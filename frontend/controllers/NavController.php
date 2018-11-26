<?php
namespace frontend\controllers;

use Yii;
use common\models\Article;

class NavController extends BaseController
{
    /**
     * Nav index
     */
    public function actionIndex()
    {
        $res = Article::getArticleByCategoryIds();
        
        return $this->render('index', ['list' => $res]);
    }
}
