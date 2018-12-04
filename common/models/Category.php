<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $category
 * @property string $remark
 * @property string $search_text
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'enabled', 'weight'], 'integer'],
            [['category'], 'required'],
            [['category'], 'string', 'max' => 50],
            [['alias'], 'string', 'max' => 50],
            [['remark', 'search_text', 'pic'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '父类ID',
            'category' => '类别名称',
            'alias' => '别名',
            'weight' => '权重',
            'pic' => '类别图片',
            'remark' => '描述',
            'search_text' => '搜索拼音',
            'enabled' => '0:不启动 1:启用',
        ];
    }

    /**
     * @inheritdoc
     */
    public function emptyModel()
    {
        $m = [];
        foreach ($this->attributeLabels() as $k => $v) {
            $m[$k] = $k == 'parent_id' || $k == 'enabled' || $k == 'weight'
                ? 0
                : '';
        }
        
        return $m;
    }

    /**
     * get Category
     */
    public static function getCategoryByParentId(int $parent_id = 0)
    {
        return static::find()->where(['parent_id' => $parent_id])->all();
    }

    /**
     * get All
     */
    public static function getAll(array $where = [])
    {
        return static::find()->where($where)->orderBy('weight desc')->all();
    }

    /**
     * Get category by id
     */
    public static function getCategoryById(int $id)
    {
        return static::find()->where(['id' => $id])->one();
    }

    /**
     * 扩展类别表字段
     *
     * @param array $oriArray 需扩展数组
     * @param string $key 查询字段
     * @param array $fields 扩展字段
     *
     * @return array
     */
    public static function extendCategory(array $oriArray, string $key = 'fk_category_id', array $fields = [])
    {
        $oriArray = ArrayHelper::toArray($oriArray);
        $columns  = $fields;
        if (!in_array('id', $columns)) {
            array_push($columns, 'id');
        }

        $category_ids = array_map('intval', array_values(array_filter(array_column($oriArray, $key))));
        $category_ids = array_unique($category_ids);

        $queryResp = static::find()->select($columns)->where(['id' => $category_ids])->asArray()->all();
        foreach ($oriArray as &$item) {
            foreach ($queryResp as $res) {
                if ($res['id'] == $item[$key]) {
                    foreach ($fields as $field) {
                        $item[$field] = $res[$field];
                    }
                }
            }
        }
        unset($item);

        return $oriArray;
    }
}
