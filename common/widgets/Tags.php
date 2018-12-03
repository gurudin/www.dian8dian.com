<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class Tags extends Widget
{
    /**
     * Article list
     *
     * @var Array
     */
    public $item;

    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
        $url_arr = parse_url(Yii::$app->request->url);
        if (isset($url_arr['query'])) {
            parse_str($url_arr['query'], $params);
        } else {
            $params = [];
        }
        
        $result = '';
        foreach ($this->item as $inx => $item) {
            if ($item->alias == Yii::$app->request->get('tag', '')) {
                $result .= Html::tag('span', $item->title, ['class' => 'btn text-muted active']);
            } else {
                $params[0]     = $url_arr['path'];
                $params['tag'] = $item['alias'];

                $result .= Html::a(
                    $item->title,
                    // Url::to(['nav/' . $item['alias']]),
                    Url::to($params),
                    ['class' => 'btn btn-link text-info']
                );
            }
        }

        return Html::decode($result);
    }
}
