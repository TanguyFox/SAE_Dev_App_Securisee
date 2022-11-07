<?php

namespace netvod\user;

class Profil
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

}