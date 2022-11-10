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

                $utilisateur = unserialize($_SESSION['user']);
                $html .= <<<END
                        <h1>Bienvenue sur NetVod !</h1> <a href='?action=accueil-catalogue' type='button' class='btn btn-primary'>Catalogue</a><br>
                        Vos favoris :<br> 
END;
                if(empty($utilisateur->fav)){
                    $html .= "Aucun favoris pour le moment...<br>";
                }else{
                    foreach ($utilisateur->fav as $series){
                        $html .= "{$series->id} - $series->titre";
                    }
                }
                $html .= "Visionné <br>";
                if(empty($utilisateur->watched)){
                    $html .= "Aucun programme vu entièrement<br>";
                }else{
                    foreach ($utilisateur->watched as $series){
                        $html .= "{$series->id} - $series->titre";
                    }
                }

                $html .= "Reprendre<br>";
                if(empty($utilisateur->continue)){
                    $html .= "Aucun programme à reprendre<br>";
                }else{
                    foreach ($utilisateur->continue as $series){
                        $html .= "{$series->id} - $series->titre";
                    }
                }


            } catch (e\UserException $e) {
                $html .= $e->getMessage() . "<a href='?action=register'> Créez-en un !</a>";
            } catch (e\AuthException $e2) {
                $html .= "Echec d'authentification" . $e2->getMessage();
            }
        }
        return $html;
    }
}