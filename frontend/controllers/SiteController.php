<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use common\models\Category;
use common\models\Article;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', ['article' => \common\models\Article::getArticleByCategoryIds()]);
    }

    /**
     * Sitemap
     *
     * @return xml
     */
    public function actionSitemap()
    {
        $lastmod = date('Y-m-d');
        $maps = [];

        // Home
        $maps[] =[
            'loc'        => Yii::$app->request->hostInfo,
            'lastmod'    => $lastmod,
            'changefreq' => 'daily',
            'priority'   => '1.0'
        ];

        // Category
        $category_item = Category::getAll(['enabled' => 1]);
        foreach ($category_item as $key => $category) {
            $maps[] = [
                'loc'        => Url::to(["nav/{$category->alias}"], true),
                'lastmod'    => $lastmod,
                'changefreq' => 'daily',
                'priority'   => 0.9
            ];
        }

        // Article
        $article_item = Article::getAll(['status' => 1], ['id', 'title'], 500);
        foreach ($article_item['list'] as $key => $article) {
            $maps[] = [
                'loc'        =>  Url::to(["article/{$article->id}"], true),
                'lastmod'    => $lastmod,
                'changefreq' => 'daily',
                'priority'   => 0.8
            ];
        }


        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_XML,
            'formatters' => [
                \yii\web\Response::FORMAT_XML => [
                    'class' => 'yii\web\XmlResponseFormatter',
                    'rootTag' => 'urlset',
                    'itemTag' => 'url',
                ],
            ],
            'data' => $maps,
        ]);
    }
}
