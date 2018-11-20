<?php
namespace backend\controllers;

use Yii;
use common\models\Category;

class CategoryController extends BaseController
{
    /**
     * Create category
     */
    public function actionCreate(int $category_id = 0)
    {
        $category_list = Category::getCategoryByParentId();

        if ($category_id == 0) {
            $m = (new Category)->emptyModel();
        } else {

        }
        
        return $this->render('create', [
            'm' => $m,
            'category_list' => $category_list,
        ]);
    }
}
