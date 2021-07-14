<?php


namespace app\controllers;

Use \app\models\Category;
use \app\models\BreadCrumbs;
use \RedBeanPHP\R as R;
use ishop\libs\Pagination;
/**
 * Description of CategoryController
 *
 * @author Sergey
 */
class CategoryController extends AppController{
    
    public function viewAction() {
        
        $alias = $this->route['alias'];
        $category = R::findOne('category', 'alias = ?', [$alias]);
        if (!$category) {
            throw new \Exception('Старница не найдена', 404);
        }

        $breadcrumbs = BreadCrumbs::getBreadCrumbs($category->id);
        
        $mod_category = new Category;
        
        $ids = $mod_category->getIds($category->id);
        $ids = !$ids ? $category->id : $ids . $category->id;
        
        $perpage = \ishop\App::$app->getProperty('pagination');
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $total = R::count('product', "category_id IN ($ids)");
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();
        
        
        $products = R::find('product', "category_id IN ($ids) LIMIT $start, $perpage");
        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->set(compact('breadcrumbs' , 'products', 'pagination', 'total'));
        
    }
}
