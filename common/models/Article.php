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
            [['fk_category_id', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['title', 'title_search', 'remark', 'tags', 'source', 'demo'], 'string', 'max' => 255],
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
            $m[$k] = $k == 'status' ? 0 : '';
        }
        
        return $m;
    }

    /**
     * Get all
     */
    public static function getAll(array $where = [])
    {
        $query = static::find()->where($where);
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count]);
        
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
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
}
