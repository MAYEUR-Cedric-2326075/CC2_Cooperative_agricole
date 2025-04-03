<?php

require_once '../../gui/ViewOrders.php';
require_once '../../gui/Layout.php';
require_once '../../control/Presenter.php';
require_once '../../service/OrdersChecking.php';
require_once '../../data/JsonAccess/JsonOrderAccess.php';
require_once '../../data/JsonAccess/JsonBasketAccess.php';
require_once '../../data/JsonAccess/JsonProductAccess.php';

use gui\ViewOrders;
use gui\Layout;
use control\Presenter;
use service\OrdersChecking;
use data\JsonAccess\JsonOrderAccess;
use data\JsonAccess\JsonBasketAccess;
use data\JsonAccess\JsonProductAccess;

// Fichiers de test
$orderAccess = new JsonOrderAccess(__DIR__ . '/../../data/Json/orders.json');
$basketAccess = new JsonBasketAccess(__DIR__ . '/../../data/Json/baskets.json');
$productAccess = new JsonProductAccess(__DIR__ . '/../../data/Json/products.json');
$dataBaskets = new JsonBasketAccess(__DIR__ . '/../../data/Json/baskets.json');
$dataOrders = new JsonOrderAccess(__DIR__ . '/../../data/Json/orders.json');
$dataProducts = new JsonProductAccess(__DIR__ . '/../../data/Json/products.json');

$ordersChecking = new OrdersChecking($dataOrders,$dataBaskets);
$presenter = new Presenter($dataBaskets, $dataProducts,$ordersChecking);

$ordersChecking = new OrdersChecking($orderAccess, $basketAccess);

$layout = new Layout("../../gui/layoutLoggedCustomer.html");

// Test avec un client
$view = new ViewOrders($layout, "client@example.com", "customer", $presenter, $ordersChecking);
$view->display();
