<?php

namespace netvod\auth;
use netvod\db\ConnexionFactory;
use netvod\exceptions\AuthException;
use netvod\user\User;

class Auth
{

    /**
     * @throws AuthException
     */
    public static function authenticate(string $email, string $pwd): void
    {
        $db = ConnexionFactory::makeConnection();

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

    /**
     * @throws AuthException
     */
    public static function loadprofile(string $email): void
    {
        $db = ConnexionFactory::makeConnection();

        $st = $db->prepare( "SELECT * FROM utilisateur WHERE email = ?");
        $st->execute([$email]);
        $u = $st->fetch(\PDO::FETCH_ASSOC);
        if (!$u) {
            throw new AuthException(" Identifiants invalides");
        }
        $user = new User($u['nom'],$u['prenom'],$email, $u['passwd'],$u['role']);
        $_SESSION['user']=serialize($user);
    }

}