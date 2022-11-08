<?php

namespace netvod\action;

use netvod\renderer\CatalogueRenderer;
use netvod\renderer\Renderer;

class AccueilCatalogueAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == 'GET') {
            $CatalogueRenderer = new CatalogueRenderer();
            return $CatalogueRenderer->render(Renderer::LONG);
        } else {
            throw new \Exception('Méthode HTTP non autorisée');
        }
    }
}