<?php

namespace gui;

use gui\View;
use control\Presenter;

class ViewSubscribers extends View
{
    public function __construct($layout, $login, $basketId,Presenter $presenter)
    {
        parent::__construct($layout, $login);
        $this->title = "👥 Gestion des abonnés";

        if (empty($login)) {
            $this->content = "<p>❌ Aucune session utilisateur détectée.</p>";
        } else {
            $basket = $presenter->getBasketsForManagerHTML($basketId); // nouvelle méthode à ajouter dans Presenter
            if ($basket) {
                $this->content = $presenter->getSubscribersHTML($basketId);
                //echo "CAR";
            } else {
                $this->content = "<p>⚠️ Panier introuvable.</p>";
            }
        }
    }
}
