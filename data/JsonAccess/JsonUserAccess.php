<?php
namespace data\JsonAccess;

use domain\User;
use service\UserAccessInterface;
include_once __DIR__ . '/../../domain/User.php';

include_once __DIR__ . '/../../service/interfaces/UserAccessInterface.php';


class JsonUserAccess implements UserAccessInterface {
    private string $filePath;

    public function __construct(string $filePath = __DIR__ . '/../../data/Json/users.json') {
        $this->filePath = $filePath;
    }

    public function getAllUsers(): array {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);

        $users = [];
        foreach ($data as $u) {
            $user = User::fromArray($u);
            $users[$user->getId()] = $user;
        }

        return $users;
    }

    public function getUser(string $email): ?User {
        foreach ($this->getAllUsers() as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }

    public function getUsersByType(string $type): array {
        $filtered = [];
        foreach ($this->getAllUsers() as $id => $user) {
            if ($user->getType() === $type) {
                $filtered[$id] = $user;
            }
        }
        return $filtered;
    }

    public function createUser(User|array $userData): bool {
        $users = $this->getAllUsers();

        // Convert array to User if needed
        if (is_array($userData)) {
            if (!isset($userData['type'], $userData['name'], $userData['email'], $userData['password'])) {
                return false;
            }
            $id = count($users) > 0 ? max(array_keys($users)) + 1 : 1;
            $userData = new User($id, $userData['type'], $userData['name'], $userData['email'], $userData['password']);
        }

        // Check if email already exists
        foreach ($users as $user) {
            if ($user->getEmail() === $userData->getEmail()) {
                return false;
            }
        }

        $users[$userData->getId()] = $userData;
        $arrayData = array_map(fn($u) => $u->toArray(), $users);
        return file_put_contents($this->filePath, json_encode(array_values($arrayData), JSON_PRETTY_PRINT)) !== false;
    }


    public function deleteUserByEmail(string $email): bool {
        $users = $this->getAllUsers();
        $originalCount = count($users);

        $users = array_filter($users, fn($u) => $u->getEmail() !== $email);

        if (count($users) === $originalCount) {
            return false;
        }

        $arrayData = array_map(fn($u) => $u->toArray(), $users);
        return file_put_contents($this->filePath, json_encode(array_values($arrayData), JSON_PRETTY_PRINT)) !== false;
    }
}