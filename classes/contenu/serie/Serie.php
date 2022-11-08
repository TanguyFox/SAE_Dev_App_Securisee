<?php

namespace netvod\contenu\serie;

use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;
use netvod\exceptions\NonEditablePropertyException;

class Serie
{
    private string $titre;
    private string $description;
    private string $image;
    private int $annee;
    private string $date_ajout;

    public function __construct(string $titre, string $description="", string $image=null, int $annee=null, string $date_ajout=null){
        $this->titre = $titre;
        $this->description = $description;
        $this->image = $image ?? "https://img.icons8.com/fluency/512/laptop-play-video.png";
        $this->annee = $annee ?? date("Y");
        $this->date_ajout = $date_ajout ?? date("Y-m-d");
    }

    public function __set(string $name, mixed $value): void {
        if($name == "titre" or $name == "date_ajout") { throw new NonEditablePropertyException("Propriété non-éditable"); }
        $this->$name = $value;
    }

    public function __get(string $name): mixed {
        return (isset($this->$name)) ? $this->$name : throw new InvalidPropertyNameException("Proprietée invalide");
    }

    public static function getSerieFromTitre(string $titre): false|Serie{
        $sql = "SELECT * FROM serie WHERE titre = :titre";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['titre' => $titre]);
        $result = $stmt->fetch();
        if (!$result) {
            return false;
        } else {
            return new Serie($result['titre'], $result['description'], $result['image'], $result['annee'], $result['date_ajout']);
        }
    }

}
