<?php

namespace control;

use service\AuthentificationManagement;
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


}
