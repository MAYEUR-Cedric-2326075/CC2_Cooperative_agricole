<?php

namespace service;

use domain\Basket;

interface BasketAccessInterface
{
    /**
     * @return Basket[] Tableau associatif [id => Basket]
     */
    public function getAllBaskets(): array;

    /**
     * @param string $id
     * @return Basket|null
     */
    public function getBasketById(string $id): ?Basket;

    /**
     * @param Basket|array $basket
     * @return bool
     */
    public function createBasket(Basket|array $basket): bool;

    /**
     * @param string $id
     * @return bool
     */
    public function deleteBasketById(string $id): bool;

    /**
     * Met à jour un panier existant.
     *
     * @param string $id
     * @param array $updatedData
     * @return bool
     */
    public function updateBasket(string $id, array $updatedData): bool;
}
