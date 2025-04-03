<?php

namespace gui;

use gui\View;
use control\Presenter;

class ViewBasketList extends View
{
    public function __construct($layout, $login, Presenter $presenter) {
        parent::__construct($layout, $login);
        $this->title = 'Gestion des paniers';
        $this->content = $presenter->getAllBasketsHTMLForCustomer();
    }
}
