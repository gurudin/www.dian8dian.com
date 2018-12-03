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
        $args  = Yii::$app->request->get();

        // Get tags.
        $tags_res = Tags::getAll(['fk_category_id' => Yii::$app->menu::routes()[1]['id']], 50);

        // Get Articlee.
        $where = [];
        if (isset($args['menu'])) {
            $where[] = ['fk_category_id' => Yii::$app->menu::one(['alias' => $args['menu']])['id']];
        }
        if (isset($args['tag'])) {
            $tag = '';
            foreach ($tags_res['list'] as $key => $value) {
                if ($value['alias'] == $args['tag']) {
                    $tag = $value['title'];
                }
            }
            $where[] = ['like', 'tags', $tag];
        }
        
        $article_res = Article::getArticleByCategoryIds($where);
        
        
        return $this->render('index', [
            'list' => $article_res['list'],
            'page' => $article_res['page'],
            'tags' => $tags_res['list']
        ]);
    }
}
