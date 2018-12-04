<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Article;
use common\models\Tags;

class ArticleController extends BaseController
{
    /**
     * Article detail
     */
    public function actionDetail(int $id, string $title)
    {
        $info = ArrayHelper::toArray(Article::getArticleById($id));
        foreach (Yii::$app->menu->category as $category) {
            if ($info['fk_category_id'] == $category->id) {
                $info['category'] = [
                    'category'    => $category->category,
                    'alias'       => $category->alias,
                    'pic'         => $category->pic,
                    'remark'      => $category->remark,
                    'search_text' => $category->search_text
                ];
            }
        }
        $info['keywords'] = $info['title_search'];
        if ($info['tags'] != '') {
            $tags         = explode(",", $info['tags']);
            $keywords     = [];
            $info['tags'] = [];

            foreach ($tags as $tag) {
                $info['tags'][] = ['title' => $tag];
            }
            $info['tags'] = Tags::extendTags($info['tags'], 'title', ['alias']);

            foreach ($info['tags'] as $key => $value) {
                $keywords[] = $value['title'];
                $keywords[] = $value['alias'];
            }
            $info['keywords'] = implode(",", $keywords);
        }
        
        return $this->render('detail', [
            'article'  => $info,
        ]);
    }
}
