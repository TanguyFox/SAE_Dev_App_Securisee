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
        return $html;
    }
}