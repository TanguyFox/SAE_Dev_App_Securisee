<?php

namespace netvod\action;

use Exception;
use User;

class AddFavSeriesAction extends Action
{

    public function execute(): string
    {
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            try {
                $user->addSeries($_GET['id']);
            } catch (Exception $e) {
                return "true";
            }
            $_SESSION['user'] = serialize($user);
        } else
            header('Location: index.php?action=signin');
    }
}