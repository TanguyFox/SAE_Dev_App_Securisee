<?php

namespace netvod\action;

class DefaultAction extends Action
{

    public function execute(): string
    {
        $html = "";
        if (isset($_GET['error']))
            if ($_GET['error'] === 'wrongCredentials') {
                $html .= <<<HTML
                    <h2 style="color: red">Erreur lors de la connexion</h2>
                    <h3 style="color: red;">Identifiants incorrects</h3>
                    HTML;
            }
        return $html .= <<<HTML
<div class="form_content">
    <h2>Connexion</h2>
    <form action='?action=signin' method='post'>
        <table>
            <tr>
                <td>Email</td>
                <td><input type='email' name='email' /></td>
            </tr>
            <tr>
                <td>Mot de passe</td>
                <td><input type='password' name='passw' /></td>
            </tr>
        </table>
        <a href="?action=register" style="margin-right: 8.5em">S'inscrire</a>
        <input type='submit' value='Connexion' style='margin-top: 10px;' />
        <div>
    </div>
    </form>
</div>
HTML;
    }
}