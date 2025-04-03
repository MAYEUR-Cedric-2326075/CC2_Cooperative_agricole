<?php

namespace gui;

use gui\View;
use control\Presenter;


class ViewSubscription extends View
{
    public function __construct($layout, $login, Presenter $presenter)
    {
        parent::__construct($layout, $login);
        $this->title = "🔔 Mes abonnements";
        $this->content = $presenter->getSubscriptionsHTML($login);
    }
}
