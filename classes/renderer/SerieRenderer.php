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
          <img class="card-img-top" src="'.$this->serie->image.'" style="width: 10rem;" alt="Serie\'s image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">'.$this->serie->titre.' <small> '.$this->serie->annee.' </small></h5>
            <p class="card-text">'.$this->serie->description.'</p>
            <p><small>'.$this->serie->date_ajout.'</small></p>
            <a href="?action=add-fav-series&id='.$this->serie->id.'"  class="align-self-end btn btn-lg btn-block btn-primary">Ajouter a la série</a>
            <a href="?action=display-serie&id='.$this->serie->id.'" class="align-self-end btn btn-lg btn-block btn-primary">Details</a>
          </div>
        </div>
        ';
    }

    private function renderLong(): string{
        $html = '
        <div class="card">
          <img src="'.$this->serie->image.'" class="card-img" style="width: 10rem;" alt="Serie\'s image">
          <div class="card-img-overlay">
            <h5 class="card-title">'.$this->serie->titre.' <small>'.$this->serie->annee.'</small></h5>
            <p class="card-text">'.$this->serie->description.'</p>
            <p class="card-text"><small>'.$this->serie->date_ajout.'</small></p>
          </div>
        </div>
        <div class="card-group">
        ';
        $episodes = $this->serie->getEpisodes();
        foreach($episodes as $episode){
            $renderer = new EpisodeRenderer($episode);
            $html .= $renderer->render(Renderer::COMPACT);
        }
        $html .= '</div>';
        return $html;
    }

    public function render(int $selector): string
    {
        return ($selector === Renderer::COMPACT) ? $this->renderCompact() : $this->renderLong();
    }
}