<?php

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
}