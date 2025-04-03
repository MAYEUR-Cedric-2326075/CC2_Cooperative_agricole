<?php

namespace domain;

class Product {
    private string $id;
    private string $name;
    private float $price;
    private string $description;

    public function __construct(string $id, string $name, float $price, string $description = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description
        ];
    }

    public static function fromArray(array $data): Product {
        return new Product(
            $data['id'],
            $data['name'],
            (float) $data['price'],
            $data['description'] ?? ""
        );
    }
}
