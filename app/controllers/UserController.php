<?php

namespace app\controllers;
use app\models\User;

/**
 * Description of UserController
 *
 * @author Sergey
 */
class UserController extends AppController{
    
    
    public function loginAction() {
        if (!empty($_POST)) {
            $this->setMeta('Авторизация');
            $user = new User();
            if($user->login()){
                $_SESSION['success'] = 'Вы успешно авторизованы';
            }else {
                $_SESSION['error'] = 'Неверно введен логин\пароль';
            }
            redirect();
        }
        $this->setMeta('Авторизация');
    }
    
    public function signupAction() {
        if (!empty($_POST)) {
            $user = new User();
            $data = $_POST;
            $user->load($data);
            if (!$user->validate($data) || !$user->checkUnique()){
                $user->getErrors();
                $_SESSION['form_data'] = $data;
            }else {
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                if ($user->save('user')){
                    $_SESSION['success'] = 'Пользователь зарегистрирован!';
                } else {
                    $_SESSION['error'] = 'Ошибка!';
                }
            }
            redirect();
            
        }
        $this->setMeta('Регистрация');
        
    }
    
    public function logoutAction() {
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        redirect('http://ishop2.loc/');
    }
}
