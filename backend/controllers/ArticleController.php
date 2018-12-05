<?php
namespace backend\controllers;

use Yii;
use Overtrue\Pinyin\Pinyin;
use common\models\Category;
use common\models\Article;
use common\models\SpiderRule;
use common\models\Tags;

class ArticleController extends BaseController
{
    /**
     * index
     */
    public function actionIndex()
    {
        $where  = [];
        $search = [
            'fk_category_id' => Yii::$app->request->get('fk_category_id', ''),
            'status' => Yii::$app->request->get('status', ''),
        ];

        foreach ($search as $key => $value) {
            if ($value != '') {
                $where[$key] = $value;
            }
        }

        $result = Article::getAll([$where]);

        return $this->render('index', [
            'category' => Category::getAll(),
            'result'   => $result,
            'search'   => $search,
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

        // rule
        if (Yii::$app->request->get('rule_id', '') != '') {
            $rule_info = SpiderRule::getSpiderById(Yii::$app->request->get('rule_id'), ['data']);
            if ($rule_info['data'] != '') {
                $rule = json_decode($rule_info['data'], true);

                foreach ($m as $key => $value) {
                    if ($key == 'tags') {
                        $m[$key] = isset($rule[$key]) ? implode(",", $rule[$key]) : $m[$key];
                    } else {
                        $m[$key] = isset($rule[$key]) ? $rule[$key] : $m[$key];
                    }
                }

                $m['rule'] = Yii::$app->request->get('rule_id');
            }
        }

        return $this->render('save', [
            'm' => $m,
            'category' => Category::getAll(),
        ]);
    }

    /**
     * Ajax get tags.
     */
    public function actionAjaxGetTags()
    {
        if ($this->args['fk_category_id'] == '') {
            return ['status' => true, 'msg' => 'success', 'data' => []];
        }

        $res_tags = Tags::getAll(['fk_category_id' => $this->args['fk_category_id']], 500);

        return [
            'status' => true,
            'msg'    => 'success',
            'data'   => $res_tags['list']
        ];
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
            $m->cover          = $data['cover'] ? $data['cover'] : '';
            $m->remark         = $data['remark'];
            $m->content        = $data['content'];
            $m->weight         = $data['weight'] ? $data['weight'] : 0;
            $m->tags           = $data['tags'] ? $data['tags'] : '';
            $m->author         = $data['author'] ? $data['author'] : '';
            $m->source         = $data['source'] ? $data['source'] : '';
            $m->demo           = $data['demo'] ? $data['demo'] : '';
            $m->status         = 0;
            $m->created_at     = time();

            // Rule
            if (isset($data['rule'])) {
                SpiderRule::updateAll(['status' => 2], ['id' => $data['rule']]);
            }

            $result = $m->save();
        } else {
            // Update
            $result = Article::updateAll([
                'fk_category_id' => $data['fk_category_id'],
                'title'          => $data['title'],
                'title_search'   => $pinyin->permalink($data['title'], '') . ' ' . $pinyin->abbr($data['title']),
                'cover'          => $data['cover'] ? $data['cover'] : '',
                'remark'         => $data['remark'],
                'content'        => $data['content'],
                'weight'         => $data['weight'] ? $data['weight'] : 0,
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

    /**
     * Edit status
     *
     * @param int $id
     * @param int $status
     *
     * @return mixed
     */
    public function actionAjaxEditStatus()
    {
        $result = Article::updateAll([
            'status' => $this->args['status']
        ], [
            'id' => $this->args['id']
        ]);

        return $result
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to edit.'];
    }

    /**
     * Remove
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionAjaxRemove()
    {
        return Article::deleteAll(['id' => $this->args['id']])
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to remove.'];
    }
}
