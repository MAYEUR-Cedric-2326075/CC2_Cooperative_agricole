<?php

namespace data\JsonAccess;

use domain\Basket;
use service\BasketAccessInterface;

include_once __DIR__ . '/../../domain/Basket.php';
include_once __DIR__ . '/../../service/interfaces/BasketAccessInterface.php';

class JsonBasketAccess implements BasketAccessInterface {
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../data/Json/baskets.json') {
        $this->filePath = $filePath;
    }

    public function getBasketsByUser(string $email): array {
        $baskets = $this->getAllBaskets();
        $filtered = [];

        foreach ($baskets as $basket) {
            if ($basket->getUserId() === $email) {
                $filtered[$basket->getId()] = $basket;
            }
        }

        return $filtered;
    }

    public function getAllBaskets(): array {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);

        $baskets = [];
        foreach ($data as $b) {
            $basket = Basket::fromArray($b);
            $baskets[$basket->getId()] = $basket;
        }

        return $baskets;
    }

    public function getBasketById(string $id): ?Basket {
        $baskets = $this->getAllBaskets();
        return $baskets[$id] ?? null;
    }

    public function createBasket(Basket|array $basket): bool {
        $baskets = $this->getAllBaskets();

        if (is_array($basket)) {
            if (!isset($basket['id'], $basket['userId'], $basket['status'], $basket['items'], $basket['createdAt'])) {
                return false;
            }
            $basket = Basket::fromArray($basket);
        }

        $baskets[$basket->getId()] = $basket;
        $arrayData = array_map(fn($b) => $b->toArray(), $baskets);

        return file_put_contents($this->filePath, json_encode(array_values($arrayData), JSON_PRETTY_PRINT)) !== false;
    }

    public function deleteBasketById(string $id): bool {
        $baskets = $this->getAllBaskets();
        if (!isset($baskets[$id])) {
            return false;
        }

        unset($baskets[$id]);
        $arrayData = array_map(fn($b) => $b->toArray(), $baskets);

        return file_put_contents($this->filePath, json_encode(array_values($arrayData), JSON_PRETTY_PRINT)) !== false;
    }

    public function updateBasket(string $id, array $updatedData): bool {
        $baskets = $this->getAllBaskets();

        if (!isset($baskets[$id])) {
            return false;
        }

        $existing = $baskets[$id]->toArray();
        $merged = array_merge($existing, $updatedData);
        $baskets[$id] = Basket::fromArray($merged);

        $arrayData = array_map(fn($b) => $b->toArray(), $baskets);

        return file_put_contents($this->filePath, json_encode(array_values($arrayData), JSON_PRETTY_PRINT)) !== false;
    }
}
