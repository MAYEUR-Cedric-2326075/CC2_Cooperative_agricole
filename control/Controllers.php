<?php

namespace control;

use service\AuthentificationManagement;
use service\BasketService;
use service\SubscriptionService;
use service\UserCreation;
use domain\User;

class Controllers {

    public function authenticateAction(
        UserCreation $userCreation,
        AuthentificationManagement $auth,
        string $email,
        string $password/*,
        $dataUsers*/
    ): ?string {
        $user = $auth->authenticate($email, $password);
        if ($user === null) {
            return 'bad login or pwd';
        }
        return null;
    }

    public function logoutAction(AuthentificationManagement $auth): void {
        $auth->logOut();
    }

    public function createBasketAction(BasketService $basketService, array $basketData): bool {
        if (!isset($basketData['id'], $basketData['status'], $basketData['createdAt'], $basketData['items'], $basketData['userId'])) {
            return false;
        }

        return $basketService->createBasket($basketData);
    }

    public function deleteBasketAction(BasketService $basketService, string $id): bool {
        return $basketService->deleteBasket($id);
    }
    public function aboSubscribeAction(SubscriptionService $subService, string $customerEmail, string $managerEmail): bool {
        return $subService->subscribe($customerEmail, $managerEmail);
    }

    public function aboUnsubscribeAction(SubscriptionService $subService, string $customerEmail, string $managerEmail): bool {
        return $subService->unsubscribe($customerEmail, $managerEmail);
    }
}
