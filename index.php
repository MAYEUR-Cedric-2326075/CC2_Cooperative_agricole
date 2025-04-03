<?php

// --- domain ---
include_once 'domain/User.php';
include_once 'domain/Basket.php';

// --- service ---
include_once 'service/AuthentificationManagement.php';
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

use control\{Controllers, Presenter};
use data\JsonAccess\{JsonBasketAccess, JsonUserAccess};
use service\{AuthentificationManagement, UserCreation};
use gui\{Layout, ViewLogin, ViewError, ViewManageBaskets,ViewCreateBasket};
// Session
session_start();

// Services
$dataUsers = new JsonUserAccess(__DIR__ . '/data/Json/users.json');
$dataBaskets = new JsonBasketAccess(__DIR__ . '/data/Json/baskets.json');
$authService = new AuthentificationManagement($dataUsers);
$controller = new Controllers();
$userCreation = new UserCreation() ;
$presenter = new Presenter($dataBaskets);
// URL demandée
$uri =parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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
    $error = $controller->authenticateAction($userCreation, $authService, $dataUsers);

    if ($error !== null) {
        $layout = new Layout("gui/layoutUnLogged.html");
        $viewError = new ViewError($layout, $error, '/index.php');
        $viewError->display();
    } else {
        header("Location: /index.php/baskets");
        exit();
    }
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
        //$controller->deleteBasketAction();
        header("Location: /index.php/baskets");
        exit();
    }

    // ✏ Modifier un panier (réutilise createBasketAction pour remplacement)
    elseif ($uri === '/index.php/editBasket' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->createBasketAction($_SESSION['email']);
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
        $layout = new Layout("gui/layoutLoggedManager.html");
        $viewError = new ViewError($layout, "❌ URL invalide pour un gestionnaire", "/index.php/baskets");
        $viewError->display();
    }
}

