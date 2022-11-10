<?php

namespace netvod\action;

use netvod\contenu\serie\Serie;
use netvod\renderer\CatalogueRenderer;
use netvod\renderer\Renderer;
use netvod\renderer\SerieRenderer;
use netvod\user\User;

class UserHomePageAction extends Action
{

    public function execute(): string
    {
        if (!isset($_SESSION['user']))
            header('Location: ?action=signin&error=notConnected');
        $user = unserialize($_SESSION['user']);
        if ($user->prenom === "") {
            $affiche = $user->email . "<br>";
        } else {
            $affiche = $user->prenom . "<br>";
        }

        $html = <<<HTML
                <div class="user_home_page">
                    <h2 style="text-align: center">Welcome {$affiche}</h2>
                    <p style="text-align: center"> Votre genre préféré est {$user->genre_pref} </p>
                        <h3 style="margin-left: 1em; text-decoration: underline">Vos favoris :</h3><br> 
                
HTML;
        $html .= $this->renderFavoris($user);

        $html .= <<<HTML
                  
                        <h3 style="margin-left: 1em; text-decoration: underline">Votre Watchlist :</h3><br> 
                </div>
HTML;
        $html .= $this->renderWatchlist($user);
        return $html;

    }


    private
    function renderFavoris(User $user): string
    {
        $html = "";
        $series = $user->getSeriesList(genre: User::FAV);
        if (empty($series))
            return "Vous n'avez pas de favoris <br>";
        $html .= (new CatalogueRenderer($series))->render(Renderer::COMPACT);
        return $html;
    }

    private
    function renderWatchlist(User $user): string
    {
        $html = "";
        $series = $user->getSeriesList(genre: User::WATCHLIST);
        if (empty($series))
            return "Vous n'avez pas de whatchlist <br>";
        $html .= (new CatalogueRenderer($series))->render(Renderer::COMPACT);
        return $html;
    }
}