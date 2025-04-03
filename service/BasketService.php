<?php

namespace service;

use domain\Basket;
use service\BasketAccessInterface;

class BasketService
{
    private BasketAccessInterface $basketAccess;

    public function __construct(BasketAccessInterface $basketAccess)
    {
        $this->basketAccess = $basketAccess;
    }

    public function createBasket(array $data): bool
    {
        if (!isset($data['id'], $data['userId'], $data['status'], $data['items'], $data['createdAt'])) {
            return false;
        }

        $basket = Basket::fromArray($data);
        return $this->basketAccess->createBasket($basket);
    }

    public function updateBasket(array $data): bool
    {
        // En JsonBasketAccess, createBasket remplace s'il existe déjà
        return $this->createBasket($data);
    }

    public function deleteBasket(string $id): bool
    {
        return $this->basketAccess->deleteBasketById($id);
    }

    public function getBasket(string $id): ?Basket
    {
        return $this->basketAccess->getBasketById($id);
    }

    public function getAllBaskets(): array
    {
        return $this->basketAccess->getAllBaskets();
    }

    public function getBasketsByUser(string $email): array
    {
        return $this->basketAccess->getBasketsByUser($email);
    }
}
