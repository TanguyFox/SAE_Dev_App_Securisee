<?php

namespace netvod\action;

use Exception;
use netvod\auth\Auth;
use netvod\exceptions\AuthException;

class RegisterAction extends Action
{

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            return <<<HTML
            <div class="form_content">
                <h2>Inscription</h2>
                <form action='?action=register' method='post'>
                    <table>
                        <tr>
                            <td>Email</td>
                            <td><input type='email' name='email' /></td>
                        </tr>
                        <tr>
                            <td>Mot de passe</td>
                            <td><input type='password' name='password'/></td>
                        </tr>
                        <tr>
                            <td>Veuillez entrer de nouveau le mot de passe</td>
                            <td><input type='password' name='password2'/></td>
                        </tr>
                    </table>
                    
                    <a href="?" >Se connecter</a>
                    <input type='submit' value="S'inscrire" style="margin: 1em 0 0 21em"/>
                </div>
                </form>
            </div>
HTML;
        else

        try {
            if ($_POST['password'] !== $_POST['password2'])
                throw new AuthException("Les mots de passe ne correspondent pas");
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            Auth::register($email, $password);
        } catch (Exception $e) {
            return <<<HTML
                        <h2 style="color: red">Erreur lors de l'inscription</h2>
                        <h3 style="color: red;">{$e->getMessage()}</h3>
                        <a href='?'>Accueil</a>
                        <a href="?action=register">Réessayer</a> 
HTML;
        }
        header("Location: ?get=register-success");
        return "";
    }
}