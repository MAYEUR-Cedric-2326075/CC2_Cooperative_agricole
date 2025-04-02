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
use gui\{Layout, ViewLogin, ViewError, ViewManageBaskets};
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
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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
elseif ($uri == '/index.php/baskets') {
    if (isset($_SESSION['user']) && $_SESSION['type'] === 'manager') {
        $layout = new Layout("gui/layoutLoggedManager.html");
        $view = new ViewManageBaskets($layout, $_SESSION['user']['email'], $presenter);
        $view->display();
    } else {
        $layout = new Layout("gui/layoutUnLogged.html");
        $viewError = new ViewError($layout, "⛔ Accès interdit", "/index.php");
        $viewError->display();
    }
}
// Fallback
else {
    echo "<h1>404 Not Found</h1>";
}
