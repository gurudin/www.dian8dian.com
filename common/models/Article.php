<?php

namespace common\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property integer $fk_category_id
 * @property string $title
 * @property string $title_search
 * @property string $remark
 * @property string $content
 * @property string $tags
 * @property string $author
 * @property string $source
 * @property string $demo
 * @property integer $status
 * @property integer $created_at
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_category_id', 'title', 'title_search', 'created_at'], 'required'],
            [['fk_category_id', 'created_at', 'weight'], 'integer'],
            [['content'], 'string'],
            [['title', 'title_search', 'remark', 'tags', 'source', 'demo', 'cover'], 'string', 'max' => 255],
            [['author'], 'string', 'max' => 50],
            [['status'], 'integer', 'max' => 1],
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
            'title_search' => '标题搜索拼音',
            'weight' => '权重 从大到小',
            'cover' => '封面图片',
            'remark' => '简介',
            'content' => '内容',
            'tags' => '标签',
            'author' => '作者',
            'source' => '来源地址',
            'demo' => 'demo地址',
            'status' => '0:下线 1:上线 2:待审核',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @inheritdoc
     */
    public function emptyModel()
    {
        $m = [];
        foreach ($this->attributeLabels() as $k => $v) {
            $m[$k] = $k == 'status' || $k == 'weight' ? 0 : '';
        }
        
        return $m;
    }

    /**
     * Get all
     *
     * @param array $where
     * @param array $fields
     *
     * @example
     * ```php
     * $where = ['status' => 1]
     * $fields = ['id', 'title']
     * ```
     *
     * @return array
     */
    public static function getAll(array $where = [], array $fields = [], int $defaultPageSize = 20)
    {
        $query = static::find()->where($where);
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => $defaultPageSize]);
        
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->select($fields)
            ->orderBy('id DESC')
            ->all();

        return ['list' => $list, 'page' => $pagination];
    }

    /**
     * Get article by id.
     */
    public static function getArticleById(int $id = 0)
    {
        return static::find()->where(['id' => $id])->one();
    }

    /**
     * Get article by category id.
     *
     * @param array $where
     *
     * @return array ['list' => list, 'page' => page]
     */
    public static function getArticleByCategoryIds(array $where = [])
    {
        $query = static::find()->where(['status' => 1]);
        foreach ($where as $key => $value) {
            $query = $query->andWhere($value);
        }
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => 20]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->select(['id','fk_category_id','title','title_search','cover','remark','tags','author','created_at'])
            ->orderBy('id DESC')
            ->all();

        foreach ($list as $key => $item) {
            if ($item['tags'] != '') {
                $item['tags'] = explode(",", $item['tags']);
            }
        }

        return ['list' => $list, 'page' => $pagination];
    }
}
