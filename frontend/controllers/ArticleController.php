<?php
namespace frontend\controllers;

use Yii;
use common\models\Article;

class ArticleController extends BaseController
{
    /**
     * Article detail
     */
    public function actionDetail()
    {
        return $this->render('detail');
    }
}
