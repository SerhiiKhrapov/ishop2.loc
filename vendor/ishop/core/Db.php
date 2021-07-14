<?php


namespace ishop;
use \RedBeanPHP\R as R;
class Db
{
    use TSingletone;

    protected function __construct(){
        $db = require_once CONF . '/config_db.php';
        R::setup($db['dsn'], $db['user'], $db['pass']);
        if (!R::testConnection()){
            throw new \Exception('Нет соединения с бд', 500);
        }else{
//            echo 'Соединение установлено';
        }
        R::freeze(true);
        if(DEBUG) {
            R::debug(true, 1);
        }

    }


}