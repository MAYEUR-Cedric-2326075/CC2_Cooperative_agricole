<?php

// --- control ---
include_once 'control/Controllers.php';

// --- data ---
include_once 'data/JsonAccess/JsonUserAccess.php';

// --- domain ---
include_once 'domain/User.php';

// --- service ---
include_once 'service/AuthentificationManagement.php';
include_once 'service/UserCreation.php';
include_once 'service/interfaces/UserAccessInterface.php';


// --- gui ---
include_once 'gui/Layout.php';
include_once 'gui/ViewLogin.php';
include_once 'gui/ViewError.php';

use control\Controllers;
use data\JsonAccess\JsonUserAccess;
use domain\User;
use service\{AuthentificationManagement,UserCreation};
use gui\{Layout, ViewLogin, ViewError};

// Session
session_start();

// Services
$dataUsers = new JsonUserAccess(__DIR__ . '/data/Json/users.json');
$authService = new AuthentificationManagement($dataUsers);
$controller = new Controllers();
$userCreation = new UserCreation() ;
// URL demandée
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Page de connexion
// Page d'accueil / login
if ($uri == '/' || $uri == '/index.php') {
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
        header("Location: /index.php/success");
        exit();
    }
}
// Page de succès
elseif ($uri == '/index.php/success') {
    echo "<h1>✅ Logged in successfully as " . ($_SESSION['user']['email'] ?? 'unknown') . "</h1>";
    echo "<p>Type: " . ($_SESSION['type'] ?? 'none') . "</p>";
    echo "<a href='/index.php'>Logout</a>";
}
// Fallback
else {
    echo "<h1>404 Not Found</h1>";
}
