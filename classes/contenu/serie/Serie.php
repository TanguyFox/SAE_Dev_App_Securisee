<?php

namespace netvod\contenu\serie;

use netvod\contenu\serie\episode\Episode;
use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;
use netvod\exceptions\NonEditablePropertyException;

define('FAV', 'lVideoPref');
define('WATCHED', 'lVideoVisio');
define('WATCHLIST', 'lVideoEnCours');

class Serie
{
    private int $id;
    private string $titre;
    private string $description;
    private string $image;
    private int $annee;
    private string $date_ajout;

    public function __construct(int $id, string $titre, string $description="", string $image=null, int $annee=null, string $date_ajout=null){
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->image = ($image =="") ? "https://img.icons8.com/fluency/512/laptop-play-video.png":$image;
        $this->annee = $annee ?? date("Y");
        $this->date_ajout = $date_ajout ?? date("Y-m-d");
    }

    public function getEpisodes(): array{
        $sql = "SELECT * FROM episode WHERE episode.serie_id = :id";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $result = $stmt->fetchAll();
        $episodes = [];
        foreach($result as $episode){
            $episodes[] = new Episode($episode['id'], $episode['numero'], $episode['titre'], $episode['resume'], $episode['duree'], $episode['file'], $episode['serie_id']);
        }
        return $episodes;
    }

    public function __set(string $name, mixed $value): void {
        if($name == "titre" or $name == "date_ajout") { throw new NonEditablePropertyException("Propriété non-éditable"); }
        $this->$name = $value;
    }

    public function __get(string $name): mixed {
        return (isset($this->$name)) ? $this->$name : throw new InvalidPropertyNameException("Proprietée invalide");
    }

    public static function getSerieFromId(int $id): false|Serie{
        $sql = "SELECT * FROM serie WHERE id = :id";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        if (!$result) {
            return false;
        } else {
            return new Serie($result["id"], $result['titre'], $result['descriptif'], $result['img'], $result['annee'], $result['date_ajout']);
        }
    }

    public function getNote(): string{
        $sql = "SELECT AVG(note) as moyenne FROM avis WHERE serie_id = :id";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id' => $this->id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(!$result || is_null($result['moyenne'])){
            $note = "Non notée";
        }else{
            $note = "{$result['moyenne']}/10";
        }
        return $note;
    }

    public static function ajouterListe($serie_id, $user_id, $genre) : bool{
        $sql = "SELECT id_list FROM user2list WHERE id_user = :id_user AND nom_genre = :genre";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id_user' => $user_id, 'genre' => $genre]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id_list = $result['id_list'];
        $sql = "INSERT INTO list2series (id_list, id_series) VALUES (:id_list, :id_serie)";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id_list' => $id_list, 'id_serie' => $serie_id]);
        if ($stmt->rowCount() == 1) return true;
        else return false;
    }

}
