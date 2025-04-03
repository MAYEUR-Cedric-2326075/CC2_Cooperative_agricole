<?php

namespace service;

use domain\Product;

interface ProductAccessInterface {
    public function getAllProducts(): array;
    public function getProductById(string $id): ?Product;
}
