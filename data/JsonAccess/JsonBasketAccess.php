<?php

namespace data\JsonAccess;

class JsonBasketAccess {
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../data/Json/baskets.json') {
        $this->filePath = $filePath;
    }

    public function getAllBaskets(): array {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);

        $baskets = [];
        foreach ($data as $basket) {
            $baskets[$basket['id']] = $basket;
        }

        return $baskets;
    }

    public function getBasketById(int $id): ?array {
        $baskets = $this->getAllBaskets();
        return $baskets[$id] ?? null;
    }

    public function createBasket(array $basket): bool {
        $baskets = $this->getAllBaskets();

        $basket['id'] = count($baskets) > 0 ? max(array_keys($baskets)) + 1 : 1;
        $baskets[$basket['id']] = $basket;

        return file_put_contents($this->filePath, json_encode(array_values($baskets), JSON_PRETTY_PRINT)) !== false;
    }

    public function deleteBasket(int $id): bool {
        $baskets = $this->getAllBaskets();
        if (!isset($baskets[$id])) {
            return false;
        }

        unset($baskets[$id]);
        return file_put_contents($this->filePath, json_encode(array_values($baskets), JSON_PRETTY_PRINT)) !== false;
    }
}
