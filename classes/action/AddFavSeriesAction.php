<?php

namespace netvod\action;

use Exception;
use netvod\contenu\serie\Serie;
use netvod\db\ConnexionFactory;
use netvod\user\User;

class AddFavSeriesAction extends Action
{

    public function execute(): string
    {
        if (!isset($_SESSION['user']))
            header('Location: ?action=signin&error=notConnected');
        $user = unserialize($_SESSION['user']);
        $user->addFavSeries($_GET['id']);
        $_SESSION['user'] = serialize($user);
        User::ajouterListe($_GET['id'], $user->getId(), genre : FAV );
        return "Série {$_GET['id']} ajoutée au favoris";
    }
}