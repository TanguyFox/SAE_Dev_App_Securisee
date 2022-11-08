<?php

namespace netvod\action;

use netvod\auth\Auth;
class SignInAction extends Action
{

    public function execute(): string
    {
        $html="";
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html .= <<<END
                    Connexion :<br><br>
                    <form method="post" action="?action=signin">
                        <label>e-mail <input type="email" name="email"></label><br><br>
                        <label>Mot de passe : <input type="password" name="passw" value=""> </label>
                        <button type="submit">Se connecter</button>
                    </form>
                    END;
        } else {
            try {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $mdp = filter_var($_POST['passw'], FILTER_SANITIZE_SPECIAL_CHARS);
                Auth::authenticate($email, $mdp);
                $utilisateur = unserialize($_SESSION['user']);
                $html .= "Bienvenue sur NetVod {$utilisateur->prenom} !";
                $html .= "<a href='?action=acess-profile'> Choississez votre profil</a><ul>";
                if(empty($utilisateur->profiles)){
                    $html .= "Vous n'avez pas de profil pour le moment... Créez-en un !";
                }
                foreach ($utilisateur->profiles as $profil) {
                    $html = "<li>$profil->nom</li>";
                }
                $html .= "</ul>";
            }catch(\netvod\exceptions\AuthException $e) {
                $html = "Echec d'authentification : " . $e->getMessage();
            }
        }
        return $html;
    }
}