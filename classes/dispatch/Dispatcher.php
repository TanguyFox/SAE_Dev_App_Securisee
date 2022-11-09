<?php

namespace netvod\dispatch;

use Exception;
use netvod\action\AccueilCatalogueAction;
use netvod\action\AddNoteAction;
use netvod\action\DefaultAction;
use netvod\action\DisplayEpisodeDetailsAction;
use netvod\action\DisplaySerieAction;
use netvod\action\LogoutAction;
use netvod\action\SignInAction;
use netvod\action\RegisterAction;

class Dispatcher
{
    private string $action;

    public function __construct(string $action){
        if(isset($_SESSION['user']) or in_array($action, array(null,"signin","register"))) {
            $this->action = $action;
        }else{
            $this->action = "signin";
        }
    }

    public function run(): void
    {
        $action = match ($this->action) {
            'signin' => new SigninAction(),
            'register' => new RegisterAction(),
            'logout' => new LogoutAction(),
            'AccueilCatalogueAction' => new AccueilCatalogueAction(),
            'DisplayEpisodeDetailsAction' => new DisplayEpisodeDetailsAction(),
            'DisplaySerieAction' => new DisplaySerieAction(),
	        'AddNoteAction' => new AddNoteAction(),
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
                <link rel="stylesheet" href="css/style.css">
                <link src="jquery/jquery-3.6.0.min.js">
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
        if(isset($_SESSION['user'])) {
            $content .= '<a href="index.php?action=logout" class="btn btn-danger centerFooter">Logout</a>';
        }
        $content .= '</body></html>';
        print($content);
    }

}