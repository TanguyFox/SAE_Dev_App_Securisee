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
        $catalogueRenderer = new CatalogueRenderer();
        if ($this->http_method == 'GET') {

            $catalogue = <<<END
            <form method='post' action='?action=accueil-catalogue'>
                <label>Recherche : <input type="search" name="search"></label>
            </form> 
            {$catalogueRenderer->render(Renderer::LONG)};
END;
            return $catalogue;
        } else {

            $keywords = explode(" ", $_POST['search']);
            $series = [];
            foreach ($keywords as $words){
               $series = array_merge($series, Serie::getSerieFromKeyWords($words));
            }
        }
        return $catalogueRenderer->renderSearch($series);
    }
}