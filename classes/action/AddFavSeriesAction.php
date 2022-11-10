<?php

namespace netvod\action;

use Exception;
use netvod\user\User;

class AddFavSeriesAction extends Action
{

    public function execute(): string
    {
        if (!isset($_SESSION['user']))
            header('Location: ?action=signin&error=notConnected');
        $addfav =$_SESSION['catalogue'];
        $user = unserialize($_SESSION['user']);
        $user->addFavSeries($_GET['id']);
        $_SESSION['user'] = serialize($user);
        $_SESSION['catalogue'] = $addfav;
        $addfav .= "Série {$_GET['id']} ajoutée au favoris";
        return $addfav;
    }
}