<?php

namespace netvod\action;

class AddNoteAction extends Action {

	public function execute(): string {
		$_GET['note'] = $_GET['note'] ?? "";
		$_GET['id'] = $_GET['id'] ?? "";
		$_SESSION['user']->addNote($_GET['id'], $_GET['note']);
        return '<script>document.location.href="index.php?action=serie&id=' . $_GET['id'] . '"</script>';
    }
}