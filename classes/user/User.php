<?php
namespace netvod\user;
use netvod\contenu\serie\Serie;
use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;
use netvod\exceptions\InvalidPropertyValueException;

class User
{

const FAV = 'lVideoPref';
const WATCHED = 'lVideoVisio';
const WATCHLIST = 'lVideoEnCours';

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

    public static function ajouterListe($serie_id, $user_id, $genre) : bool{
        $sql = "SELECT list_id FROM user2list WHERE user_id = :user_id AND nom_genre = :genre";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'genre' => $genre]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id_list = $result['id_list'];
        $sql = "INSERT INTO list2series (list_id, serie_id) VALUES (:list_id, :serie_id)";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['list_id' => $id_list, 'serie_id' => $serie_id]);
        if ($stmt->rowCount() == 1) return true;
        else return false;
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

    public static function getSeriesList(string $genre) : array{
        $user_id = self::getUserId();
        $sql = "SELECT list_id FROM user2list WHERE  user_id = :user_id AND nom_genre = :genre";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute([$user_id,'genre' => $genre]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result)
            $id_list = $result['list_id'];
        else
            return [];
        $sql = "SELECT serie_id FROM list2series WHERE list_id = :list_id";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['list_id' => $id_list]);
        $result = $stmt->fetchAll();
        $series = [];
        foreach($result as $serie){
            $series[] = Serie::getSerieFromId($serie['serie_id']);
        }
        return $series;
    }
}