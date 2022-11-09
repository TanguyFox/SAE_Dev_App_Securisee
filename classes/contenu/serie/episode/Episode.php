<?php

namespace netvod\contenu\serie\episode;

use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;
use netvod\exceptions\NonEditablePropertyException;

class Episode
{
    private int $id;
    private int $numero;
    private string $titre;
    private string $resume;
    private int $duree;
    private string $file;
    private int $serie_id;

    public function __construct(int $id, int $numero, string $titre, string $resume, int $duree, string $file, int $serie_id)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->titre = $titre;
        $this->resume = $resume;
        $this->duree = $duree;
        $this->file = $file;
        $this->serie_id = $serie_id;
    }

    public function __set(string $name, mixed $value): void {
        if($name == "numero" or $name == "serie_id") { throw new NonEditablePropertyException("Propriété non-éditable"); }
        $this->$name = $value;
    }

    public function __get(string $name): mixed {
        return (isset($this->$name)) ? $this->$name : throw new InvalidPropertyNameException("Proprietée invalide");
    }

    public static function getEpisodeFromId(int $id): false|Episode{
        $sql = "SELECT * FROM episode WHERE id = :id";
        $stmt = ConnexionFactory::makeConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        if (!$result) {
            return false;
        } else {
            return new Episode($result['id'], $result['numero'], $result['titre'], $result['resume'], $result['duree'], $result['file'], $result['serie_id']);
        }
    }

}