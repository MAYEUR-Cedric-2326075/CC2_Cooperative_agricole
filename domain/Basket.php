<?php

namespace domain;

class Basket {
    private string $id;
    private string $userId;
    private string $status;
    private array $items;
    private string $createdAt;

    public function __construct(string $id, string $userId, string $status, array $items, string $createdAt) {
        $this->id = $id;
        $this->userId = $userId;
        $this->status = $status;
        $this->items = $items;
        $this->createdAt = $createdAt;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getUserId(): string {
        return $this->userId;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'status' => $this->status,
            'items' => $this->items,
            'createdAt' => $this->createdAt
        ];
    }

    public static function fromArray(array $data): Basket {
        return new Basket(
            $data['id'],
            $data['userId'],
            $data['status'],
            $data['items'],
            $data['createdAt']
        );
    }
}