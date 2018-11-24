<?php
namespace frontend\controllers;

use Yii;

class NavController extends BaseController
{
    /**
     * Nav index
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
