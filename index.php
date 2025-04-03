<?php

// --- domain ---
include_once 'domain/User.php';
include_once 'domain/Basket.php';

// --- service ---
include_once 'service/AuthentificationManagement.php';
include_once 'service/BasketService.php';
include_once 'service/UserCreation.php';
include_once 'service/interfaces/UserAccessInterface.php';
include_once 'service/interfaces/BasketAccessInterface.php';

// --- gui ---
include_once 'gui/Layout.php';
include_once 'gui/ViewLogin.php';
include_once 'gui/ViewError.php';
include_once 'gui/ViewManageBaskets.php';
include_once 'gui/ViewCreateBasket.php';

// --- data / JsonAccess ---
include_once 'data/JsonAccess/JsonUserAccess.php';
include_once 'data/JsonAccess/JsonBasketAccess.php';
include_once 'data/JsonAccess/JsonOrderAccess.php';
include_once 'data/JsonAccess/JsonProductAccess.php';

// --- control ---
include_once 'control/Controllers.php';
include_once 'control/Presenter.php';
include_once 'gui/ViewSubscribers.php';
include_once 'gui/ViewSubscription.php';

use control\{Controllers, Presenter};
use data\JsonAccess\{JsonBasketAccess, JsonOrderAccess, JsonUserAccess, JsonProductAccess};
use service\{AuthentificationManagement, UserCreation,BasketService};
use gui\{Layout, ViewLogin, ViewError, ViewManageBaskets,ViewCreateBasket,ViewSubscribers,ViewSubscription};

// Session
session_start();

// Services
$dataUsers = new JsonUserAccess(__DIR__ . '/data/Json/users.json');
$dataBaskets = new JsonBasketAccess(__DIR__ . '/data/Json/baskets.json');
$dataOrders = new JsonOrderAccess(__DIR__ . '/data/Json/orders.json');
$dataProducts = new JsonProductAccess(__DIR__ . '/data/Json/products.json');
$authService = new AuthentificationManagement($dataUsers);
$controller = new Controllers();
$userCreation = new UserCreation() ;
$presenter = new Presenter($dataBaskets,$dataProducts);
$basketService = new BasketService($dataBaskets);
// URL demandée
$uri =parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//$uri ='/';
// Page de connexion
// Page d'accueil / login
if ( '/' == $uri || '/index.php' == $uri || '/index.php/logout' == $uri) {
    session_destroy();
    $layout = new Layout("gui/layoutUnLogged.html");
    $viewLogin = new ViewLogin($layout);
    $viewLogin->display();
}
// Traitement de l'authentification (POST du formulaire)
elseif ($uri == '/index.php/login') {
    $error = $controller->authenticateAction($userCreation, $authService,$_POST['email'],$_POST['password']);
//$_POST['email'], $_POST['password']);
    if ($error !== null) {
        $layout = new Layout("gui/layoutUnLogged.html");
        $viewError = new ViewError($layout, $error, '/index.php');
        $viewError->display();
    } else {
        header("Location: /index.php/baskets");
        exit();
    }
}
elseif ($uri === '/index.php/createBasket' && $_SERVER['REQUEST_METHOD'] === 'POST') {
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
}

elseif ($uri === '/index.php/editBasket' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    /*$controller->editBasketAction($basketService, [
        'id' => $_POST['id'],
        'userId' => $_SESSION['user']['email'],
        'status' => $_POST['status'],
        'createdAt' => $_POST['createdAt'],
        'items' => json_decode($_POST['items'], true) ?? []
    ]);*/
    header("Location: /index.php/baskets");
    exit();
}

elseif ($uri === '/index.php/deleteBasket' && isset($_GET['id'])) {
    $controller->deleteBasketAction($basketService, $_GET['id']);
    header("Location: /index.php/baskets");
    exit();
}
// 🔁 Abonner un utilisateur à un panier
elseif ($uri === '/index.php/subscribe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['basketId']) && isset($_SESSION['user']['email'])) {
        //$basketService->subscribeToBasket($_POST['basketId'], $_SESSION['user']['email']);
    }
    header("Location: /index.php/subscriptions");
    exit();
}

// 🔁 Désabonner un utilisateur d’un panier
elseif ($uri === '/index.php/unsubscribe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['basketId'], $_POST['email'])) {
        //$basketService->unsubscribeFromBasket($_POST['basketId'], $_POST['email']);
    }
    // Redirige vers la page précédente
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// 👁 Voir les abonnés d’un panier (Manager)
elseif ($uri === '/index.php/subscribers' && isset($_GET['basketId']) && $_SESSION['type'] === 'manager') {

    $layout = new Layout("gui/layoutLoggedManager.html");
    $view = new ViewSubscribers($layout, $_SESSION['user']['email'], $_GET['basketId'], $presenter);
    $view->display();
}

// 👁 Voir ses abonnements (Customer)
elseif ($uri === '/index.php/subscriptions' && $_SESSION['type'] === 'customer') {
    $layout = new Layout("gui/layoutLoggedCustomer.html");
    $view = new ViewSubscription($layout, $_SESSION['user']['email'], $presenter);
    $view->display();
}
// Page de succès
// Visualisation des paniers (manager uniquement)
elseif (strpos($uri, '/index.php') === 0 && isset($_SESSION['user']) && $_SESSION['type'] === 'manager') {

    // 🔄 Créer un panier via le contrôleur
    if ($uri === '/index.php/createBasket' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $layout = new Layout("gui/layoutLoggedManager.html");

        $view = new ViewCreateBasket($layout, $_SESSION['user']['email'], $presenter);
        $view->display();

    }

    // 🗑 Supprimer un panier via le contrôleur
    elseif ($uri === '/index.php/deleteBasket' && isset($_GET['id'])) {
        //$controller->deleteBasketAction($_GET['id']);
        header("Location: /index.php/baskets");
        exit();
    }

    // ✏ Modifier un panier (réutilise createBasketAction pour remplacement)
    elseif ($uri === '/index.php/editBasket' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //$controller->createBasketAction($_SESSION['email']);
        header("Location: /index.php/baskets");
        exit();
    }

    // 🧺 Visualiser la liste complète
    elseif ($uri === '/index.php/baskets') {
        $layout = new Layout("gui/layoutLoggedManager.html");
        $view = new ViewManageBaskets($layout, $_SESSION['user']['email'], $presenter);
        $view->display();
    }

    // ❌ URL non reconnue
    else {
        /*if(isset($_GET['basketId']))
            echo"Dog";
        else
            echo "Cat";
        echo $_SESSION['type'];*/
        $layout = new Layout("gui/layoutLoggedManager.html");
        $viewError = new ViewError($layout, "❌ URL invalide pour un gestionnaire", "/index.php");
        $viewError->display();
    }
}

