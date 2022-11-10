<?php
namespace netvod\user;
use netvod\contenu\serie\Serie;
use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;
use netvod\exceptions\InvalidPropertyValueException;

define('FAV', 'lVideoPref');
define('WATCHED', 'lVideoVisio');
define('WATCHLIST', 'lVideoEnCours');

class User
{
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;

    public function __construct(string $n, string $p, string $mail, string $pwd){
        $this->nom=$n ?? "";
        $this->prenom=$p ?? "";
        $this->email=$mail;
        $this->password=$pwd;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $attr):mixed{
        if (property_exists ($this, $attr)) return $this->$attr;
        throw new InvalidPropertyNameException(" $attr: invalid property");
    }


    /**
     * @throws InvalidPropertyValueException
     */
    public function getAccount($accountId): Account
    {
        foreach($this->accounts as $account) {
            if ($accountId == $account->id) return $account;
        }
        throw new InvalidPropertyValueException("Compte inexistant");
    }

    public function addAccount(Account $acc): void{
        if($this->nbAccount <4) {
            array_push($this->accounts, $acc);
            $this->nbAccount++;
        }else{
            throw new \UserException(" Vous possédez déjà 4 comptes");
        }
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __set(string $attr, mixed $value):void{
        if ( property_exists ($this, $attr) ) {
            $this->$attr = $value;
        } else throw new InvalidPropertyNameException(" $attr: invalid property");
    }

	/**
	 * @throws InvalidPropertyValueException
	 */
	public function addFavSeries(int $id):void{
        if(!in_array($id, $this->fav)) array_push($this->fav,$id);
        else throw new InvalidPropertyValueException("Série déjà dans vos favoris");
    }

    public function addNote(int $id, int $note) : void {
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare( "INSERT INTO avis (user_id, serie_id, note) VALUES (:user_id, :serie_id, :note)");
        $st->execute([':profil_id' => $_SESSION['profil']->id, ':episode_id' => $id, ':note' => $note]);
    }

	public function addCom(int $id, int $com) : void {
		$db = ConnexionFactory::makeConnection();
		$st = $db->prepare( "INSERT INTO avis (user_id, serie_id, commentaire) VALUES (:user_id, :serie_id, :commentaire)");
		$st->execute([':user_id' => $_SESSION['user']->id, ':serie_id' => $id, ':commentaire' => $com]);
	}

    public static function getSeriesList($genre) : array{
        $sql = "SELECT list_id FROM user2list WHERE  user_id = :user_id AND nom_genre = :genre";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['genre' => $genre]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id_list = $result['list_id'];
        $sql = "SELECT serie_id FROM list2series WHERE list_id = :list_id";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['list_id' => $id_list]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $series = [];
        foreach($result as $serie){
            $series[] = Serie::getSerieFromId($serie['serie_id']);
        }
        return $series;
    }

}