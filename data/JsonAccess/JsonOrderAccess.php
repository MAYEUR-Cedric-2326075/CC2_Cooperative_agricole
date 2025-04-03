<?php

namespace data\JsonAccess;

use domain\Order;
use service\OrderAccessInterface;


include_once __DIR__ . '/../../service/interfaces/OrderAccessInterface.php';
include_once __DIR__ . '/../../domain/Order.php';

class JsonOrderAccess implements OrderAccessInterface
{
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../data/Json/orders.json')
    {
        $this->filePath = $filePath;
    }

    public function getAllOrders(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        $array = json_decode($json, true) ?? [];

        return array_map(fn($o) => Order::fromArray($o), $array);
    }

    public function saveAllOrders(array $orders): bool
    {
        $data = array_map(fn($o) => $o->toArray(), $orders);
        return file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }

    public function createOrder(Order $order): bool
    {
        $orders = $this->getAllOrders();
        $orders[] = $order;
        return $this->saveAllOrders($orders);
    }

    public function getOrdersByCustomer(string $email): array
    {
        return array_values(array_filter(
            $this->getAllOrders(),
            fn($order) => $order->getCustomerEmail() === $email
        ));
    }

    /**
     * Récupère toutes les commandes liées à un panier spécifique.
     *
     * @param string $basketId
     * @return Order[]
     */
    public function getOrdersForBasket(string $basketId): array
    {
        return array_values(array_filter(
            $this->getAllOrders(),
            fn($order) => $order->getBasketId() === $basketId
        ));
    }




    public function getOrderById(string $id): ?Order
    {
        foreach ($this->getAllOrders() as $order) {
            if ($order->getId() === $id) {
                return $order;
            }
        }
        return null;
    }
}
