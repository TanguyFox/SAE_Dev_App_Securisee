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
            	<form action="?action=display-serie&id=' . $_GET['id'] . '" method="post">
	                <a href="?=display-serie&note=1&id=' . $_GET['id'] . '"><img id="tde_1" src="images/star.png" class="tde" alt="star 1"/></a>
	                <a href="?=display-serie&note=2&id=' . $_GET['id'] . '"><img id="tde_2" src="images/star.png" class="tde" alt="star 2"/></a>
	                <a href="?=display-serie&note=3&id=' . $_GET['id'] . '"><img id="tde_3" src="images/star.png" class="tde" alt="star 3"/></a>
	                <a href="?=display-serie&note=4&id=' . $_GET['id'] . '"><img id="tde_4" src="images/star.png" class="tde" alt="star 4"/></a>
	                <a href="?=display-serie&note=5&id=' . $_GET['id'] . '"><img id="tde_5" src="images/star.png" class="tde" alt="star 5"/> </a>
                </form>
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
				<form action="?action=display-serie&id=' . $_GET['id'] . '" method="post">
					<input type="hidden" name="id" value="' . $_GET['id'] . '">
					<textarea name="com" id="com" cols="30" rows="1"></textarea>
					<input type="submit" value="Envoyer">
				</form>
			</div>
			';
        } else {
	        $u = unserialize($_SESSION['user']);
			if (isset($_POST['note'])) {
				$u->addNote($_POST['id'], $_GET['note']);
			}
			if (isset($_POST['com'])) {
				$com = filter_var($_POST['com'], FILTER_SANITIZE_STRING);
				$u->addCom($_POST['id'], $com);
			}
		}
		return $html;
    }
}