<?php

namespace domain;

class User {
    private string $email;        // correspond à l'email
    private string $type;      // "customer" ou "manager"
    private string $name;
    private string $password;

    public function __construct(string $email, string $type, string $name, string $password) {
        $this->email = $email; // email
        $this->type = $type;
        $this->name = $name;
        $this->password = $password;
    }

    public function getEmail(): string {
        return $this->email;
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

    public function getPassword(): string {
        return $this->password;
    }

    public function toArray(): array {
        return [
            "email" => $this->email,
            "type" => $this->type,
            "name" => $this->name,
            "password" => $this->password
        ];
    }

    public static function fromArray(array $data): User {
        //var_dump($data);
        return new User(
            $data['email'],
            $data['type'],
            $data['name'],
            $data['password']
        );
    }
}
