<?php

namespace netvod\user;

use Exception;
use netvod\contenu\serie\Serie;
use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;
use PDOException;


class User
{

    const FAV = 'lVideoPref';
    const WATCHED = 'lVideoVisio';
    const WATCHLIST = 'listevideoencours';

    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $genre_pref;

    public function __construct(string $n, string $p, string $mail, string $pwd,string $genre="Aucun")
    {
        $this->nom = $n ?? "";
        $this->prenom = $p ?? "";
        $this->email = $mail;
        $this->password = $pwd;
        $this->genre_pref = $genre ;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $attr): mixed
    {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new InvalidPropertyNameException(" $attr: invalid property");
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __set(string $attr, mixed $value): void
    {
        if (property_exists($this, $attr)) {
            $this->$attr = $value;
        } else throw new InvalidPropertyNameException(" $attr: invalid property");
    }

    public function ajouterListe(int $serie_id, User $user, $genre): void
    {
        $user_id = $user->getIdFromDb();
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare("SELECT list_id FROM user2list WHERE user_id = :user_id AND nom_genre = :genre");
        $st->execute(['user_id' => $user_id, 'genre' => $genre]);
        $list_id = $st->fetch()['list_id'];
        if ($list_id == null) {
            $this->createSerieList($user, $genre);
        }
        $st = $db->prepare("INSERT INTO list2series (list_id, serie_id) VALUES (:list_id, :serie_id)");
        try {
            $st->execute(['list_id' => $list_id, 'serie_id' => $serie_id]);
            header('Location: ?action=accueil-catalogue');
        } catch (PDOException $e) {
            header('Location: ?action=accueil-catalogue&error=alreadyInList');
        }
    }


    public function updateInfos(): void
    {
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare("UPDATE utilisateur SET nom=?, prenom=?, genre_pref=?");
        $st->execute([$this->nom, $this->prenom, $this->genre_pref]);
    }


    public function addNote(int $id, int $note): void
    {
        $sql = "SELECT * FROM avis WHERE avis.serie_id = :id AND avis.user_id = :user_id";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $this->getIdFromDb()]);
        $result = $stmt->fetch();
        if (!$result) {
            $sql = "INSERT INTO avis (serie_id, user_id, note) VALUES (:id, :user_id, :note)";
        } else {
            $sql = "UPDATE avis SET note = :note WHERE avis.serie_id = :id AND avis.user_id = :user_id";
        }
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $this->getIdFromDb(), 'note' => $note]);
    }

    public function addCom(int $id, string $com): void
    {
        $sql = "SELECT * FROM avis WHERE avis.serie_id = :id AND avis.user_id = :user_id";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $this->getIdFromDb()]);
        $result = $stmt->fetch();
        if (!$result) {
            $sql = "INSERT INTO avis (serie_id, user_id, commentaire) VALUES (:id, :user_id, :com)";
        } else {
            $sql = "UPDATE avis SET commentaire = :com WHERE avis.serie_id = :id AND avis.user_id = :user_id";
        }
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $this->getIdFromDb(), 'com' => $com]);
    }

    public function getIdFromDb(): int
    {
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare("SELECT id FROM utilisateur WHERE email = :email");
        $st->execute([':email' => $this->email]);
        $result = $st->fetch();
        return $result['id'];
    }

    public function updateEpisodeProgress(int $id, float $time): void
    {
        //Is there already a listevideoencours (progress) for this episode?
        //If no, create it
        //If yes, update it
        $db = ConnexionFactory::makeConnection();
        $st1 = $db->prepare("SELECT * FROM listencours INNER JOIN user2list 
                                                                    ON user2list.nom_genre='listevideoencours'
                                                                        AND user2list.list_id=listencours.id
                                                                    WHERE listencours.episode_id=:id
                                                                        AND user2list.user_id=:id_user;");
        $st1->execute([':id' => $id, ':id_user' => $this->getIdFromDB()]);
        $result = $st1->fetch();
        if ($result) {
            //update progress of episode in listevideoencours (progress) for this user (id) and this episode (id)
            $st = $db->prepare("UPDATE listencours SET tpsVisio=:time WHERE episode_id=:id 
                                                                    AND id=(
                                                                        SELECT list_id FROM user2list 
                                                                                       WHERE user_id=:id_user 
                                                                                         AND nom_genre='listevideoencours'
                                                                                         AND list_id=:id_list
                                                                    );"
            );
            $st->execute([':id' => $id, ':id_user' => $this->getIdFromDB(), ':time' => $time, ':id_list' => $result['id']]);
        } else {
            $st = $db->prepare("INSERT INTO listencours (episode_id, tpsVisio) VALUES (:id, :temps)");
            $st->execute([':id' => $id, ':temps' => $time]);

            $st = $db->prepare("SELECT id FROM listencours ORDER BY id DESC LIMIT 1");
            $st->execute();
            $result = $st->fetch();
            $id_list = $result['id'];

            $st = $db->prepare("INSERT INTO user2list (user_id, nom_genre, list_id) VALUES (:id_user, 'listevideoencours', :id_list)");
            $st->execute([':id_user' => $this->getIdFromDB(), ':id_list' => $id_list]);
        }
    }

    public function recupererAvancement(int $id): int
    {
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare("SELECT * FROM listencours INNER JOIN user2list 
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

    public function getSeriesList(string $genre): array
    {
        $user_id = $this->getIdFromDb();
        $sql = "SELECT list_id FROM user2list WHERE  user_id = :user_id AND nom_genre = :genre";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute([$user_id, 'genre' => $genre]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$result) {
            return [];
        }
        $id_list = $result['list_id'];
        if ($genre != User::WATCHLIST)
            $sql = "SELECT serie_id FROM list2series WHERE list_id = :list_id";
        else {
            $sql = "SELECT serie_id FROM episode,listencours WHERE episode.id = listencours.episode_id AND listencours.id = :list_id";
        }
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['list_id' => $id_list]);
        $result = $stmt->fetchAll();
        $series = [];
        foreach ($result as $serie) {
                $series[] = Serie::getSerieFromId($serie['serie_id']);
        }
        return $series;
    }

    private function createSerieList(User $user, string $genre): void
    {
        $user_id = $user->getIdFromDb();
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare("INSERT INTO user2list (user_id, nom_genre) VALUES (:user_id, :genre)");
        $st->execute(['user_id' => $user_id, 'genre' => $genre]);
    }

    public function supprimerListe(int $serie_id, User $user, $genre): void
    {
        $user_id = $user->getIdFromDb();
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare("DELETE FROM list2series WHERE serie_id=:serie_id AND list_id=(
			SELECT list_id FROM user2list WHERE user_id=:user_id AND nom_genre=:genre
		)");
        $st->execute(['serie_id' => $serie_id, 'user_id' => $user_id, 'genre' => $genre]);
    }
}