<?php

namespace netvod\action;

use netvod\contenu\serie\Serie;
use netvod\renderer\CatalogueRenderer;
use netvod\renderer\Renderer;
use netvod\renderer\SerieRenderer;

class AccueilCatalogueAction extends Action
{

    /**
     * @throws \Exception
     */
    public function execute(): string
    {
        if(isset($_SESSION['catalogue'])) $catalogue = $_SESSION['catalogue'];
        if (!isset($_SESSION['user']))  header('Location: ?action=signin&error=notConnected');
        $user = unserialize($_SESSION['user']);
        $html = <<<HTML
                    <h1>Home Page</h1>
                    <p>Welcome {$user->email}</p>
                    <h2>Vos séries favorites</h2>
HTML;
        if (empty($user->fav)) {
            $html .= ("Vous n'avez pas encore de série en favoris");
        } else {
            foreach ($user->fav as $serie) {
                $html .= (new SerieRenderer($serie))->render(Renderer::COMPACT);
            }
        }

        if ($this->http_method == 'GET') {
            $CatalogueRenderer = new CatalogueRenderer();
            $catalogue = $html . $CatalogueRenderer->render(Renderer::LONG);
            $_SESSION['catalogue']=  $catalogue;
            return $catalogue;
        } else {
            throw new \Exception('Méthode HTTP non autorisée');
        }
    }
}