<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class BaseController extends \yii\web\Controller
{
    protected $args = [];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['login', 'error'],
                        'roles' => ['?'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (strstr($action->id, 'ajax')) {
            if (!Yii::$app->request->isAjax) {
                return;
            }
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $this->args = Yii::$app->request->post();
        }
        
        return parent::beforeAction($action);
    }

    /**
     * Rand string
     *
     * @param int $length
     * @param string $case lc(小写)|uc(大写)
     *
     * @return string
     */
    protected function randstr(int $length = 16, string $case = 'uc')
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $case == 'uc' ? strtoupper($randomString) : strtolower($randomString);
    }
}
