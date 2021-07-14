<?php


namespace app\controllers;


use ishop\App;
use ishop\Cache;
use ishop\Db;
use RedBeanPHP\R;


class MainController extends AppController
{
    public function indexAction(){
        $this->setMeta(App::$app->getProperty('shop_name'), 'desc', 'key');
        $brands = R::findAll('brand', 'LIMIT 3');
        $hits = R::findAll('product', "hit='1' LIMIT 8");
        $this->set(compact('brands', 'hits'));

    }
}