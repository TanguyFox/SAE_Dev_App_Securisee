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
        <div class="card" style="width: 18rem; margin: 1rem;">
          <img class="card-img-top" src="'.$this->serie->image.'" alt="Serie\'s image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">'.$this->serie->titre.' <small> '.$this->serie->annee.' </small></h5>
            <p class="card-text">'.$this->serie->description.'</p>
            <p><small>'.$this->serie->date_ajout.'</small></p>
            <a href="?action=add-fav-series" class="align-self-end btn btn-lg btn-block btn-primary">Ajouter a la série</a>
            <a href="#" class="align-self-end btn btn-lg btn-block btn-primary">Details</a>
          </div>
        </div>
        ';
    }

    private function renderLong(): string{
        //TODO: implement this method
        //Utiliser le renderer des episodes
    }

    public function render(int $selector): string
    {
        return ($selector === Renderer::COMPACT) ? $this->renderCompact() : $this->renderLong();
    }
}