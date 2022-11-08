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
        return ($selector == Renderer::COMPACT) ? $this->rendererCompact(): $this->rendererLong();
    }

    protected function rendererCompact(): string
    {
        return '
        <div class="card" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title">'.$this->episode->id.' - '.$this->episode->titre.'</h5>
            <p class="card-text">'.$this->episode->resume.'</p>
            <p class="card-text"><small class="text-muted">Duree: '.$this->episode->duree.'</small></p>
            <a href="?action=DisplayEpisodeDetailsAction&id='.$this->episode->id.'" class="btn btn-primary">Details</a>
          </div>
        </div>
        ';
    }

    protected function rendererLong(): string{
        return '
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">'.$this->episode->titre.' <small>Duree: '.$this->episode->duree.' </small></h5>
            <p class="card-text">'.$this->episode->resume.'</p>
            <video style="width: 50rem;" controls>
              <source src="video/'.$this->episode->file.'" type="video/'.pathinfo($this->episode->file)['extension'].'">
            </video>
          </div>
        </div>
        ';
    }

}