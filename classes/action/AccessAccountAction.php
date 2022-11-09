<?php

namespace netvod\action;

use netvod\db\ConnexionFactory;

class AccessAccountAction extends Action
{


    public function execute(): string
    {
       $html = "Vos listes : ";
       $html = "Favoris : ";
       foreach ($_SESSION['account']->fav as $favorite){
           $db = ConnexionFactory::makeConnection();
           $stmt = $db->prepare("SELECT `id` FROM Serie where titre = ?");
           $stmt->execute([$favorite->titre]);
           $res = $stmt->fetch(\PDO::FETCH_ASSOC);
           $id_serie = $res['id'];

           $html .= <<<END
                <ul>
                    <li><a href="?action=display-serie&id={$id_serie}"$favorite->titre</li>
                </ul>
END;
       }

       return $html;
    }
}