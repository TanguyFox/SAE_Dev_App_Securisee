<?php

namespace netvod\action;

use netvod\auth\Auth;
use netvod\exceptions as e;

class SignInAction extends Action
{

    public function execute(): string
    {
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Location: ?');
            exit();
        } else {
            try {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $mdp = filter_var($_POST['passw'], FILTER_SANITIZE_SPECIAL_CHARS);
                if (isset($_SESSION['user']) or Auth::authenticate($email, $mdp))
                    header('Location: ?action=user-home-page');
            } catch (e\UserException|e\AuthException $e) {
                header('Location: ?&error=wrongCredentials');
                exit();
            }
        }
        return $html;

    }
}