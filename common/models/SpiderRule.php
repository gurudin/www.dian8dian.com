<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "spider_rule".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $url_data
 * @property string $rule
 * @property integer $status
 * @property string $dara
 */
class SpiderRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spider_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status'], 'integer'],
            [['url_data', 'rule', 'dara', 'title'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '名称',
            'parent_id' => '父类id',
            'url_data' => 'url规则 json:[{"url":"http://example.com","method":"get"},{"url":"http://example.com","method":"get"}]',
            'rule' => '规则 json:{"mode":"api","title":{"value":"","type":"string"},"tags":{"value":"","type":"array"}}',
            'status' => '状态 1:未爬取 2:已爬取添加到文章 3:已爬取添加到规则 4:规则错误',
            'dara' => '爬取结果',
        ];
    }

    /**
     * @inheritdoc
     */
    public function emptyModel()
    {
        $m = [];
        foreach ($this->attributeLabels() as $k => $v) {
            switch ($k) {
                case 'status':
                    $m[$k] = 1;
                    break;
                case 'parent_id':
                    $m[$k] = 0;
                    break;
                default:
                    $m[$k] = '';
                    break;
            }
        }
        
        return $m;
    }

    /**
     * @inheritdoc
     */
    public static function getAll()
    {
        return static::find()->all();
    }
}
