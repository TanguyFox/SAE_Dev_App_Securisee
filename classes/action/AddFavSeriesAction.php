<?php

namespace netvod\action;

use Exception;
use User;

class AddFavSeriesAction extends Action
{

    public function execute(): string
    {
        if (isset($_SESSION['user'], $_SESSION['account'])) {
            $user = unserialize($_SESSION['user']);
            $account = unserialize($_SESSION['account']);
            try {
                $user->getAccount($account)->addFavSeries($_GET['id']);
            } catch (Exception $e) {
                return "Erreur : " . $e->getMessage();
            }
            $_SESSION['user'] = serialize($user);
        } else
            header('Location: index.php?action=signin');
        return "true";
    }
}