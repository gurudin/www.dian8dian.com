<?php
namespace backend\controllers;

use Yii;
use yii\helpers\Json;
use common\models\SpiderRule;
use common\models\Category;
use common\models\Article;

class SpiderController extends BaseController
{
    /**
     * list
     */
    public function actionIndex()
    {
        $result = SpiderRule::getAll([]);

        return $this->render('index', ['list' => $result['list'], 'page' => $result['page']]);
    }

    /**
     * Set spider
     */
    public function actionSpider()
    {
        $id      = Yii::$app->request->get('id', '');
        $copy_id = Yii::$app->request->get('copy_id', '');

        if ($id == '' && $copy_id == '') {
            // Create
            $m = (new SpiderRule)->emptyModel();
            $m['rule'] = ['mode' => 'api'];
        } else {
            $m = SpiderRule::getSpiderById($id == '' ? $copy_id : $id);
            $m->url_data     = Json::decode($m->url_data, true);
            $m->rule         = Json::decode($m->rule, true);
            $m->data         = Json::decode($m->data, true);
            $m->article_rule = Json::decode($m->article_rule, true);

            if ($copy_id != '') {
                // Copy
                $m->id = '';
            }
        }

        return $this->render('spider', ['m' => $m]);
    }

    /**
     * Create
     */
    public function actionCreate()
    {
        $info = SpiderRule::getSpiderById(Yii::$app->request->get('id'));
        
        return $this->render('create', [
            'message'  => $info->data,
            'category' => Category::getAll(),
            'm'        => $m = (new Article)->emptyModel(),
        ]);
    }

    /**
     * Ajax add
     */
    public function actionAjaxSave()
    {
        $id           = $this->args['id'];
        $data         = $this->args['data'];
        $spider       = $this->args['m'];
        $article_rule = $this->args['article_rule'];
        $target       = isset($this->args['target']) ? $this->args['target'] : '';

        if ($target == '') {
            return ['status' => false, 'msg' => 'URL未设置.'];
        }

        if ($id == '') {
            // Create
            $m = new SpiderRule;
            $m->title        = $this->args['title'];
            $m->parent_id    = isset($this->args['parent_id']) ? $this->args['parent_id'] : 0;
            $m->url_data     = json_encode($target, JSON_UNESCAPED_UNICODE);
            $m->rule         = json_encode($spider, JSON_UNESCAPED_UNICODE);
            $m->article_rule = $article_rule != '' ? json_encode($article_rule, JSON_UNESCAPED_UNICODE) : '';
            if ($data != '') {
                $m->data   = json_encode($data, JSON_UNESCAPED_UNICODE);
                $m->status = 3;
            }
            $result = $m->save();
        } else {
            // Update
            $update = [];
            $update['title']        = $this->args['title'];
            $update['parent_id']    = isset($this->args['parent_id']) ? $this->args['parent_id'] : 0;
            $update['url_data']     = json_encode($target, JSON_UNESCAPED_UNICODE);
            $update['rule']         = json_encode($spider, JSON_UNESCAPED_UNICODE);
            $update['article_rule'] = $article_rule != '' ? json_encode($article_rule, JSON_UNESCAPED_UNICODE) : '';
            if ($data != '') {
                $update['data']   = json_encode($data, JSON_UNESCAPED_UNICODE);
                $update['status'] = 3;
            } else {
                $update['data']   = '';
                $update['status'] = 1;
            }
            $result = SpiderRule::updateAll($update, [
                'id' => $id
            ]);
        }

        return $result
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to save.'];
    }

    /**
     * Ajax remove.
     */
    public function actionAjaxRemove()
    {
        return SpiderRule::deleteAll(['id' => $this->args['id']])
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to remove.'];
    }

    /**
     * Ajax get for data.
     */
    public function actionAjaxGetData()
    {
        $target  = $this->args['target'];
        $data    = $this->args['data'];
        $result  = [];

        // Mode api
        if ($data['mode'] == 'api') {
            $result =(new SpiderRule)->getApi($target, $data);
        }

        // Mode web
        if ($data['mode'] == 'web') {

        }

        return ['status' => true, 'msg' => 'success', 'data' => $result['article'], 'response' => $result['response']];
    }
}
