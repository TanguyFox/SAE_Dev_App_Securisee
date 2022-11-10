<?php

namespace netvod\action;

class AddComAction extends Action {

	public function execute(): string {
		$_GET['com'] = $_GET['com'] ?? "";
		$_GET['id'] = $_GET['id'] ?? "";
		$_SESSION['user']->addCom($_GET['id'], $_GET['com']);
	}
}