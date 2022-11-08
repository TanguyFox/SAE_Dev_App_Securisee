<?php

namespace netvod\action;

use netvod\contenu\serie\Serie;
use netvod\renderer\Renderer;
use netvod\renderer\SerieRenderer;

class DisplaySerieAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == 'GET') {
            $serie = Serie::getSerieFromId($_GET['id']);
            $renderer = new SerieRenderer($serie);
            return $renderer->render(Renderer::LONG);
        }
    }
}