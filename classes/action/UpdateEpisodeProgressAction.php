<?php

namespace netvod\action;

class UpdateEpisodeProgressAction extends Action
{

    public function execute(): string
    {
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $time = floatval($_POST['avancement']); //cannot be sanitized for some reason but function should fail if not a float
            $user = unserialize($_SESSION['user']);
            $user->updateEpisodeProgress($id, $time); //methode à créer quand la fonctionnalité de profil sera finalisée
            $html .= 'Mis à jour';
        }else{
            //put the value of recupererAvancement($id) in a div
            $user = unserialize($_SESSION['user']);
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $html .= '<div id="avancement">'. $user->recupererAvancement($id) . '</div>';
        }
        return $html;
    }
}