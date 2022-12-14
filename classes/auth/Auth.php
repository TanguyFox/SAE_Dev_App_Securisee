<?php

namespace netvod\auth;
use netvod\db\ConnexionFactory;
use netvod\exceptions\AuthException;
use netvod\exceptions\UserException;
use netvod\user\User;

class Auth
{

    /**
     * @throws AuthException
     * @throws UserException
     */
    public static function authenticate(string $email, string $pwd): bool
    {
        $db = ConnexionFactory::makeConnection();

        $st = $db->prepare( "SELECT * FROM utilisateur WHERE email = ?");
        $st->execute([$email]);
        $u = $st->fetch(\PDO::FETCH_ASSOC);
	    if (!$u or !password_verify($pwd, $u['password'])){
            throw new AuthException("Identifiants ou mot de passe incorrect.");
	    }
        if (! isset($u['genre_pref']))
            $u['genre_pref'] = "Aucun";
        $user = new User($u['nom'],$u['prenom'],$email, $u['password'],$u['genre_pref']);
        $_SESSION['user']=serialize($user);
        return true;
    }

    /**
     * @throws AuthException
     */
    public static function loadprofile(string $email): void
    {
        //Cette fonction sera modifiée prochainement pour charger les profils
        $db = ConnexionFactory::makeConnection();

        $st = $db->prepare( "SELECT * FROM utilisateur WHERE email = ?");
        $st->execute([$email]);
        $u = $st->fetch(\PDO::FETCH_ASSOC);
        if (!$u) {
            throw new AuthException("Identifiants invalides.");
        }
    }

    /**
     * @throws AuthException
     */
    public static function register(string $email, string $password, string $nom="", string $prenom=""): void
    {
        if (strlen($password) < 10) {
            throw new AuthException("Mot de passe trop court");
        }
        $db = ConnexionFactory::makeConnection();
        $st = $db->prepare( "SELECT * FROM utilisateur WHERE email = :email");
        $st->execute(["email" => $email]);
        $row = $st->fetch();
        if ($row != null) {
            throw new AuthException("L'utilisateur existe déjà");
        }
        $st = $db->prepare("INSERT INTO utilisateur (email, password, nom, prenom) VALUES (?,?,?,?)");
        $st->execute([$email, password_hash($password, PASSWORD_DEFAULT), $nom, $prenom]);
    }

}