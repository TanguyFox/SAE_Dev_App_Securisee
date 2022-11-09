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
                    <form id="login" method="post" action="?action=signin">
                        <label>e-mail <input type="email" name="email"></label><br><br>
                        <label>Mot de passe : <input type="password" name="passw" value=""> </label>
                        <button type="submit">Se connecter</button>
                    </form>
                    END;

            if(isset($_SESSION['user'])){
                $html .= '<script>document.getElementById("login").submit()</script>';
            }
        } else {
            try {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $mdp = filter_var($_POST['passw'], FILTER_SANITIZE_SPECIAL_CHARS);
                isset($_SESSION['user']) or Auth::authenticate($email, $mdp);
                $utilisateur = unserialize($_SESSION['user']);
                $html .= "Bienvenue sur NetVod !";
                if (empty($utilisateur->accounts)) {
                    $html .= "Vous n'avez pas de profil pour le moment... <a href='?action=create-account'>Créez-en un !</a>";
                } else {
                    $html .= "<a href='?action=acess-profile'> Choississez votre profil</a><ul>";
                    foreach ($utilisateur->accounts as $acc) {
                        $html .= <<<END
                           <a href='?action=access-account'> <img src= "{$acc->avatar}"></a><br>
                            {$acc->nom}
END;

                    }
                    $html .= "<a href='?action=acess-profile'> Choississez votre profil</a><ul>";
                    foreach ($utilisateur->accounts as $account) {
                        $html = "<li>$account->nom</li>";
                    }
                    $html .= "</ul>";
                    $html .= '<a href="?action=accueil-catalogue" type="button" class="btn btn-primary">Temporaire - Catalogue</a>';


                }
            } catch
            (\netvod\exceptions\AuthException $e) {
                $html = "Echec d'authentification : " . $e->getMessage();
            }
        return $html;
}