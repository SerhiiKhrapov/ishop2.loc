<?php


namespace app\controllers;


use app\models\BreadCrumbs;
use app\models\Product;
use ishop\App;
use RedBeanPHP\R;

class ProductController extends AppController
{
    public function viewAction(){
        $this->setMeta(App::$app->getProperty('shop_name'), 'desc', 'key');
        $alias = $this->route['alias'];
        $product = R::findOne('product', "alias=? AND status='1'", [$alias]);

        if(empty($product)) {
            echo new \Exception('Страница не найдена', 404);
        }
        // хлебные крошки
        $breadcrumps = BreadCrumbs::getBreadCrumbs($product->category_id, $product->title);

        // связанніе товарі
        $related = R::getAll("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$product->id]);

        //запись в куки запрошенного товара
        $p_model = new Product();
        $recentlyViewed = $p_model->setRecentlyViewed($product->id);

        //просмотренніе товарі
        $r_viewed = $p_model->getRecentlyViewed();
        $recentlyViewed = null;
        if($r_viewed) {
            $recentlyViewed = R::find('product', 'id IN (' . R::genSlots($r_viewed) .') LIMIT 3', $r_viewed);
        }


        //галерея
        $gallery = R::findAll('gallery', "product_id=?", [$product->id]);

        //модификации
        $mods = R::findAll('modification', 'product_id=?', [$product->id]);


        $this->set(compact('product', 'related', 'gallery', 'recentlyViewed', 'breadcrumps', 'mods'));
    }

}