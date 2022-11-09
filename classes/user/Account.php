<?php

namespace netvod\user;

use netvod\exceptions\InvalidPropertyNameException;
use netvod\exceptions\InvalidPropertyValueException;

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

    /**
     * @throws InvalidPropertyValueException
     */
    public function addFavSeries(int $id):void{
        if(!in_array($id, $this->fav)) $this->fav[]=$id;
        else throw new InvalidPropertyValueException("Série déjà dans vos favoris");
    }


}