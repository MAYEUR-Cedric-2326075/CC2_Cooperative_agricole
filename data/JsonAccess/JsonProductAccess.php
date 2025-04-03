<?php

namespace data\JsonAccess;

use domain\Product;
use service\ProductAccessInterface;

include_once __DIR__ . '/../../domain/Product.php';
include_once __DIR__ . '/../../service/interfaces/ProductAccessInterface.php';

class JsonProductAccess implements ProductAccessInterface
{
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../data/Json/products.json') {
        $this->filePath = $filePath;
    }

    public function getAllProducts(): array {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);

        $products = [];
        foreach ($data as $productData) {
            $product = Product::fromArray($productData);
            $products[$product->getId()] = $product;
        }

        return $products;
    }

    public function getProductById(string $id): ?Product {
        $products = $this->getAllProducts();
        return $products[$id] ?? null;
    }
}
