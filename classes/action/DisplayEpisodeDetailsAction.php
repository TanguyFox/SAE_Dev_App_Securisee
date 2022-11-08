<?php

namespace netvod\action;

use netvod\contenu\serie\episode\Episode;
use netvod\renderer\EpisodeRenderer;
use netvod\renderer\Renderer;

class DisplayEpisodeDetailsAction extends Action
{

    public function execute(): string
    {
        if ($this->http_method == 'GET') {
            return (new EpisodeRenderer(Episode::getEpisodeFromId($_GET['id'])))->render(Renderer::LONG);
        }
    }
}