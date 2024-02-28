<?php

namespace MyProject\Controllers;
use InvalidArgumentException;
use MyProject\Models\Users\UserActivationService;
use MyProject\Models\Users\UsersAuthService;
use MyProject\Services\EmailSender;
use MyProject\View\View;
use MyProject\Models\Users\User;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users;

class UsersController extends AbstractController
{

    public function signUp()
    {
        if (!empty($_POST)) {
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }
    
            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);
    
                EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code
                ]);
    
                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }
    
        $this->view->renderHtml('users/signUp.php');
    }
    
    public function login()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
                header('Location: http://localhost:8081/MyProject/www');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }
    
        $this->view->renderHtml('users/login.php');
    }

    public function activate(int $userId, string $activationCode)
    {
        $user = User::getById($userId);
        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
        if ($isCodeValid) {
            $user->activate();
            echo 'OK!';
        }
    }

    public function logout()
    {
        UsersAuthService::deleteCookie();
        header('Location: http://localhost:8081/MyProject/www/users/login');
    }

}

?>