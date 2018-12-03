<?php
namespace frontend\controllers;

use Yii;

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
