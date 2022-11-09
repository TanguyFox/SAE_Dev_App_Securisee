<?php

namespace netvod\action;

class AddNoteAction extends Action {

	public function execute(): string {
		// recuperer parametre bouton (_GET) = note + get id
		$_GET['note'] = $_GET['note'] ?? "";
		$_GET['id'] = $_GET['id'] ?? "";
		// requete sql pour ajouter la note
		$_SESSION['profil']->addNote($_GET['id'], $_GET['note']);
	}
}