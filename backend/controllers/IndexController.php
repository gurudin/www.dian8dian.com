<?php
namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use common\models\Category;
use common\models\Article;

class IndexController extends BaseController
{
    /**
     * Sitemap
     */
    public function actionSitemap()
    {
        return $this->render('sitemap', [
        ]);
    }

    /**
     * Ajax sitemap
     */
    public function actionAjaxSitemap()
    {
        return ['status' => true, 'msg' => 'success', 'data' => $this->getMap()];
    }

    /**
     * Ajax generate
     */
    public function actionAjaxGenerate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        $result = $this->getMap();
        $maps   = [];
        foreach ($result as $key => $map) {
            $tmp = [
                'loc'        => $map['url'],
                'lastmod'    => date('Y-m-d'),
                'changefreq' => 'daily',
                'priority'   => $map['priority']
            ];

            $maps[] = $tmp;
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

    private function getMap()
    {
        $home = Yii::$app->params['home'];
        $maps = [];
        
        // Home
        $maps[] =['url' => $home, 'title' => '首页', 'priority' => 1.0];

        // Category
        $category_item = Category::getAll(['enabled' => 1]);
        foreach ($category_item as $key => $category) {
            $maps[] = [
                'url'      => "{$home}/nav/{$category->alias}",
                'title'    => $category->category,
                'priority' => 0.9
            ];
        }

        // Article
        $article_item = Article::getAll([['status' => 1]], ['id', 'title'], 500);
        foreach ($article_item['list'] as $key => $article) {
            $maps[] = [
                'url'      => "{$home}/article/{$article->id}",
                'title'    => $article->title,
                'priority' => 0.8
            ];
        }

        return $maps;
    }
}
