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
        if (!isset($_SESSION['accountId']))
            header('Location: ?action=access-profile&error=noAccount');
        $user = unserialize($_SESSION['user']);
        $accountId = ($_SESSION['accountId']);
        $user->getAccount($accountId)->addFavSeries($_GET['id']);
        return "";
    }
}