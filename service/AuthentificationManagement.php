<?php

namespace service;

use domain\User;
use service\UserAccessInterface;
class AuthentificationManagement {
    private UserAccessInterface $userAccess;

    public function __construct(UserAccessInterface $userAccess) {
        $this->userAccess = $userAccess;
    }

    public function authenticate(string $email, string $password): ?User {
        $user = $this->userAccess->getUser($email);

        if (!$user || $user->getPassword() !== $password) {
            return null;
        }

        if ($user->isCustomer()) {
            return $this->logInCustomer($user);
        } elseif ($user->isManager()) {
            return $this->logInManager($user);
        }

        return null;
    }

    public function logOut(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();
    }

    private function logInCustomer(User $user): User {
        $this->startSessionIfNeeded();
        $_SESSION['user'] = $user->toArray();
        $_SESSION['type'] = 'customer';
        return $user;
    }

    private function logInManager(User $user): User {
        $this->startSessionIfNeeded();
        $_SESSION['user'] = $user->toArray();
        $_SESSION['type'] = 'manager';
        return $user;
    }

    private function startSessionIfNeeded(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
