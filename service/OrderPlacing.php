<?php

namespace service;

use domain\Order;
use service\OrderAccessInterface;
use service\BasketAccessInterface;

class OrderPlacing
{
    private OrderAccessInterface $orderAccess;
    private BasketAccessInterface $basketAccess;

    public function __construct(OrderAccessInterface $orderAccess, BasketAccessInterface $basketAccess)
    {
        $this->orderAccess = $orderAccess;
        $this->basketAccess = $basketAccess;
    }

    public function placeOrder(string $basketId, string $customerEmail): bool
    {
        $basket = $this->basketAccess->getBasketById($basketId);
        if (!$basket) {
            return false; // panier inexistant
        }

        $orderId = uniqid("order-", true);

        $order = new Order(
            $orderId,
            $basketId,
            $customerEmail,
            date('Y-m-d\TH:i:s\Z'),
            $basket->getItems()
        );

        return $this->orderAccess->createOrder($order);
    }
}
