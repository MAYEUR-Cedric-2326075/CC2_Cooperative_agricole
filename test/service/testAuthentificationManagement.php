<?php

include_once __DIR__ . '/../../domain/User.php';
include_once __DIR__ . '/../../service/AuthentificationManagement.php';
include_once __DIR__ . '/../../service/interfaces/UserAccessInterface.php';
include_once __DIR__ . '/../../data/JsonAccess/JsonUserAccess.php';

use domain\User;
use service\AuthentificationManagement;
use data\JsonAccess\JsonUserAccess;

// Simulation d'un accès JSON
$userAccess = new JsonUserAccess(__DIR__ . '/../../data/Json/users.json');

// Création de l'objet AuthentificationManagement
$auth = new AuthentificationManagement($userAccess);

// Test de connexion d'un utilisateur connu
$email = "alice@example.com";
$password = "alice123";

$user = $auth->authenticate($email, $password);

if ($user) {
    echo "✅ Connexion réussie : " . $user->getName() . " (" . $user->getType() . ")\n";
    echo "Session type : " . ($_SESSION['type'] ?? 'aucun') . "\n";
} else {
    echo "❌ Connexion échouée pour $email\n";
}

// Test de déconnexion
$auth->logOut();
echo "👋 Déconnexion effectuée.\n";

var_dump($_SESSION); // doit être vide après logout
