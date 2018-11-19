<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // print_r(Yii::$app->params['adminEmail']);
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'blank';
        return $this->render('login');
    }

    public function actionError()
    {
        return $this->render('error', ['name' => 'Error', 'message' => 'Message']);
    }
}
