<?php

namespace netvod\action;

use Auth;
use Exception;

class Register extends Action
{

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            return <<<HTML
                <h2>Inscription</h2>
                <form action='?action=add-user' method='post'>
                    <table>
                        <tr>
                            <td>Email</td>
                            <td><input type='email' name='email' /></td>
                        </tr>
                        <tr>
                            <td>Mot de passe</td>
                            <td><input type='password' name='password'/></td>
                        </tr>
                    </table>
                    <a href='?action=signin'>Se connecter</a>
                    <input type='submit' value="S'inscrire"' />
                    <div>
                    <a href="?">Accueil</a>
                </div>
                </form>
HTML;
        else
            try {
                Auth::register($_POST['email'], $_POST['password']);
            } catch (Exception $e) {
                return <<<HTML
                        <h2 style="color: red">Erreur lors de l'inscription</h2>
                        <a href='?'>Accueil</a>
                        <a href="?action=register">Réessayer</a> 
HTML;
            }
        return <<<HTML
                    <h2>Utilisateur ajouté</h2>
                    <a href="?action=signin">Se connecter</a>
                    <a href='?'>Accueil</a>
HTML;
    }
}