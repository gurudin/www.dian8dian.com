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
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
}
