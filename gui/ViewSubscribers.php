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
            $this->content = $presenter->getSubscribersHTML($basketId);
            $this->content .= $presenter->getOrdersForBasketHTML($basketId);
        }
    }
}
