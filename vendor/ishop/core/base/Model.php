<?php


namespace ishop\base;
use Valitron\Validator;
use \RedBeanPHP\R as R;

use ishop\Db;

abstract class Model
{
    public $attributes = [];
    public $errors = [];
    public $rules = [];

    public function __construct(){
        Db::instance();
    }
    
    public function load($data) {
        foreach ($this->attributes as $name => $value) {
            if(isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }
    
    public function validate($data) {
        Validator::langDir(WWW . '/validator/lang');
        Validator::lang('ru');
        $v = new Validator($data);
        $v->rules($this->rules);
        if ($v->validate()) {
            return true;
        }
        $this->errors = $v->errors();
        return false;
    }
    
    public function save($table) {
        $bean = R::dispense($table);
        foreach($this->attributes as $name => $value) {
            $bean->$name = $value;
        }
        return R::store($bean);
    }


    public function getErrors() {
        $errors = '<ul>';
        foreach ($this->errors as $error) {
            foreach ($error as $item) {
                $errors .= "<li>{$item}</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['error'] = $errors;    
    }
       
    

}