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
        $user->ajouterListe($_GET['id'], $user, genre : USER::FAV );
        return "";
    }
}