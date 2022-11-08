<?php
namespace netvod\user;
use netvod\exceptions\InvalidPropertyNameException;
use netvod\exceptions\InvalidPropertyValueException;


class User
{
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private array $accounts;

    public function __construct(string $n, string $p, string $mail, string $pwd, array $account=[]){
        $this->nom=$n ?? "";
        $this->prenom=$p ?? "";
        $this->email=$mail;
        $this->password=$pwd;
        $this->accounts=$account;
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
    public function getAccount($accountId): ?Account
    {
        foreach($this->accounts as $account) {
            if ($accountId == $account->id) return $account;
        }
        throw new InvalidPropertyValueException("Compte inexistant");
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