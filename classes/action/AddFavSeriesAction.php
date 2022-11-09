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
        if (!isset($_SESSION['account']))
            header('Location: ?action=access-profile&error=noAccount');
        $user = unserialize($_SESSION['user']);
        $account = ($_SESSION['account']);
        $account->addFavSeries($_GET['id']);
        return "";
    }
}