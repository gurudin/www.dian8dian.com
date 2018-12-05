<?php

namespace common\models;

use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

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
            [['fk_category_id', 'recommend'], 'integer'],
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
            'recommend' => '是否推荐'
        ];
    }

    /**
     * @inheritdoc
     */
    public function emptyModel()
    {
        $m = [];
        foreach ($this->attributeLabels() as $k => $v) {
            if ($k == 'recommend') {
                $m[$k] = 0;
            } else {
                $m[$k] = '';
            }
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

    /**
     * 扩展类别表字段
     *
     * @param array $oriArray 需扩展数组
     * @param string $key 查询字段
     * @param array $fields 扩展字段
     *
     * @return array
     */
    public static function extendTags(array $oriArray, string $key = 'title', array $fields = [])
    {
        $oriArray = ArrayHelper::toArray($oriArray);
        $columns  = $fields;

        if (!in_array('title', $columns)) {
            array_push($columns, 'title');
        }

        $titles    = array_unique(array_values(array_filter(array_column($oriArray, $key))));
        $queryResp = static::find()->select($columns)->where([$key => $titles])->asArray()->all();
        foreach ($oriArray as &$item) {
            foreach ($queryResp as $res) {
                if ($res['title'] == $item[$key]) {
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
