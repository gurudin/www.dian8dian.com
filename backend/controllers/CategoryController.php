<?php
namespace backend\controllers;

use Yii;
use common\models\Category;
use Overtrue\Pinyin\Pinyin;

class CategoryController extends BaseController
{
    /**
     * List
     */
    public function actionList()
    {
        $list = Category::getAll();
        
        return $this->render('list', [
            'list' => $list,
        ]);
    }

    /**
     * Save category
     */
    public function actionSave(int $id = 0)
    {
        $category_list = Category::getCategoryByParentId(0);
        
        if ($id == 0) {
            $m = (new Category)->emptyModel();
        } else {
            $m = Category::getCategoryById($id);
        }
        
        return $this->render('save', [
            'm' => $m,
            'category_list' => $category_list,
        ]);
    }

    /**
     * Save category
     *
     * @param data model
     */
    public function actionAjaxSave()
    {
        $data   = $this->args['data'];
        $pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');

        if ($data['id'] == '') {
            // Create
            $m = new Category;
            $m->parent_id   = $data['parent_id'];
            $m->category    = $data['category'];
            $m->remark      = $data['remark'];
            $m->search_text = $pinyin->permalink($data['category'], '') . ' ' . $pinyin->abbr($data['category']);

            $result = $m->save();
        } else {
            // Update
            $result = Category::updateAll([
                'parent_id'   => $data['parent_id'],
                'category'    => $data['category'],
                'remark'      => $data['remark'],
                'search_text' => $pinyin->permalink($data['category'], '') . ' ' . $pinyin->abbr($data['category']),
            ], [
                'id' => $data['id']
            ]);
        }

        return $result
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to save.'];
    }

    /**
     * Remove category
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionAjaxRemove()
    {
        if (Category::getCategoryByParentId($this->args['id'])) {
            return ['status' => false, 'msg' => '请先删除子类！'];
        }

        return Category::deleteAll(['id' => $this->args['id']])
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to delete.'];
    }
}
