<?php

namespace netvod\action;

use netvod\contenu\serie\Serie;
use netvod\renderer\CatalogueRenderer;
use netvod\renderer\Renderer;
use netvod\renderer\SerieRenderer;

class AccueilCatalogueAction extends Action
{

    public function execute(): string
    {
        if (!isset($_SESSION['user']))
            header('Location: ?action=signin&error=notConnected');
        if (!isset($_SESSION['accountId']))
            header('Location: ?action=create-account&error=noAccount');
        $user = $_SESSION['user'];
        $accountId = $_SESSION['accountId'];
        $account = $user->getAccount($accountId);
        $html = <<<HTML
                    <h1>Home Page</h1>
                    <p>Welcome {$account->name}</p>
HTML;
        if (empty($account->fav)) {
            $html .= ("Vous n'avez pas encore de série en favoris");
        } else {
            $html .= <<<HTML
                    <h2>Vos séries favorites</h2>
HTML;
            foreach ($account->fav as $serie) {
                $html .= (new SerieRenderer($serie))->render(Renderer::COMPACT);
            }
        }

        if ($this->http_method == 'GET') {
            $CatalogueRenderer = new CatalogueRenderer();
            return $CatalogueRenderer->render(Renderer::LONG);
        } else {
            throw new \Exception('Méthode HTTP non autorisée');
        }
    }
}