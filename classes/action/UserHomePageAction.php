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
        if (!isset($_SESSION['accountId']))
            header('Location: ?action=access-profile&error=noAccount');
        $user = $_SESSION['user'];
        $accountId = $_SESSION['accountId'];
        $account = $user->getAccount($accountId);
        $html = <<<HTML
                    <h1>Home Page</h1>
                    <p>Welcome {$account->__get("name")}<</p>
HTML;
        if ($account->__get("fav") == []) {
            $html .= ("Vous n'avez pas encore de série en favoris");
        } else {
            $html .= <<<HTML
                    <h2>Vos séries favorites</h2>
HTML;
            foreach ($account->__get("fav") as $serieId) {
                $serie = Serie::getSerieFromId($serieId);
                $html .= (new SerieRenderer($serie))->render(Renderer::COMPACT);
            }
        }
        return $html;
    }
}