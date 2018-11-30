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
    
    // <span class="btn text-success active">Primary link</span>
    // <a href="#" class="btn btn-link text-secondary">Link</a>
    public function run()
    {
        $result = '';
        foreach ($this->item as $inx => $item) {
            if ($item->alias == Yii::$app->request->get('menu')) {
                $result .= Html::tag('span', $item->category, ['class' => 'btn text-muted active']);
            } else {
                $result .= Html::a(
                    $item->category,
                    Url::to(['nav/' . $item['alias']]),
                    ['class' => 'btn btn-link text-primary']
                );
            }
        }

        return Html::decode($result);
    }
}
