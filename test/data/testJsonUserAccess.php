<?php

include_once '../../data/JsonAccess/JsonUserAccess.php';
include_once '../../domain/User.php';

use data\Json\JsonUserAccess;
use domain\User;

$jsonAccess = new JsonUserAccess(__DIR__ . '/../../data/Json/users.json');

// --- Création d'un utilisateur via un tableau ---
$newUserArray = [
    "type" => "customer",
    "name" => "Charlie Test",
    "email" => "charlie@example.com",
    "password" => "test123"
];

$createdArray = $jsonAccess->createUser($newUserArray);
echo $createdArray ? "✅ User (array) created successfully.\n" : "❌ User already exists.\n";

// --- Vérification de la présence de l'utilisateur ---
$user = $jsonAccess->getUserByEmail("charlie@example.com");
echo "🔍 Retrieved user (from array creation):\n";
print_r($user);

// --- Création d'un utilisateur via un objet User ---
$userObject = new User(0, "manager", "Dorian Manager", "dorian@example.com", "manager123");
$createdObject = $jsonAccess->createUser($userObject);
echo $createdObject ? "✅ User (object) created successfully.\n" : "❌ User already exists.\n";

// --- Vérification de l'utilisateur objet ---
$userObjCheck = $jsonAccess->getUserByEmail("dorian@example.com");
echo "🔍 Retrieved user (from object creation):\n";
print_r($userObjCheck);

// --- Récupération des managers ---
$managers = $jsonAccess->getUsersByType("manager");
echo "📋 List of managers:\n";
print_r($managers);

// --- Suppression de l'utilisateur Charlie ---
$deleted = $jsonAccess->deleteUserByEmail("charlie@example.com");
echo $deleted ? "🗑️ User 'charlie@example.com' deleted successfully.\n" : "❌ User not found for deletion.\n";

// --- Vérification de la suppression ---
$userAfterDelete = $jsonAccess->getUserByEmail("charlie@example.com");
echo "🔍 User after deletion:\n";
var_dump($userAfterDelete);

// --- Affichage de tous les utilisateurs restants ---
echo "📦 Remaining users:\n";
print_r($jsonAccess->getAllUsers());