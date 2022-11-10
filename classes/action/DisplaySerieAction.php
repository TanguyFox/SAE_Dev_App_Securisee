<?php

namespace netvod\action;

use netvod\contenu\serie\Serie;
use netvod\renderer\Renderer;
use netvod\renderer\SerieRenderer;

class DisplaySerieAction extends Action {

    public function execute(): string {
		$html = '';
        if ($this->http_method == 'GET') {
            $serie = Serie::getSerieFromId($_GET['id']);
	        $html .= (new SerieRenderer($serie))->render(Renderer::LONG);
	        $html .= '
			<!-- Affichage des étoiles pour noter l épisode -->
            <div id="glob">
            	    <form action="?action=display-serie&id=' . $_GET['id'] . '" class="form_c" method="post">
            	        <input type="hidden" name="note" value="1"/>
	                    <button type="submit" class="button_c">
	                        <img id="tde_1" src="images/star.png" class="tde" alt="star 1"/>
	                    </button>
	                </form>
	                <form action="?action=display-serie&id=' . $_GET['id'] . '" class="form_c" method="post">
                        <input type="hidden" name="note" value="2"/>
                        <button type="submit" class="button_c"><img id="tde_2" src="images/star.png" class="tde" alt="star 2"/></button>
                    </form>
                    <form action="?action=display-serie&id=' . $_GET['id'] . '" class="form_c" method="post">
                        <input type="hidden" name="note" value="3"/>
                        <button type="submit" class="button_c"><img id="tde_3" src="images/star.png" class="tde" alt="star 3"/></button>
                    </form>
                    <form action="?action=display-serie&id=' . $_GET['id'] . '" class="form_c" method="post">
                        <input type="hidden" name="note" value="4"/>
                        <button type="submit" class="button_c"><img id="tde_4" src="images/star.png" class="tde" alt="star 4"/></button>
                    </form>
                    <form action="?action=display-serie&id=' . $_GET['id'] . '" class="form_c" method="post">
                        <input type="hidden" name="note" value="5"/>
                        <button type="submit" class="button_c"><img id="tde_5" src="images/star.png" class="tde" alt="star 5"/></button>
                    </form>
            </div>
            <script>
                function setNote(note) {
                    for (var i = 1; i <= 5; i++) {
                        if (i <= note) {
                            document.getElementById("tde_" + i).style.backgroundColor = "#E0E001";
                        } else {
                            document.getElementById("tde_" + i).style.backgroundColor = "";
                        }
                    }
                }
                setNote('.$serie->getNote().');
                for (var i = 1; i <= 5; i++) {
                    document.getElementById("tde_" + i).addEventListener("mouseover", function () {
                        setNote(this.id.split("_")[1]);
                    });
                    document.getElementById("tde_" + i).addEventListener("mouseout", function () {
                        setNote('.$serie->getNote().');
                    });
                }
			</script>
			
			<!-- Affichage des commentaires -->
			<div id="commentaires">
				<form action="?action=display-serie&id=' . $_GET['id'] . '" method="post">
					<input type="hidden" name="id" value="' . $_GET['id'] . '">
					<textarea name="com" id="com" cols="30" rows="1">'.$serie->getCom().'</textarea>
					<input type="submit" value="Envoyer">
				</form>
			</div>
			
			<!-- Affichage de la moyenne des notes -->
			<div id="moyenne">
				<p>Moyenne des notes : '.$serie->getNoteMoyenne().'</p>
			</div>
			';
        } else {
	        $u = unserialize($_SESSION['user']);
			if (isset($_POST['note'])) {
				$u->addNote($_GET['id'], $_POST['note']);
			}
			if (isset($_POST['com'])) {
				$com = filter_var($_POST['com'], FILTER_SANITIZE_STRING);
				$u->addCom($_POST['id'], $com);
			}
            header("Location: ?action=display-serie&id=" . $_GET['id']);
		}
		return $html;
    }
}