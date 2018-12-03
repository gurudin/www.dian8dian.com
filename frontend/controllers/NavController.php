<?php
namespace frontend\controllers;

use Yii;
use common\models\Article;
use common\models\Tags;

class NavController extends BaseController
{
    /**
     * Nav index
     */
    public function actionIndex()
    {
        $article_res = Article::getArticleByCategoryIds();
        $tags_res    = Tags::getAll(['fk_category_id' => Yii::$app->menu::routes()[1]['id']], 50);
        // echo Yii::$app->request->getUrl();
        return $this->render('index', [
            'list' => $article_res['list'],
            'page' => $article_res['page'],
            'tags' => $tags_res['list']
        ]);
    }
}
