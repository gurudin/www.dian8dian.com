<?php

namespace common\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property integer $fk_category_id
 * @property string $title
 * @property string $alias
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_category_id', 'title', 'alias'], 'required'],
            [['fk_category_id'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['alias'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_category_id' => '类别外键',
            'title' => '标题',
            'alias' => '别名',
        ];
    }

    /**
     * @inheritdoc
     */
    public function emptyModel()
    {
        $m = [];
        foreach ($this->attributeLabels() as $k => $v) {
            $m[$k] = '';
        }
        
        return $m;
    }

    /**
     * Get all tags
     */
    public static function getAll(array $where = [], int $defaultPageSize = 20)
    {
        $query = static::find()->where($where);
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => $defaultPageSize]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('id DESC')
            ->all();

        return ['list' => $list, 'page' => $pagination];
    }
}
