<?php

include_once __DIR__ . '/../../domain/Basket.php';

use domain\Basket;

// Chargement du fichier JSON
$jsonPath = __DIR__ . '/../../data/Json/baskets.json';
$jsonData = file_get_contents($jsonPath);
$array = json_decode($jsonData, true);

// Création des objets Basket
$baskets = array_map(fn($b) => Basket::fromArray($b), $array);

// Affichage des paniers
foreach ($baskets as $basket) {
    echo "🧺 Basket ID: " . $basket->getId() . "\n";
    echo "👤 User: " . $basket->getUserId() . "\n";
    echo "📦 Status: " . $basket->getStatus() . "\n";
    echo "🕓 Created: " . $basket->getCreatedAt() . "\n";

    echo "📋 Items:\n";
    foreach ($basket->getItems() as $item) {
        echo "   - Product ID: " . $item['productId'] . ", Quantity: " . $item['quantity'] . "\n";
    }

    echo "👥 Subscribers:\n";
    foreach ($basket->getSubscribers() as $subscriber) {
        echo "   - " . $subscriber . "\n";
    }

    echo str_repeat("-", 30) . "\n";
}
