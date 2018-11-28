<?php

namespace common\models;

use Yii;
use yii\data\Pagination;
use GuzzleHttp\Client;

/**
 * This is the model class for table "spider_rule".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $url_data
 * @property string $rule
 * @property integer $status
 * @property string $data
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
            [['url_data', 'rule', 'data', 'title'], 'string'],
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
            'data' => '爬取结果',
            'article_rule' => '文字规则 json:{"title": ""}',
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
    public static function getAll(array $where = [])
    {
        $query = static::find()->where($where);
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count]);
        
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->orderBy('id DESC')
            ->all();
        
        foreach ($list as &$value) {
            if ($value['parent_id'] != 0) {
                $info = static::getSpiderById($value['parent_id'], ['title']);
                $value['parent_name'] = $info->title;
            }
        }
        unset($value);

        return ['list' => $list, 'page' => $pagination];
    }

    /**
     * @inheritdoc
     */
    public static function getSpiderById(int $id, array $fileds = [])
    {
        return static::find()->select($fileds)->where(['id' => $id])->one();
    }

    // -----Rule----
    /**
     * Api方式爬取数据
     *
     * @param array $target
     * @param array $data
     *
     * @example
     * $target = [
     *      ['url' => 'http://www.wheelsfactory.cn/api/getTagByPluginItemId?id=56', 'method' => 'get'],
     *      ['url' => 'http://www.wheelsfactory.cn/api/getPluginById?id=56', 'method' => 'post']
     * ];
     * $data = [
     *      'mode'  => 'get', // get|post
     *      'title' => [value => '', type => 'string'],
     *      'tags'  => [value => '', type => 'array'],
     * ];
     *
     * @return array $article
     */
    public function getApi(array $target, array $data)
    {
        $article = [];
        $result  = [];

        foreach ($target as $key => $value) {
            $parts = parse_url($value['url']);
            if (isset($parts['query'])) {
                parse_str($parts['query'], $query);
            } else {
                $query = [];
            }

            if ($value['method'] == 'get') {
                $result[$key] = $this->get($value['url'], $query);
            }

            if ($value['method'] == 'post') {
                $result[$key] = $this->post($value['url'], $query);
            }
        }
        
        foreach ($result as $ret) {
            $res = $this->getApiValue($ret, $data);
            foreach ($res as $key => $value) {
                if (!empty($value)) {
                    $article[$key] = $value;
                }
            }
        }

        return ['article' => $article, 'response' => $result];
    }

    private function getApiValue(array $response = [], array $data = [])
    {
        $result = [];

        foreach ($data as $k => $v) {
            if ($k == 'mode' || $v['value'] == '') {
                continue;
            }

            $path_arr = explode("/", $v['value']);
            
            if ($v['type'] == 'string') {
                $result[$k] = isset($response[$path_arr[0]]) ? $response[$path_arr[0]] : '';
                for ($i=1; $i<count($path_arr); $i++) {
                    $result[$k] = isset($result[$k][$path_arr[$i]]) ? $result[$k][$path_arr[$i]] : $result[$k];
                }
            }

            if ($v['type'] == 'array') {
                $tmp_arr = isset($response[$path_arr[0]]) ? $response[$path_arr[0]] : [];
                
                for ($i = 1; $i<count($path_arr)-1; $i++) {
                    $tmp_arr = isset($tmp_arr[$path_arr[$i]]) ? $tmp_arr[$path_arr[$i]] : $tmp_arr;
                }

                foreach ($tmp_arr as $value) {
                    $result[$k][] = $value[$path_arr[count($path_arr) - 1]];
                }
            }
        }

        return $result;
    }

    private function get(string $uri, array $params = [], int $timeout = 10)
    {
        if (!empty($params)) {
            $query = ['query' => $params];
        } else {
            $query = [];
        }

        $response = (new Client(['timeout' => $timeout]))->get($uri, $query);
        $body = $response->getBody();

        return json_decode($body, true);
    }

    private function post(string $uri, array $params = [], int $timeout = 10)
    {
        $res = (new Client(['timeout' => $timeout]))->post($uri, $params);
        $ret = $res->getBody();
        
        return json_decode($ret, true);
    }
    // -----Rule----
}
