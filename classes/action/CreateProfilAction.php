<?php

namespace netvod\action;

use netvod\user\Account;

class CreateProfilAction extends Action
{

    public function execute(): string
    {
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $html = <<<END
                <form method='post' action='?action=create-profil'>
                <label>Nom du profil : <input type="text" name="name_profil"></label>
                <label>Vous pouvez insérez une image de profil !(format accepté : jpeg, png) <input type="file" name="img"></label>
                <button type="submit">Créer compte</button>
</form>
END;
        } else {
            $uploaded_dir = "images/profiles";
            $filename = uniqid();
            $tmp = $_FILES['inputfile']['tmp_name'];

            if (($_FILES['inputfile']['error'] === UPLOAD_ERR_OK) &&
                (str_ends_with($_FILES['inputfile']['name'], '.png')
                    || str_ends_with($_FILES['inputfile']['name'], '.jpeg')) &&
                $_FILES['inputfile']['type'] === 'audio/mpeg') {

                $dest = $uploaded_dir . $filename . $_FILES['inputfile']['name'];

                if (move_uploaded_file($tmp, $dest)) {
                    if(isset($_SESSION['user'])){
                        $name = filter_var($_POST['name'],FILTER_SANITIZE_SPECIAL_CHARS);
                        $user_account = new Account($dest,$name);
                        $user_account->insertAccount();
                        $_SESSION['account']=$user_account;
                        $html  .= "Création du compte réussi ! <a href='?action=access-account&id={$user_account->getId()}'>Découvrez-le !</a>";
                    }
                } else {
                    $html .= "Erreur lors du chargement, <a href='?action=create-account'>veuillez recommencer</a>";
                }
            }else{
                $html .= "Format d'image non valide, <a href='?action=create-account'>veuillez recommencer</a> (accepté : png,jpeg)";
            }
        }
        return $html;
    }
}