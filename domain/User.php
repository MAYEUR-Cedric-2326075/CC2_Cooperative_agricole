<?php

namespace domain;

class User {
    private int $id;
    private string $type;      // "customer" ou "manager"
    private string $name;
    private string $email;
    private string $password;

    public function __construct(int $id, string $type, string $name, string $email, string $password) {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getType(): string {
        return $this->type;
    }

    public function isCustomer(): bool {
        return $this->type === "customer";
    }

    public function isManager(): bool {
        return $this->type === "manager";
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "type" => $this->type,
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password
        ];
    }
    public static function fromArray(array $data): User {
        return new User(
            $data['id'],
            $data['type'],
            $data['name'],
            $data['email'],
            $data['password']
        );
    }

}
