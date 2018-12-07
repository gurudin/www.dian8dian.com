<?php
namespace backend\controllers;

use Yii;
use Overtrue\Pinyin\Pinyin;
use common\models\Category;
use common\models\Tags;

class TagsController extends BaseController
{
    /**
     * index
     */
    public function actionIndex()
    {
        $result = Tags::getAll([], 500);
        $list   = Category::extendCategory($result['list'], 'fk_category_id', ['category']);

        return $this->render('index', [
            'm'        => (new Tags)->emptyModel(),
            'list'     => $list,
            'page'     => $result['page'],
            'category' => Category::getCategoryByParentId(),
        ]);
    }

    /**
     * Ajax recommed.
     */
    public function actionAjaxRecommend()
    {
        return Tags::updateAll(['recommend' => $this->args['recommend']], ['id' => $this->args['id']])
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to recommend.'];
    }

    /**
     * Ajax save.
     */
    public function actionAjaxSave()
    {
        $pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');

        if ($this->args['id'] == '') {
            // Create
            $m = new Tags;
            $m->fk_category_id = $this->args['fk_category_id'];
            $m->title          = $this->args['title'];
            $m->alias          = $pinyin->permalink($this->args['title'], '');
            $m->recommend      = 0;

            $result = $m->save();
        } else {
            // Update
            $result = Tags::updateAll([
                'fk_category_id' => $this->args['fk_category_id'],
                'title'          => $this->args['title'],
                'alias'          => $pinyin->permalink($this->args['title'], ''),
                'recommend'      => 0,
            ], [
                'id' => $this->args['id']
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
        return Tags::deleteAll(['id' => $this->args['id']])
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to remove.'];
    }
}
