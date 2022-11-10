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
        if ($this->http_method == 'GET') {
            $CatalogueRenderer = new CatalogueRenderer();
            $catalogue = $CatalogueRenderer->render(Renderer::LONG);
            $_SESSION['catalogue']=  $catalogue;
            return $catalogue;
        } else {
            throw new \Exception('Méthode HTTP non autorisée');
        }
    }
}