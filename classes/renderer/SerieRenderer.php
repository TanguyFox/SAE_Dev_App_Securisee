<?php

namespace netvod\renderer;

use netvod\contenu\serie\Serie;

class SerieRenderer implements Renderer
{

    private Serie $serie;

    public function __construct(Serie $serie)
    {
        $this->serie = $serie;
    }

    private function renderCompact(): string{
        return '
        <div class="card" style="width: 18rem;">
          <img class="card-img-top" src="'.$this->serie->image.'" alt="Serie\'s image">
          <div class="card-body">
            <h5 class="card-title">'.$this->serie->titre.' <small> '.$this->serie->annee.' </small></h5>
            <p class="card-text">'.$this->serie->description.'</p>
            <a href="#" class="btn btn-primary">Details</a>
          </div>
        </div>
        ';
    }

    private function renderLong(): string{
        //TODO: implement this method
        //Faire le renderer long des episodes avant
    }

    public function render(int $selector): string
    {
        return ($selector === Renderer::COMPACT) ? $this->renderCompact() : $this->renderLong();
    }
}