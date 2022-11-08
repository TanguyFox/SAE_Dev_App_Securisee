<?php

namespace netvod\renderer;

use netvod\contenu\serie\episode\Episode;

class EpisodeRenderer implements Renderer
{

    private Episode $episode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function render(int $selector): string
    {
        return $this->rendered = ($selector == Renderer::COMPACT) ? $this->compact(): $this->rendererLong();
    }

    protected function rendererLong():String
    {
        return '
        <div class="card" style="width: 18rem;">
          <!--TODO A FAIRE MAXIME-->
          <div class="card-body">
            <h5 class="card-title">'.$this->episode->titre.' <small> '.$this->serie->annee.' </small></h5>
            <p class="card-text">'.$this->episode->resume.'</p>
            <a href="#" class="btn btn-primary">Details</a>
          </div>
        </div>
        ';
    }

}