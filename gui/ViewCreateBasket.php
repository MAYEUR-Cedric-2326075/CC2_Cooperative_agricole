<?php

namespace gui;
include_once __DIR__ . '/View.php';

use gui\View;
use control\Presenter;

class ViewCreateBasket extends View
{
    public function __construct($layout, $login, Presenter $presenter)
    {
        parent::__construct($layout, $login);
        $this->title = "➕ Création d'un nouveau panier";
        $this->content = $presenter->getCreateBasketForm();
    }
}
