<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class Breadcrumb extends Widget
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
        $result = '';
        $aHtml  = '';
        foreach ($this->item as $inx => $item) {
            if (count($this->item) == ($inx + 1)) {
                $aHtml .= Html::tag('li', $item['title'], ['class' => 'breadcrumb-item active']);
            } else {
                $aHtml .= Html::tag(
                    'li',
                    Html::a($item['title'], $item['uri'], []),
                    ['class' => 'breadcrumb-item']
                );
            }
        }
        $result = Html::tag('ol', $aHtml, ['class' => 'breadcrumb bg-light']);

        return Html::decode($result);
    }
}
