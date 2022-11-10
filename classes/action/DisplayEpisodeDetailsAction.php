<?php

namespace netvod\action;

use netvod\contenu\serie\episode\Episode;
use netvod\renderer\EpisodeRenderer;
use netvod\renderer\Renderer;

class DisplayEpisodeDetailsAction extends Action {

    public function execute(): string {
        $html = '';
        if ($this->http_method == 'GET') {
            $html .= (new EpisodeRenderer(Episode::getEpisodeFromId($_GET['id'])))->render(Renderer::LONG);
            // ajouter le code javascript pour récupérer l'avancement de la video quand l'utilisateur quite la page
            $html .= '
            <script>
                window.onbeforeunload = function(){
                    var video = document.querySelector("video");
                    var time = video.currentTime;
                    var id = '.$_GET['id'].';
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "?action=UpdateEpisodeProgressAction", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("id="+id+"&time="+time);
                };
            </script>
            ';
        }
        return $html;
    }
}