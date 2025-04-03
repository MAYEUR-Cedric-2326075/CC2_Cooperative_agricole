<?php

// --- domain ---
include_once 'domain/User.php';
include_once 'domain/Basket.php';

// --- service ---
include_once 'service/AuthentificationManagement.php';
include_once 'service/SubscriberRegistration.php';
include_once 'service/BasketService.php';
include_once 'service/UserCreation.php';
include_once 'service/interfaces/UserAccessInterface.php';
include_once 'service/interfaces/BasketAccessInterface.php';
include_once 'service/OrderPlacing.php';//
include_once 'service/OrdersChecking.php';

// --- gui ---
include_once 'gui/Layout.php';
include_once 'gui/ViewLogin.php';
include_once 'gui/ViewError.php';
include_once 'gui/ViewManageBaskets.php';
include_once 'gui/ViewCreateBasket.php';
include_once 'gui/ViewSubscribers.php';
include_once 'gui/ViewSubscription.php';//
include_once 'gui/ViewBasketList.php';
include_once 'gui/ViewOrders.php';


// --- data / JsonAccess ---
include_once 'data/JsonAccess/JsonUserAccess.php';
include_once 'data/JsonAccess/JsonBasketAccess.php';
include_once 'data/JsonAccess/JsonOrderAccess.php';
include_once 'data/JsonAccess/JsonProductAccess.php';

// --- control ---
include_once 'control/Controllers.php';
include_once 'control/Presenter.php';

use control\{Controllers, Presenter};
use data\JsonAccess\{JsonBasketAccess, JsonOrderAccess, JsonUserAccess, JsonProductAccess};
use service\{AuthentificationManagement, UserCreation, BasketService, SubscriberRegistration,OrderPlacing,OrdersChecking};
use gui\{Layout, ViewLogin, ViewError, ViewManageBaskets, ViewCreateBasket, ViewSubscribers, ViewSubscription,ViewBasketList,ViewOrders};

// ...



session_start();

// Initialisation des services
$dataUsers = new JsonUserAccess(__DIR__ . '/data/Json/users.json');
$dataOrders = new JsonUserAccess(__DIR__ . '/data/Json/orders.json');
$dataBaskets = new JsonBasketAccess(__DIR__ . '/data/Json/baskets.json');
$dataOrders = new JsonOrderAccess(__DIR__ . '/data/Json/orders.json');
$dataProducts = new JsonProductAccess(__DIR__ . '/data/Json/products.json');

$authService = new AuthentificationManagement($dataUsers);//
$controller = new Controllers();
$userCreation = new UserCreation();
$ordersChecking = new OrdersChecking($dataOrders,$dataBaskets);
$presenter = new Presenter($dataBaskets, $dataProducts,$ordersChecking);
$basketService = new BasketService($dataBaskets);
$orderPlacing = new OrderPlacing ($dataOrders,$dataBaskets);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Page de connexion ou déconnexion
if ($uri === '/' || $uri === '/index.php' || $uri === '/index.php/logout') {
    session_destroy();
    $layout = new Layout("gui/layoutUnLogged.html");
    (new ViewLogin($layout))->display();
    //var_dump($ordersChecking->getOrdersForCustomer("alice@example.com"));
    exit();
}

// Authentification
if ($uri === '/index.php/login') {
    $error = $controller->authenticateAction($userCreation, $authService, $_POST['email'], $_POST['password']);
    if ($error !== null) {
        $layout = new Layout("gui/layoutUnLogged.html");
        (new ViewError($layout, $error, '/index.php'))->display();
    } else {
        header("Location: /index.php/baskets");
    }
    exit();
}

// Création d'un panier
elseif ($uri === '/index.php/createBasket') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 👉 Traitement du formulaire
        $items = [];
        foreach ($_POST['quantities'] as $productId => $quantity) {
            $quantity = (int)$quantity;
            if ($quantity > 0) {
                $items[] = ['productId' => $productId, 'quantity' => $quantity];
            }
        }

        $controller->createBasketAction($basketService, [
            'id' => $_POST['id'],
            'userId' => $_SESSION['user']['email'],
            'status' => $_POST['status'],
            'createdAt' => date('Y-m-d H:i'),
            'items' => $items
        ]);

        header("Location: /index.php/baskets");
        exit();
    } else {
        // 👉 Affichage du formulaire
        $layout = new Layout("gui/layoutLoggedManager.html");
        $view = new ViewCreateBasket($layout, $_SESSION['user']['email'], $presenter);
        $view->display();
        exit();
    }
}

// Suppression d'un panier
if ($uri === '/index.php/deleteBasket' && isset($_GET['id'])) {
    $controller->deleteBasketAction($basketService, $_GET['id']);
    header("Location: /index.php/baskets");
    exit();
}

// Abonnement
if ($uri === '/index.php/subscribe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Appel à SubscriberRegistration si implémenté
    header("Location: /index.php/subscribers");
    exit();
}

// Désabonnement
if ($uri === '/index.php/unsubscribe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Appel à SubscriberRegistration si implémenté
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Voir abonnés (manager uniquement)
if ($uri === '/index.php/subscribers' && isset($_GET['basketId']) && $_SESSION['type'] === 'manager') {
    $layout = new Layout("gui/layoutLoggedManager.html");
    (new ViewSubscribers($layout, $_SESSION['user']['email'], $_GET['basketId'], $presenter))->display();
    exit();
}

// Voir abonnements (customer uniquement)
if ($uri === '/index.php/subscriptions' && $_SESSION['type'] === 'customer') {
    $layout = new Layout("gui/layoutLoggedCustomer.html");
    (new ViewSubscription($layout, $_SESSION['user']['email'], $presenter))->display();
    exit();
}

// Page principale des paniers (redirection dynamique selon le type)
if ($uri === '/index.php/baskets') {
    if ($_SESSION['type'] === 'manager') {
        echo "DOG";
        $layout = new Layout("gui/layoutLoggedManager.html");
        (new ViewManageBaskets($layout, $_SESSION['user']['email'], $presenter))->display();
    } elseif ($_SESSION['type'] === 'customer') {
        $layout = new Layout("gui/layoutLoggedCustomer.html");
        $view =new ViewBasketList($layout, $_SESSION['user']['email'], $presenter);
        $view->display();
    }
    else {
        $layout = new Layout("gui/layoutUnLogged.html");
        (new ViewError($layout, "❌ Type d'utilisateur inconnu", "/index.php"))->display();
    }

    exit();
}
// 🛒 Passer une commande à partir d’un panier
elseif ($uri === '/index.php/order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo "DOG";
    if (isset($_POST['basketId']) && isset($_SESSION['user']['email'])) {
        //echo "DOG";
        $controller->createOrderAction($orderPlacing, $_POST['basketId'], $_SESSION['user']['email']);
        //echo "DOG";
    }
    header("Location: /index.php/order"); // ou une autre page après commande
    exit();
}
// 🛒 Passer une commande à partir d’un panier
elseif ($uri === '/index.php/order') {
    //echo "dog";
    if (!isset($_SESSION['user'])) {
        $layout = new Layout("gui/layoutUnLogged.html");
        (new ViewError($layout, "🚫 Vous devez être connecté.", "/index.php"))->display();
        exit();
    }
    //echo "dog";
    $layout = ($_SESSION['type'] === 'manager')
        ? new Layout("gui/layoutLoggedManager.html")
        : new Layout("gui/layoutLoggedCustomer.html");
    //echo "dog";
    //echo $_SESSION['type'];
    //var_dump($_SESSION['user']);
    $view = new ViewOrders($layout, $_SESSION['user']['email'], $_SESSION['type'], $presenter, $ordersChecking);
    $view->display();
    exit();
}
else{
    // Fallback : erreur
    $layout = new Layout("gui/layoutUnLogged.html");
    (new ViewError($layout, "❌ Page inconnue ou accès non autorisé", "/index.php"))->display();
}


