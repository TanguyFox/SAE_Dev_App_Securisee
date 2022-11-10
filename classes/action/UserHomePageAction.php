<?php

namespace netvod\action;

use netvod\contenu\serie\Serie;
use netvod\renderer\Renderer;
use netvod\renderer\SerieRenderer;

class UserHomePageAction extends Action
{

    public function execute(): string
    {
        if (!isset($_SESSION['user']))
            header('Location: ?action=signin&error=notConnected');
        $user = unserialize($_SESSION['user']);
        if (empty($user->prenom))
            $affiche = $user->email;
        else
            $affiche = $user->prenom;

        $html = <<<HTML
                    <h1>Home Page</h1>
                    <p>Welcome {$affiche}</p>

                        <a href='?action=accueil-catalogue' type='button' class='btn btn-primary'>Catalogue</a><br>
                        Vos favoris :<br> 
HTML;

        return $html;
    }
}