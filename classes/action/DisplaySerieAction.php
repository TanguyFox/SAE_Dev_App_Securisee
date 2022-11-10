<?php

namespace netvod\action;

use netvod\contenu\serie\Serie;
use netvod\renderer\Renderer;
use netvod\renderer\SerieRenderer;

class DisplaySerieAction extends Action {

    public function execute(): string {
		$html = '';
        if ($this->http_method == 'GET') {
	        $html .= (new SerieRenderer(Serie::getSerieFromId($_GET['id'])))->render(Renderer::LONG);
	        $html .= '
			<!-- Affichage des étoiles pour noter l épisode -->
            <div id="glob">
                <a href="?=add-note&note=1&id=' . $_GET['id'] . '"><img id="tde_1" src="images/star.png" class="tde" alt="star 1"/></a>
                <a href="?=add-note&note=2&id=' . $_GET['id'] . '"><img id="tde_2" src="images/star.png" class="tde" alt="star 2"/></a>
                <a href="?=add-note&note=3&id=' . $_GET['id'] . '"><img id="tde_3" src="images/star.png" class="tde" alt="star 3"/></a>
                <a href="?=add-note&note=4&id=' . $_GET['id'] . '"><img id="tde_4" src="images/star.png" class="tde" alt="star 4"/></a>
                <a href="?=add-note&note=5&id=' . $_GET['id'] . '"><img id="tde_5" src="images/star.png" class="tde" alt="star 5"/> </a>   
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
			
			<!-- Affichage des commentaires -->
			<div id="commentaires">
				<form action="?action=add-com" method="post">
					<input type="hidden" name="id" value="' . $_GET['id'] . '">
					<textarea name="com" id="com" cols="30" rows="1"></textarea>
					<input type="submit" value="Envoyer">
				</form>
			</div>
			';
        }
		return $html;
    }
}