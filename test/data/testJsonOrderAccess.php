<?php

include_once '../../data/JsonAccess/JsonOrderAccess.php';
include_once '../../domain/Order.php';

use data\JsonAccess\JsonOrderAccess;
use domain\Order;

$orderAccess = new JsonOrderAccess(__DIR__ . '/../../data/Json/orders.json');
/*
// --- Création d'une commande via un objet Order ---
$order1 = new Order("cmd100", "basket1", "client1@example.com", "2024-04-03 14:00");
$created1 = $orderAccess->createOrder($order1);
echo $created1 ? "✅ Order cmd100 created successfully.\n" : "❌ Order cmd100 already exists or error.\n";

// --- Deuxième commande ---
$order2 = new Order("cmd101", "basket2", "client2@example.com", "2024-04-03 14:30");
$created2 = $orderAccess->createOrder($order2);
echo $created2 ? "✅ Order cmd101 created successfully.\n" : "❌ Order cmd101 already exists or error.\n";*/

// --- Récupération d'une commande spécifique ---
$retrieved = $orderAccess->getOrderById("cmd100");
/*echo "🔍 Retrieved order cmd100:\n";
print_r($retrieved);*/

// --- Commandes d'un client ---
$clientOrders = $orderAccess->getOrdersByCustomer("alice@example.com");
echo "📦 Orders for client2@example.com:\n";
print_r($clientOrders);

// --- Toutes les commandes ---
$allOrders = $orderAccess->getAllOrders();
echo "🗂️ All orders:\n";
//print_r($allOrders);
