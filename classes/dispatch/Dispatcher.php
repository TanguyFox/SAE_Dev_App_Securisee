<?php

namespace netvod\dispatch;

use Exception;
use netvod\action\AccueilCatalogueAction;
use netvod\action\AddFavSeriesAction;
use netvod\action\DefaultAction;
use netvod\action\DeleteFavSeriesAction;
use netvod\action\DisplayEpisodeDetailsAction;
use netvod\action\DisplaySerieAction;
use netvod\action\GestionUtilisateurAction;
use netvod\action\LogoutAction;
use netvod\action\SignInAction;
use netvod\action\RegisterAction;
use netvod\action\UpdateEpisodeProgressAction;
use netvod\action\UserHomePageAction;

class Dispatcher {
    private string $action;

    public function __construct(string $action) {
        //pages autorisees sans login utilisateur
        $aP1 = array(null,"signin","register"); //accueil et pages de connexion

        //pages autorisees quand utilisateur deconnecte
        if (!isset($_SESSION['user'])) {
            if (!in_array($action, $aP1)) {
                header('Location: ?action=signin&error=notConnected');
            }
        } else { //pages autorisees quand utilisateur connecte
            if (in_array($action, $aP1)) {
                header('Location: ?action=accueil-catalogue');
            }
        }
        $this->action = $action;
    }

    public function run(): void {
        $action = match ($this->action) {
            'signin' => new SigninAction(),
            'register' => new RegisterAction(),
            'logout' => new LogoutAction(),
            'display-episode-details' => new DisplayEpisodeDetailsAction(),
            'display-serie' => new DisplaySerieAction(),
            'accueil-catalogue' => new AccueilCatalogueAction(),
            'add-fav-series' => new AddFavSeriesAction(),
            'user-home-page' => new UserHomePageAction(),
            'gestion-utilisateur' => new GestionUtilisateurAction(),
            'update-episode-progress' => new UpdateEpisodeProgressAction(),
	        'delete-fav-series' => new DeleteFavSeriesAction(),
            default => new DefaultAction(),
        };
        try {
            $this->renderPage($action->execute());
        } catch (Exception $e) {
            $this->renderPage($e->getMessage());
        }
    }

    private function renderPage(string $html): void{
        $content= '
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>NetVOD - '.$_GET['action'].'</title>
                <link rel="stylesheet" href="css/style.css">
                <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
            </head>
            <body>
                <header>
                    <div id="logo">
                        <a href="?action=accueil-catalogue"><img src="images/logo.png" alt="logo" id="logo_image"></a>
                    </div>
                    <div id="menu">';

        if (isset($_SESSION['user'])) {
$content .= <<<HTML
                            <p><a href="?action=user-home-page">Accueil</a></p>
                            <p><a href="?action=accueil-catalogue">Catalogue</a></p>
                            <p><a href="?action=gestion-utilisateur">Mon compte</a></p>
                            <p><a href="?action=logout">Déconnexion</a></p>
HTML;
} else {
$content .= <<<HTML
                            <p><a href="?action=signin">Connexion</a></p>
                            <p><a href="?action=register">Inscription</a></p>
HTML;
}
$content .= <<<HTML
                    </div>
                </header> 
HTML;

        $content .= $html;
        $content .= '</body></html>';
        print($content);
    }

}