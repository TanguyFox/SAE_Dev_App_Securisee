<?php

namespace netvod\action;

use netvod\contenu\serie\Serie;
use netvod\renderer\Renderer;
use netvod\renderer\SerieRenderer;
use netvod\user\User;

class UserHomePageAction extends Action
{

    public function execute(): string
    {
       if (!isset($_SESSION['user'])) {
            header('Location: ?action=signin&error=notConnected');
        } else {


           $user = unserialize($_SESSION['user']);
           if ($user->prenom === "") {
               $affiche = $user->email . "<br>";
           } else {
               $affiche = $user->prenom . "<br>";
           }

           $html = <<<HTML
                    <h1>Home Page</h1>
                    <p>Welcome {$affiche}</p>
                        Votre genre préféré : {$user->genre_pref}
                        <a href='?action=accueil-catalogue' type='button' class='btn btn-primary'>Catalogue</a><br>
                        <a href='?action=gestion-utilisateur' type='button' class='btn btn-primary'>Gestion du profil</a><br>
                        Vos favoris :<br> 
HTML;
           $html .= $this->renderFavoris($user);
       }
            return $html;
    }


    private function renderFavoris(User $user): string {
        $html = "";
        $series = $user->getSeriesList(genre: User::FAV);
        if (empty($series))
            return "Vous n'avez pas de favoris <br>";
        foreach ($series as $serie) {
            $html .= (new SerieRenderer($serie))->render(Renderer::COMPACT);
        }
        return $html;
    }
}