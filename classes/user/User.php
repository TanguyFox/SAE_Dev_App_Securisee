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
    private array $accounts;
    private array $fav = [];
    private array $watched = [];
    private array $continue = [];

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

    public function addFavSeries(int $id):void{
        if(!in_array($id, $this->fav)) array_push($this->fav,$id);
        else throw new InvalidPropertyValueException("Série déjà dans vos favoris");
    }

    public function addNote(int $id, int $note) : void {
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare( "INSERT INTO avis (profil_id, episode_id, note) VALUES (:profil_id, :episode_id, :note)");
        $st->execute([':profil_id' => $_SESSION['profil']->id, ':episode_id' => $id, ':note' => $note]);
    }

}