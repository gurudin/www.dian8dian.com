<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use common\models\SpiderRule;
use common\models\Article;

/**
 * Batch spider.
 */
class SpiderController extends Controller
{
    public $source = '';
    public $spider_id = '';
    
    public function options($actionID)
    {
        return ['source', 'spider_id'];
    }
    
    public function optionAliases()
    {
        return ['s' => 'source', 'id' => 'spider_id'];
    }
    
    /**
     * Example: php yii spider/index -s=wheel -id=3
     */
    public function actionIndex()
    {
        switch ($this->source) {
            case 'wheel':
                $this->actionWheel();
                break;
            
            default:
                Console::output(Console::ansiFormat("No method found", [Console::FG_RED]));
                break;
        }
    }

    /**
     * Source: http://www.wheelsfactory.cn
     */
    public function actionWheel()
    {
        if ($this->spider_id == '') {
            Console::output(Console::ansiFormat("Empty id.", [Console::FG_YELLOW]));
            return;
        }

        $target = [
            ['url' => 'http://www.wheelsfactory.cn//api/getPluginById?id=', 'method' => 'get'],
            ['url' => 'http://www.wheelsfactory.cn//api/getTagByPluginItemId?id=', 'method' => 'get']
        ];

        $rule = [
            "mode" => "api",
            "title" => [
                "value" => "result/0/name",
                "type" => "string"
            ],
            "remark" => [
                "value" => "result/0/fullDescription",
                "type" => "string"
            ],
            "tags" => [
                "value" => "pluginTagList/content",
                "type" => "array"
            ],
            "author" => [
                "value" => "result/0/author",
                "type" => "string"
            ],
            "demo" => [
                "value" => "result/0/demoUrl",
                "type" => "string"
            ],
            "source" => [
                "value" => "result/0/homepageUrl",
                "type" => "string"
            ]
        ];

        $info = SpiderRule::getSpiderById($this->spider_id, ['data']);
        $spiderData = json_decode($info->data, true);

        Console::output(Console::ansiFormat("Totle: " . count($spiderData['id']), [Console::FG_GREY]));
        $success = 0;
        $repeat  = 0;

        foreach ($spiderData['id'] as $key => $id) {
            $count = SpiderRule::find()->where(['title' => $spiderData['title'][$key]])->count();
            if ($count > 0) {
                $repeat++;
                continue;
            }

            // Set target
            $tmp_target = $target;
            $tmp_target[0]['url'] .= $id;
            $tmp_target[1]['url'] .= $id;

            // Get for data.
            $result     = (new SpiderRule)->getApi($tmp_target, $rule);
            $retArtcile = $result['article'];

            // Add article.
            $m = new SpiderRule;
            $m->title        = $spiderData['title'][$key];
            $m->parent_id    = $this->spider_id;
            $m->url_data     = json_encode($target, JSON_UNESCAPED_UNICODE);
            $m->rule         = json_encode($rule, JSON_UNESCAPED_UNICODE);
            $m->article_rule = '';
            if (empty($retArtcile)) {
                $m->data   = '';
                $m->status = 1;
            } else {
                $m->data   = json_encode($retArtcile, JSON_UNESCAPED_UNICODE);
                $m->status = 3;
            }

            if ($m->save()) {
                $success++;
                Console::output(Console::ansiFormat($spiderData['title'][$key], [Console::FG_GREEN]));
            }
        }
        
        Console::output('');
        Console::output('Success: ' . Console::ansiFormat($success, [Console::FG_GREEN]));
        Console::output('Repeat: ' . Console::ansiFormat($repeat, [Console::FG_GREEN]));
    }
}
