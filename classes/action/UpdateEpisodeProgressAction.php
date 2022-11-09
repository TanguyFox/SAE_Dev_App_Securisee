<?php

namespace netvod\action;

class UpdateEpisodeProgressAction extends Action
{

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $time = filter_var($_POST['time'], FILTER_SANITIZE_NUMBER_FLOAT);
            $user = $_SESSION['user'];
            $user->updateEpisodeProgress($id, $time); //methode à créer quand la fonctionnalité de profil sera finalisée
        }
    }
}