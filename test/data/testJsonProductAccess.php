<?php

include_once __DIR__ . '/../../domain/Product.php';

use domain\Product;

// Chargement du fichier JSON
$jsonPath = __DIR__ . '/../../data/Json/products.json';
$jsonData = file_get_contents($jsonPath);
$array = json_decode($jsonData, true);

// Création des objets Product
$products = array_map(fn($p) => Product::fromArray($p), $array);

// Affichage des produits
foreach ($products as $product) {
    echo "🆔 ID: " . $product->getId() . "\n";
    echo "📦 Name: " . $product->getName() . "\n";
    echo "💶 Price: " . $product->getPrice() . " €\n";
    echo "📚 Description: " . $product->getDescription() . "\n";
    echo "---------------------------\n";
}
