<?php

namespace netvod\action;

use Exception;
use netvod\contenu\serie\Serie;
use netvod\db\ConnexionFactory;
use netvod\user\User;

define('FAV', 'favoris');

class AddFavSeriesAction extends Action
{

    public function execute(): string
    {
        if (!isset($_SESSION['user']))
            header('Location: ?action=signin&error=notConnected');
        $user = unserialize($_SESSION['user']);
        $user->addFavSeries($_GET['id']);
        $_SESSION['user'] = serialize($user);
        Serie::ajouterListe($_GET['id'], $user->getId(),  );
        return "Série {$_GET['id']} ajoutée au favoris";
    }
}