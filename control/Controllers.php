<?php

namespace control;

use service\AuthentificationManagement;
use service\BasketAccessInterface;
use service\UserCreation;
use domain\User;

class Controllers {

    public function authenticateAction(
        UserCreation $userCreation,
        AuthentificationManagement $auth,
        $dataUsers
    ): ?string {
        $user = $auth->authenticate($_POST['email'], $_POST['password']);
        if ($user === null) {
            return 'bad login or pwd';
        }
        return null;
    }

    public function logoutAction(AuthentificationManagement $auth): void {
        $auth->logOut();
    }

    public function createBasketAction(BasketAccessInterface $basketAccess, string $userEmail): bool {
        if (!isset($_POST['id'], $_POST['status'], $_POST['createdAt'], $_POST['items'])) {
            return false;
        }

        $basket = [
            'id' => $_POST['id'],
            'userId' => $userEmail,
            'status' => $_POST['status'],
            'createdAt' => $_POST['createdAt'],
            'items' => json_decode($_POST['items'], true) ?? []
        ];

        return $basketAccess->createBasket($basket);
    }

    public function deleteBasketAction(BasketAccessInterface $basketAccess): bool {
        if (!isset($_GET['id'])) {
            return false;
        }

        return $basketAccess->deleteBasketById($_GET['id']);
    }
}
