<?php

namespace service;

use domain\Order;

class OrdersChecking
{
    private OrderAccessInterface $orderAccess;
    private BasketAccessInterface $basketAccess;

    public function __construct(OrderAccessInterface $orderAccess, BasketAccessInterface $basketAccess)
    {
        $this->orderAccess = $orderAccess;
        $this->basketAccess = $basketAccess;
    }

    /**
     * Récupère toutes les commandes d'un utilisateur (client).
     * @param string $customerEmail
     * @return Order[]
     */
    public function getOrdersForCustomer(string $customerEmail): array
    {
        return $this->orderAccess->getOrdersByCustomer($customerEmail);
    }

    /**
     * Récupère toutes les commandes des paniers appartenant à un manager.
     * @param string $managerEmail
     * @return Order[]
     */
    public function getOrdersForManager(string $managerEmail): array
    {
        $orders = $this->orderAccess->getAllOrders();
        $baskets = $this->basketAccess->getBasketsByUser($managerEmail);
        $basketIds = array_map(fn($basket) => $basket->getId(), $baskets);

        return array_filter($orders, function (Order $order) use ($basketIds) {
            return in_array($order->getBasketId(), $basketIds, true);
        });
    }

    /**
     * Récupère toutes les commandes disponibles.
     * @return Order[]
     */
    public function getAllOrders(): array
    {
        return $this->orderAccess->getAllOrders();
    }
}
