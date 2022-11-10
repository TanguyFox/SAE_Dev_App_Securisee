<?php
namespace netvod\user;
use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;
use netvod\exceptions\InvalidPropertyValueException;


class User
{
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $genre_pref="";

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
     * @throws InvalidPropertyNameException
     */
    public function __set(string $attr, mixed $value):void{
        if ( property_exists ($this, $attr) ) {
            $this->$attr = $value;
        } else throw new InvalidPropertyNameException(" $attr: invalid property");
    }

    public function updateInfos():void{
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare("UPDATE utilisateur SET nom=?, prenom=?, genre_pref=?");
        $st->execute([$this->nom,$this->prenom,$this->genre_pref]);
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

    public function getIdFromDB(): int{
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare("SELECT id FROM utilisateur WHERE email = :email");
        $st->execute([':email' => $this->email]);
        $result = $st->fetch();
        return $result['id'];
    }

	public function addCom(int $id, int $com) : void {
		$db = ConnexionFactory::makeConnection();
		$st = $db->prepare( "INSERT INTO avis (user_id, serie_id, commentaire) VALUES (:user_id, :serie_id, :commentaire)");
		$st->execute([':user_id' => $_SESSION['user']->id, ':serie_id' => $id, ':commentaire' => $com]);
	}

    public function updateEpisodeProgress(int $id, float $time): void {
        //Is there already a listevideoencours (progress) for this episode?
        //If no, create it
        //If yes, update it
        $db = ConnexionFactory::makeConnection();
        $st1 = $db->prepare( "SELECT * FROM listencours INNER JOIN user2list 
                                                                    ON user2list.nom_genre='listevideoencours'
                                                                        AND user2list.list_id=listencours.id
                                                                    WHERE listencours.episode_id=:id
                                                                        AND user2list.user_id=:id_user;");
        $st1->execute([':id' => $id, ':id_user' => $this->getIdFromDB()]);
        $result = $st1->fetch();
        if($result){
            //update progress of episode in listevideoencours (progress) for this user (id) and this episode (id)
            $st = $db->prepare( "UPDATE listencours SET tpsVisio=:time WHERE episode_id=:id 
                                                                    AND id=(
                                                                        SELECT list_id FROM user2list 
                                                                                       WHERE user_id=:id_user 
                                                                                         AND nom_genre='listevideoencours'
                                                                                         AND list_id=:id_list
                                                                    );"
            );
            $st->execute([':id' => $id, ':id_user' => $this->getIdFromDB(), ':time' => $time, ':id_list' => $result['id']]);
        }else{
            $st = $db->prepare( "INSERT INTO listencours (episode_id, tpsVisio) VALUES (:id, :temps)");
            $st->execute([':id' => $id, ':temps' => $time]);

            $st = $db->prepare( "SELECT id FROM listencours ORDER BY id DESC LIMIT 1");
            $st->execute();
            $result = $st->fetch();
            $id_list = $result['id'];

            $st = $db->prepare( "INSERT INTO user2list (user_id, nom_genre, list_id) VALUES (:id_user, 'listevideoencours', :id_list)");
            $st->execute([':id_user' => $this->getIdFromDB(), ':id_list' => $id_list]);
        }
    }

    public function recupererAvancement(int $id): int {
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare( "SELECT * FROM listencours INNER JOIN user2list 
                                                                    ON user2list.nom_genre='listevideoencours'
                                                                        AND user2list.list_id=listencours.id
                                                                    WHERE listencours.episode_id=:id
                                                                        AND user2list.user_id=:id_user;"
        );
        $st->execute([':id' => $id, ':id_user' => $this->getIdFromDB()]);
        $result = $st->fetch();
        var_dump($result);
        return (is_array($result) && array_key_exists('tpsVisio', $result)) ? $result['tpsVisio'] : 0;
    }

}