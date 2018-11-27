<?php
namespace backend\controllers;

use Yii;
use GuzzleHttp\Client;
use common\models\SpiderRule;
use yii\helpers\Json;

class SpiderController extends BaseController
{
    /**
     * list
     */
    public function actionIndex()
    {
        $result = SpiderRule::getAll([]);

        return $this->render('index', ['list' => $result['list'], 'page' => $result['page']]);
    }

    /**
     * Set spider
     */
    public function actionSpider()
    {
        if (Yii::$app->request->get('id', '') == '') {
            $m = (new SpiderRule)->emptyModel();
            $m['rule'] = ['mode' => 'api'];
        } else {
            $m = SpiderRule::getSpiderById(Yii::$app->request->get('id'));
            $m->url_data = Json::decode($m->url_data, true);
            $m->rule     = Json::decode($m->rule, true);
            $m->data     = Json::decode($m->data, true);
        }

        return $this->render('spider', ['m' => $m]);
    }

    /**
     * Ajax add
     */
    public function actionAjaxSave()
    {
        $id     = $this->args['id'];
        $data   = $this->args['data'];
        $spider = $this->args['m'];
        $target = isset($this->args['target']) ? $this->args['target'] : '';

        if ($target == '') {
            return ['status' => false, 'msg' => 'URL未设置.'];
        }

        if ($id == '') {
            // Create
            $m = new SpiderRule;
            $m->title     = $this->args['title'];
            $m->parent_id = $this->args['parent_id'] ? $this->args['parent_id'] : 0;
            $m->url_data  = json_encode($target, JSON_UNESCAPED_UNICODE);
            $m->rule      = json_encode($spider, JSON_UNESCAPED_UNICODE);
            if ($data != '') {
                $m->data   = json_encode($data, JSON_UNESCAPED_UNICODE);
                $m->status = 3;
            }
            $result = $m->save();
        } else {
            // Update
            $update = [];
            $update['title']     = $this->args['title'];
            $update['parent_id'] = $this->args['parent_id'] ? $this->args['parent_id'] : 0;
            $update['url_data']  = json_encode($target, JSON_UNESCAPED_UNICODE);
            $update['rule']      = json_encode($spider, JSON_UNESCAPED_UNICODE);
            if ($data != '') {
                $update['data']   = json_encode($data, JSON_UNESCAPED_UNICODE);
                $update['status'] = 3;
            } else {
                $update['data']   = '';
                $update['status'] = 1;
            }
            $result = SpiderRule::updateAll($update, [
                'id' => $id
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
        return SpiderRule::deleteAll(['id' => $this->args['id']])
            ? ['status' => true, 'msg' => 'success']
            : ['status' => false, 'msg' => 'Failed to remove.'];
    }

    /**
     * Ajax get for data.
     */
    public function actionAjaxGetData()
    {
        $target  = $this->args['target'];
        $data    = $this->args['data'];
        $result  = [];

        // Mode api
        if ($data['mode'] == 'api') {
            $result = $this->getApi($target, $data);
        }

        // Mode web
        if ($data['mode'] == 'web') {

        }

        return ['status' => true, 'msg' => 'success', 'data' => $result['article'], 'response' => $result['response']];
    }

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
    private function getApi(array $target, array $data)
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
}
