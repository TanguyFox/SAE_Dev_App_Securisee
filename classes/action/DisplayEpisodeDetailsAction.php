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
            $html .= '
            <script>
                //get the video by id
                var video = document.getElementById("video");
                
                //get the video advancement from index?action=update-episode-progress with a get request
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "index.php?action=update-episode-progress&id=' . $_GET['id'] . '", false);
                xhr.send();
                  //take response from xhr and extract from the div by id="avancement" and convert it to a number then set the video advancement to it
                var avancement = parseFloat(xhr.responseText.split("<div id=\"avancement\">")[1].split("</div>")[0]);
                video.currentTime = avancement;

                function update(){
                    //send the post request
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "index.php?action=update-episode-progress",false);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send("id=' .$_GET['id'].'&avancement="+video.currentTime);
                }
                setInterval(update, 5000);
            </script>
            ';
        }
        return $html;
    }
}