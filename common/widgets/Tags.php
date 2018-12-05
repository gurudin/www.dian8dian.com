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

    /**
     * Mode
     *
     * @var string
     *
     * @example nav|all|search
     */
    public $mode;

    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
        switch ($this->mode) {
            case 'nav':
                return $this->nav();
                break;
            case 'search':
                return $this->search();
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * Nav
     */
    private function nav()
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
                $result .= Html::tag(
                    'span',
                    $item->title,
                    [
                        'class' => 'btn text-muted active',
                        'title' => $item->title,
                        'alt'   => $item->title
                    ]
                );
            } else {
                $params[0]     = $url_arr['path'];
                $params['tag'] = $item['alias'];

                $result .= Html::a(
                    $item->title,
                    Url::to($params),
                    [
                        'class' => 'btn btn-link text-info',
                        'title' => $item->title,
                        'alt'   => $item->title
                    ]
                );
            }
        }

        return Html::decode($result);
    }
}
