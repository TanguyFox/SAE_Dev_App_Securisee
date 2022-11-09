<?php

namespace netvod\action;

class AddNoteAction extends Action {

	public function execute(): string {
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			return "
				<h2>Ajouter une note</h2>
					<form action='?action=add-note' method='post'>
						<div style=\"float:right;margin-top : 10px;width:100px;\">
							<div id=\"value\">
								<div id=\"glob\" >
			                        <img id=\"tde_1\" src=\"/images/star.png\" class=\"tde\" alt=''/>
			                        <img id=\"tde_2\" src=\"/images/star.png\" class=\"tde\" alt=''/>
			                        <img id=\"tde_3\" src=\"/images/star.png\" class=\"tde\" alt=''/>
			                        <img id=\"tde_4\" src=\"/images/star.png\" class=\"tde\" alt=''/>
			                        <img id=\"tde_5\" src=\"/images/star.png\" class=\"tde\" alt=''/>    
			                    </div>
			                    <script>
			                    	$(\".tde\").mouseover(function() {
										let nbr = $(this).prop('id').substring(4);
										$(this).css(\"backgroundColor\", \"#E0E001\");
										$(\".tde\").slice(0, nbr).css(\"backgroundColor\", \"#E0E001\");
										$(\".tde\").slice(nbr).css(\"backgroundColor\", \"#A1A1A1\");
									});
										$(\"#glob\").mouseout(function() {
											$(\".tde\").css('backgroundColor', \"\" );
										})
								</script>
			                </div>
						</div>
					</form>";
		}
	}
}