<?php

namespace netvod\user;

use netvod\db\ConnexionFactory;
use netvod\exceptions\InvalidPropertyNameException;

class Account
{
    private string $avatar;
    private string $nom;
    private array $fav;
    private array $watched;
    private array $continue;

    public function __construct(string $img, string $n, array $f=[], array $w=[],array $c=[]){
        $this->avatar=$img;
        $this->nom=$n;
        $this->fav=$f;
        $this->watched=$w;
        $this->continue=$c;
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

    public function insertAccount():void{
        $db = ConnexionFactory::makeConnection();
        $u = unserialize($_SESSION['user']);
        $stmt = $db->prepare("SELECT `id` FROM utilisateur WHERE email = ?");
        $stmt->execute([$u->email]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id_user = $res['id'];

        $stmt2 = $db->prepare("INSERT INTO profil(`user_id`,`profil_name`,`img_avatar`) VALUES (?,?,?)");
        $stmt2->execute([$id_user,$this->nom,$this->avatar]);

        $u->addAccount($this);
    }

    public function getId():string{
        $db= ConnexionFactory::makeConnection();
        $stmt = $db->prepare("SELECT `id` FROM profil WHERE profil_name=?");
        $stmt->execute([$this->nom]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res['id'];
    }

}