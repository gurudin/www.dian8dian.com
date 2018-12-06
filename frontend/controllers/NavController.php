<?php
namespace frontend\controllers;

use Yii;
use common\models\Article;
use common\models\Tags;
use yii\helpers\Url;

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

    /**
     * Search
     */
    public function actionSearch(string $keywords = '')
    {
        if ($keywords == '') {
            return $this->render('search');
        }

        $modeArray = ['tag', 'author'];
        $keyArray  = explode("-", $keywords);
        $fields    = [
            'id',
            'fk_category_id',
            'title',
            'title_search',
            'cover',
            'remark',
            'tags',
            'author',
            'source',
            'demo',
            'created_at'
        ];

        if (count($keyArray) > 1 && in_array($keyArray[0], $modeArray)) {
            // Tag or Author
            $mode = $keyArray[0];
            unset($keyArray[0]);
            $search_key = implode("-", $keyArray);
            
            $where = [];
            $where[] = ['status' => 1];
            if ($mode == 'tag') {
                $where[] = ['like', 'tags', $search_key];
            }
            if ($mode == 'author') {
                $where[] = ['like', 'author', $search_key];
            }

            $result = Article::getAll(
                $where,
                $fields,
                40,
                'weight,id DESC'
            );
        } else {
            // Keyword
            $search_key = $keywords;

            $where   = [];
            $where[] = ['status' => 1];
            $where[] = [
                'or',
                ['like', 'title', $search_key],
                ['like', 'title_search', $search_key],
                ['like', 'remark', $search_key],
                ['like', 'content', $search_key]
            ];
            $result = Article::getAll($where, $fields, 40, 'weight,id DESC');
        }

        foreach ($result['list'] as $key => $item) {
            if ($item['tags'] != '') {
                $item['tags'] = explode(",", $item['tags']);
            }
        }

        return $this->render('search', [
            'list'     => $result['list'],
            'page'     => $result['page'],
            'keywords' => $search_key,
        ]);
    }
}
