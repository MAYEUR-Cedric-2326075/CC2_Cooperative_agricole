<?php

namespace service;

use service\BasketAccessInterface;
use domain\Basket;

class SubscriberRegistration
{
    private BasketAccessInterface $basketAccess;

    public function __construct(BasketAccessInterface $basketAccess)
    {
        $this->basketAccess = $basketAccess;
    }

    public function subscribe(string $basketId, string $email): bool
    {
        $baskets = $this->basketAccess->getAllBaskets();

        if (!isset($baskets[$basketId])) return false;

        $basket = $baskets[$basketId];
        $subscribers = $basket->getSubscribers();

        if (!in_array($email, $subscribers)) {
            $subscribers[] = $email;
            $basket->setSubscribers($subscribers);
            return $this->basketAccess->createBasket($basket);
        }

        return true; // déjà abonné
    }

    public function unsubscribe(string $basketId, string $email): bool
    {
        $baskets = $this->basketAccess->getAllBaskets();

        if (!isset($baskets[$basketId])) return false;

        $basket = $baskets[$basketId];
        $subscribers = array_filter(
            $basket->getSubscribers(),
            fn($e) => $e !== $email
        );

        $basket->setSubscribers(array_values($subscribers));
        return $this->basketAccess->createBasket($basket);
    }

    public function getSubscribersOfBasket(string $basketId): array
    {
        $baskets = $this->basketAccess->getAllBaskets();

        return isset($baskets[$basketId])
            ? $baskets[$basketId]->getSubscribers()
            : [];
    }

    public function getBasketsSubscribedBy(string $email): array
    {
        $result = [];

        foreach ($this->basketAccess->getAllBaskets() as $basket) {
            if (in_array($email, $basket->getSubscribers())) {
                $result[] = $basket;
            }
        }

        return $result;
    }
}
