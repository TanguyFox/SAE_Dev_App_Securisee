<?php

namespace netvod\renderer;

use netvod\contenu\Catalogue;

class CatalogueRenderer implements Renderer
{

    private array $series;
    public function __construct(){
        $this->series = Catalogue::getSeries();
    }

    public function render(int $selector): string
    {
        $html= '<div class="card-group">';
        foreach($this->series as $serie){
            $html .= (new SerieRenderer($serie))->render(Renderer::COMPACT);
        }
        $html .= '</div>';
        return $html;
    }
}