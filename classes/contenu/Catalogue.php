<?php

namespace netvod\contenu;

use netvod\contenu\serie\Serie;
use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;

class Catalogue
{
    private array $series;

    public function __construct()
    {
        $this->series = Catalogue::getSeries();
    }

    public static function getSeries(): array{
        $sql = "SELECT * FROM serie";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $series = [];
        foreach($result as $serie){
            $series[] = new Serie($serie['titre'], $serie['descriptif'], $serie['img'], $serie['annee'], $serie['date_ajout']);
        }
        return $series;
    }

    public function __get(string $name)
    {
        return (isset($this->$name)) ? $this->$name : throw new InvalidPropertyNameException("Proprietée invalide");
    }

}