<?php

namespace app\models;
use \RedBeanPHP\R as R;
/**
 * Description of User
 *
 * @author Sergey
 */
class User extends AppModel{
    
    public $attributes = [
        'login' => '',  
        'password' => '', 
        'email' => '', 
        'address' => '', 
        'name' => '', 
    ];
    
    public $rules = [
        'required' => [
            ['login'],
            ['password'],
            ['name'],
            ['email'],
            ['address'],
        ],
        'email' => [
            ['email'],
        ],
        'lengthMin' => [
            ['password', 6],
        ]
    ];
    
    public function checkUnique() {
        $user = R::findOne('user', "login = ? OR email = ?", [$this->attributes['login'], $this->attributes['email']]);
        if ($user) {
            if ($user->login === $this->attributes['login']) {
                $this->errors['unique'][] = 'Этот логин уже занят';
            }
            if ($user->email === $this->attributes['email']) {
                $this->errors['unique'][] = 'Этот email уже занят';
            }
            return false;
        }
        return true;
    }
    
    public function login($isAdmin = false) {
        $login = !empty($_POST['login']) ? trim($_POST['login']) : null;
        $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
        if ($login && $password) {
            if($isAdmin) {
                $user = R::findOne('user', "login = ? AND role = 'admin'", [$login]);
            }else {
                 $user = R::findOne('user', "login = ?", [$login]);
            }
            if ($user) {
                if(password_verify($password, $user->password)) {
                    foreach ($user as $k => $v) {
                        if ($k !== 'password') $_SESSION['user'][$k] = $v;
                    }
                    return true;
                }
            }
        }
        return false;
    }
}
