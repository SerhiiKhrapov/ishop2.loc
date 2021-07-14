<?php


namespace app\models;
Use \ishop\App;
/**
 * Description of Category
 *
 * @author Sergey
 */
class Category extends AppModel{
    
    public function getIds($id) {
        $cats = App::$app->getProperty('cats');
        $ids = null;

        foreach ($cats as $k => $v) {
            if ($id == $v['parent_id']) {
                $ids .=$k . ',';
                $ids .= $this->getIds($k);
            }
        }
        return $ids;
    }
}
