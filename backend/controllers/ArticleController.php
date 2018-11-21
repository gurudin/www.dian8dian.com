<?php
namespace backend\controllers;

use Yii;
use Overtrue\Pinyin\Pinyin;
use common\models\Category;
use common\models\Article;

class ArticleController extends BaseController
{
    /**
     * index
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'category' => Category::getAll(),
        ]);
    }

    /**
     * Create & update
     */
    public function actionSave(int $id = 0)
    {
        if ($id == 0) {
            $m = (new Article)->emptyModel();
        } else {
            
        }

        return $this->render('save', [
            'm' => $m,
            'category' => Category::getAll(),
        ]);
    }
}
