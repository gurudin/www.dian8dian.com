<?php
namespace backend\controllers;

use Yii;
use Overtrue\Pinyin\Pinyin;
use common\models\Category;
use common\models\Article;

class ArticleController extends BaseController
{
    /**
     * index
     */
    public function actionIndex()
    {
        $result = Article::getAll();

        return $this->render('index', [
            'category' => Category::getAll(),
            'result'   => $result,
        ]);
    }

    /**
     * Create & update
     */
    public function actionSave(int $id = 0)
    {
        if ($id == 0) {
            $m = (new Article)->emptyModel();
        } else {
            $m = Article::getArticleById($id);
        }

        return $this->render('save', [
            'm' => $m,
            'category' => Category::getAll(),
        ]);
    }

    /**
     * Ajax save
     */
    public function actionAjaxSave()
    {
        $data   = $this->args['data'];
        $pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');

        if ($data['id'] == '') {
            // Create
            $m = new Article;
            $m->fk_category_id = $data['fk_category_id'];
            $m->title          = $data['title'];
            $m->title_search   = $pinyin->permalink($data['title'], '') . ' ' . $pinyin->abbr($data['title']);
            $m->remark         = $data['remark'];
            $m->content        = $data['content'];
            $m->tags           = $data['tags'] ? $data['tags'] : '';
            $m->author         = $data['author'] ? $data['author'] : '';
            $m->source         = $data['source'] ? $data['source'] : '';
            $m->demo           = $data['demo'] ? $data['demo'] : '';
            $m->status         = 0;
            $m->created_at     = time();

            $result = $m->save();
        } else {
            // Update
            $result = Article::updateAll([
                'fk_category_id' => $data['fk_category_id'],
                'title'          => $data['title'],
                'title_search'   => $pinyin->permalink($data['title'], '') . ' ' . $pinyin->abbr($data['title']),
                'remark'         => $data['remark'],
                'content'        => $data['content'],
                'tags'           => $data['tags'] ? $data['tags'] : '',
                'author'         => $data['author'] ? $data['author'] : '',
                'source'         => $data['source'] ? $data['source'] : '',
                'demo'           => $data['demo'] ? $data['demo'] : '',
            ], [
                'id' => $data['id']
            ]);
        }

        return $result
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to save.'];
    }
}
