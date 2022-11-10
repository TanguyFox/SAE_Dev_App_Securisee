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
            $html .= <<<END
                    Connexion :<br><br>
                    <form id="login" method="post" action="?action=signin">
                        <label>e-mail <input type="email" name="email"></label><br><br>
                        <label>Mot de passe : <input type="password" name="passw" value=""> </label>
                        <button type="submit">Se connecter</button>
                    </form>
                    END;

            if (isset($_SESSION['user'])) {
                $html .= '<script>document.getElementById("login").submit()</script>';
            }
        } else {
            try {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $mdp = filter_var($_POST['passw'], FILTER_SANITIZE_SPECIAL_CHARS);
                if(isset($_SESSION['user']) or Auth::authenticate($email, $mdp))
                    header('Location: ?action=user-home-page');
                else
                    header('Location: ?action=signin&error=wrongCredentials');


            } catch (e\UserException $e) {
                $html .= $e->getMessage() . "<a href='?action=register'> Créez-en un !</a>";
            } catch (e\AuthException $e2) {
                $html .= "Echec d'authentification" . $e2->getMessage();
            }
        }
        return $html;
    }
}