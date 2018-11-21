<?php

namespace common\models;

use Yii;

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
            [['parent_id'], 'integer'],
            [['category'], 'required'],
            [['category'], 'string', 'max' => 50],
            [['remark', 'search_text'], 'string', 'max' => 255],
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
            'pic' => '类别图片',
            'remark' => '描述',
            'search_text' => '搜索拼音',
        ];
    }

    /**
     * @inheritdoc
     */
    public function emptyModel()
    {
        $m = [];
        foreach ($this->attributeLabels() as $k => $v) {
            $m[$k] = $k == 'parent_id' ? 0 : '';
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
    public static function getAll()
    {
        return static::find()->orderBy('id desc')->all();
    }

    /**
     * Get category by id
     */
    public static function getCategoryById(int $id)
    {
        return static::find()->where(['id' => $id])->one();
    }
}
