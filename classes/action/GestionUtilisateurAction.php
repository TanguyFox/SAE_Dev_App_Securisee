<?php

namespace netvod\action;

class GestionUtilisateurAction extends Action
{

    public function execute(): string
    {
        if(!isset($_SESSION['user'])){
            $html = "Non connecté";
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $html = <<<END
            <form method="post" action="?action=gestion-utilisateur">
                <label>Nom : <input type="text" name="name_user" autofocus></label>
                <label>Prénom : <input type="text" name="first_name"></label>
                <select name="genre_pref">
                    <option value="action">Action</option>
                    <option value="comedie">Comédie</option>
                    <option value="horreur">Horreur</option>
                    <option value="htriller">Thriller</option>
                    <option value="suspense">Suspense</option>
                    <option value="animation">Animation</option>
                    <option value="histoire">Histoire</option>
                    <option value="sci-fi">Science-Fiction</option>
                    <option value="drame">Drame</option>
                </select>
                <button type="submit">Modifier</button>
            </form>
END;

        }else{
            $name = filter_var($_POST['name'],FILTER_SANITIZE_SPECIAL_CHARS);
            $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre_pr = $_POST['genre_pref'];

            $user = unserialize($_SESSION['user']);
            $user->nom = $name;
            $user->prenom = $first_name;
            $user->genre_pref = $genre_pr;

            $user->updateInfos();

            $html = "Vos informations ont bien été enregistrées";
        }

        return $html;
    }
}