<?php

namespace netvod\action;

use netvod\user\User;

class DeleteFavSeriesAction {

	public function execute(): string {
		if (!isset($_SESSION['user']))
			header('Location: ?action=signin&error=notConnected');
		$user = unserialize($_SESSION['user']);
		$user->supprimerListe($_GET['id'], $user, genre : USER::FAV );
		header('Location: ?action=user-home-page');
		return "";
	}

}