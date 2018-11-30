<?php
namespace frontend\controllers;

use Yii;

class BaseController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        // Yii::$app->menu::children(Yii::$app->menu::routes()[1]['title']);
        // echo Yii::$app->menu::routes()[1]['title'];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
}
