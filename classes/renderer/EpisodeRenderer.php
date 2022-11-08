<?php

namespace netvod\renderer;

class EpisodeRenderer implements Renderer
{

    public function render(int $selector): string
    {
        return $this->rendered = ($selector == Renderer::COMPACT) ? $this->compact(): $this->long();
    }

    protected function long():String
    {
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
}