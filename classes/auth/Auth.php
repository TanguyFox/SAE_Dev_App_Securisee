<?php

namespace netvod\auth;
class Auth
{

    public static function authenticate(string $email, string $pwd)
    {
        $db = \iutnc\deefy\db\ConnexionFactory::makeConnexion();

        $st = $db->prepare( "SELECT * FROM utilisateur WHERE email = ?");
        $st->execute([$email]);
        $u = $st->fetch(\PDO::FETCH_ASSOC);
        if (!$u) {
            throw new AuthException(" Cet utilisateur n'existe pas");
        }
        $hash = $u['passwd'];
        if (!password_verify($pwd, $hash)) {
            throw new AuthException("Mot de passe incorrecte");
        }
    }

    public static function loadprofile(string $email){
        $db = \iutnc\deefy\db\ConnexionFactory::makeConnexion();

        $st = $db->prepare( "SELECT * FROM utilisateur WHERE email = ?");
        $st->execute([$email]);
        $u = $st->fetch(\PDO::FETCH_ASSOC);
        if (!$u) {
            throw new AuthException(" Identifiants invalides");
        }
        $user = new \iutnc\deefy\user\User($email, $u['passwd'],$u['role']);
        $_SESSION['user']=serialize($user);
    }

}