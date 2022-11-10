<?php

namespace netvod\dispatch;

use Exception;
use netvod\action\AccessAccountAction;
use netvod\action\AccueilCatalogueAction;
use netvod\action\AddFavSeriesAction;
use netvod\action\AddNoteAction;
use netvod\action\CreateProfilAction;
use netvod\action\DefaultAction;
use netvod\action\DisplayEpisodeDetailsAction;
use netvod\action\DisplaySerieAction;
use netvod\action\LogoutAction;
use netvod\action\SignInAction;
use netvod\action\RegisterAction;
use netvod\action\UserHomePageAction;

class Dispatcher
{
    private string $action;

    public function __construct(string $action){
        //pages autorisees sans login utilisateur (et du coup sans profil)
        $aP1 = array(null,"signin","register"); //acceuil et pages de connexion

        //pages autorisees avec login utilisateur (et sans profil), doit pouvoir acceder a la page de creation et de sélection de profil
        $aP2 = array_merge($aP1, array("logout","create-profil"));
        if(!isset($_SESSION['user']) and !in_array($action, $aP1)) {
            $this->action = "signin";
        }else{
            $this->action = $action; //action validée

        }
    }

    public function run(): void
    {
        $action = match ($this->action) {
            'signin' => new SigninAction(),
            'register' => new RegisterAction(),
            'logout' => new LogoutAction(),
            'create-profil' => new CreateProfilAction(),
            'access-account' => new AccessAccountAction(),
            'display-episode-details' => new DisplayEpisodeDetailsAction(),
            'display-serie' => new DisplaySerieAction(),
            'accueil-catalogue' => new AccueilCatalogueAction(),
            'add-fav-series' => new AddFavSeriesAction(),
	        'add-note' => new AddNoteAction(),
            'user-home-page' => new UserHomePageAction(),
            default => new DefaultAction(),
        };
        try {
            $this->renderPage($action->execute());
        } catch (Exception $e) {
            $this->renderPage($e->getMessage());
        }
    }

    private function renderPage(string $html): void{
        $content='
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>NetVOD - '.$_GET['action'].'</title>
                <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
            </head>
            <body>
                <style>
                    body{
                        text-align: center;
                    }  
                </style>
            ';
        $content .= $html;
        if ($this->action != "user-home-page") {
            $content .= '<a href="?action=accueil-catalogue" class="btn btn-primary centerFooter">Home</a>';
        }
        if(isset($_SESSION['user'])) {
            $content .= '<a href="?action=logout" class="btn btn-danger centerFooter">Logout</a>';
        }
        $content .= '</body></html>';
        print($content);
    }

}