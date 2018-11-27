<?php
namespace backend\controllers;

use Yii;
use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SpiderController extends BaseController
{
    /**
     * Spider
     */
    public function actionSpider()
    {
        return $this->render('spider');
    }

    public function actionAjaxGetData()
    {
        $target  = $this->args['target'];
        $data    = $this->args['data'];
        $article = [];

        // Mode api
        if ($data['mode'] == 'api') {
            $article = $this->getApi($target, $data);
        }

        // Mode web
        if ($data['mode'] == 'web') {

        }

        return ['status' => true, 'msg' => 'success', 'data' => $article];
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

        return $article;
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
