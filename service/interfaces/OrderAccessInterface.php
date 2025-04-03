<?php

namespace service;

use domain\Order;

interface OrderAccessInterface
{
    /**
     * Retourne toutes les commandes
     * @return Order[]
     */
    public function getAllOrders(): array;

    /**
     * Crée une nouvelle commande
     * @param Order $order
     * @return bool succès ou échec
     */
    public function createOrder(Order $order): bool;

    /**
     * Retourne toutes les commandes passées par un client donné
     * @param string $customerEmail
     * @return Order[]
     */
    public function getOrdersByCustomer(string $customerEmail): array;

    /**
     * Cherche une commande par son identifiant
     * @param string $id
     * @return Order|null
     */
    public function getOrderById(string $id): ?Order;

    public function getOrdersForBasket(string $basketId): array;
}
