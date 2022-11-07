<?php

namespace netvod\dispatch;

use Exception;
use netvod\action\DefaultAction;
use netvod\action\LogoutAction;

class Dispatcher
{
    private string $action;

    public function __construct(string $action){
        if(isset($_SESSION['user'])) {
            $this->action = $action;
        }else{
            $this->action = 'signin';
        }
    }

    public function run(): void
    {
        switch ($this->action) {
            case 'signin':
                $action = new SigninAction();
                break;
            case 'register':
                $action = new RegisterAction();
                break;
            case 'logout':
                $action = new LogoutAction();
                break;
            default:
                $action = new DefaultAction();
                break;
        }
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
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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