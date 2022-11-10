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
        $html= '<div class="catalogue">';
        foreach($this->series as $serie){
            $html .= (new SerieRenderer($serie))->render(Renderer::COMPACT);
        }
        $html .= '</div>';
        return $html;
    }

    public function renderSearch(array $searched) : string{
        $html= '<div class="catalogue">';
        foreach($searched as $serie){
            $html .= (new SerieRenderer($serie))->render(Renderer::COMPACT);
        }
        $html .= '</div>';
        return $html;
    }
}