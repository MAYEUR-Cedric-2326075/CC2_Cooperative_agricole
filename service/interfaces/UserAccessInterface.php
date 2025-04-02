<?php
namespace service;

use domain\User;

interface UserAccessInterface {
    public function getAllUsers(): array;
    public function getUser(string $email): ?User;
    public function getUsersByType(string $type): array;
    public function createUser(User|array $userData): bool;
    public function deleteUserByEmail(string $email): bool;
}
