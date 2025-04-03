<?php

include_once '../../gui/ViewCreateBasket.php';
include_once '../../gui/Layout.php';
include_once '../../control/Presenter.php';
include_once '../../data/JsonAccess/JsonBasketAccess.php';

use gui\{ViewCreateBasket, Layout};
use control\Presenter;
use data\JsonAccess\JsonBasketAccess;

// 🔧 Initialisation réelle des composants
$layout = new Layout("../../gui/layoutLoggedManager.html");
$dataBaskets = new JsonBasketAccess(__DIR__ . '/../../data/Json/baskets.json');
$presenter = new Presenter($dataBaskets);
$view = new ViewCreateBasket($layout, "manager@example.com", $presenter);

// 🎯 Affichage de la vue
$view->display();
