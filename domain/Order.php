<?php

namespace domain;

class Order
{
    private string $id;
    private string $basketId;
    private string $customerEmail;
    private string $orderedAt;

    public function __construct(string $id, string $basketId, string $customerEmail, string $orderedAt)
    {
        $this->id = $id;
        $this->basketId = $basketId;
        $this->customerEmail = $customerEmail;
        $this->orderedAt = $orderedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBasketId(): string
    {
        return $this->basketId;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getOrderedAt(): string
    {
        return $this->orderedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'basketId' => $this->basketId,
            'customerEmail' => $this->customerEmail,
            'orderedAt' => $this->orderedAt
        ];
    }

    public static function fromArray(array $data): Order
    {
        return new Order(
            $data['id'],
            $data['basketId'],
            $data['customerEmail'],
            $data['orderedAt'] ?? date('Y-m-d H:i:s') // fallback si absent
        );
    }

}
