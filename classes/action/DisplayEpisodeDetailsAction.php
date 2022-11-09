<?php

namespace netvod\action;

use netvod\contenu\serie\episode\Episode;
use netvod\renderer\EpisodeRenderer;
use netvod\renderer\Renderer;

class DisplayEpisodeDetailsAction extends Action
{

    public function execute(): string
    {
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
            <div id="glob">
                <a href="?=add-note&note=1&id='.$_GET['id'].'"><img id="tde_1" src="images/star.png" class="tde" alt="star 1"/></a>
                <a href="?=add-note&note=2&id='.$_GET['id'].'"><img id="tde_2" src="images/star.png" class="tde" alt="star 2"/></a>
                <a href="?=add-note&note=3&id='.$_GET['id'].'"><img id="tde_3" src="images/star.png" class="tde" alt="star 3"/></a>
                <a href="?=add-note&note=4&id='.$_GET['id'].'"><img id="tde_4" src="images/star.png" class="tde" alt="star 4"/></a>
                <a href="?=add-note&note=5&id='.$_GET['id'].'"><img id="tde_5" src="images/star.png" class="tde" alt="star 5"/> </a>   
            </div>
            <script>
                $(".tde").mouseover(function() {
					let nbr = $(this).prop("id").substring(4);
					$(this).css("backgroundColor", "#E0E001");
					$(".tde").slice(0, nbr).css("backgroundColor", "#E0E001");
				});
				$("#glob").mouseout(function() {
					$(".tde").css("backgroundColor", "" );
				})
			</script>
            ';
        }
        return $html;
    }
}