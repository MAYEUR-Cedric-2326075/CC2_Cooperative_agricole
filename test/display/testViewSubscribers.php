<?php

include_once '../../gui/ViewSubscribers.php';
include_once '../../gui/Layout.php';
include_once '../../control/Presenter.php';
include_once '../../data/JsonAccess/JsonBasketAccess.php';
include_once '../../data/JsonAccess/JsonProductAccess.php';

use gui\{ViewSubscribers, Layout};
use control\Presenter;
use data\JsonAccess\{JsonBasketAccess, JsonProductAccess};

// Init
$layout = new Layout("../../gui/layoutLoggedManager.html");
$dataBaskets = new JsonBasketAccess(__DIR__ . '/../../data/Json/baskets.json');
$dataProducts = new JsonProductAccess(__DIR__ . '/../../data/Json/products.json');
$presenter = new Presenter($dataBaskets, $dataProducts);

// Paramètres de test
$basketId = "basket-001";
$managerEmail = "manager@example.com";

// 💡 Récupération de la liste des abonnés
$basket = $dataBaskets->getBasketById($basketId);
$subscribers = $basket ? $basket->getSubscribers() : [];

echo $presenter->getSubscribersHTML($subscribers, $basketId);
