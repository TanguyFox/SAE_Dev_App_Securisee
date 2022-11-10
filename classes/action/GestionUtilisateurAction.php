<?php

namespace netvod\action;

class GestionUtilisateurAction extends Action
{

    public function execute(): string
    {
        $html="";
        if(!isset($_SESSION['user'])){
            $html = "Non connecté";
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $html = <<<END
            <form method="post" action="?action=gestion-utilisateur">
                <label>Nom : <input type="text" name="name_user" autofocus></label>
                <label>Prénom : <input type="text" name="first_name"></label>
                <select name="genre_pref">
                    <option value=""> </option>
                    <option value="action">Action</option>
                    <option value="comedie">Comédie</option>
                    <option value="horreur">Horreur</option>
                    <option value="thriller">Thriller</option>
                    <option value="suspense">Suspense</option>
                    <option value="animation">Animation</option>
                    <option value="histoire">Histoire</option>
                    <option value="sci-fi">Science-Fiction</option>
                    <option value="drame">Drame</option>
                </select>
                <button type="submit">Modifier</button>
            </form>
END;

        }else {
            $user = unserialize($_SESSION['user']);
            if ($_POST['name_user'] !== "") {
                $name = filter_var($_POST['name_user'], FILTER_SANITIZE_SPECIAL_CHARS);
                $user->nom = $name;
            }
            if ($_POST['first_name'] !== ""){
                $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_SPECIAL_CHARS);
                $user->prenom = $first_name;
            }
            if($_POST['genre_pref'] !== ""){
                $genre_pr = $_POST['genre_pref'];
                $user->genre_pref = $genre_pr;
            }

            $user->updateInfos();

            $_SESSION['user'] = serialize($user);

            $html .= "Vos informations ont bien été enregistrées<br> Votre genre préféré est $user->genre_pref";
        }

        return $html;
    }
}