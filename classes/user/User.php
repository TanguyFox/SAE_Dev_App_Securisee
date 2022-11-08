<?php
namespace netvod\user;
use netvod\exceptions\InvalidPropertyNameException;

class User
{
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private array $profiles;

    public function __construct(string $n, string $p, string $mail, string $pwd, array $profile=[]){
        $this->nom=$n;
        $this->prenom=$p;
        $this->email=$mail;
        $this->password=$pwd;
        $this->profiles=$profile;
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
}