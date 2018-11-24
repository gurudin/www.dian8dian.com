<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class Menu extends Widget
{
    /**
     * Menu list
     *
     * @var Array
     */
    public $item;

    public function init()
    {
        parent::init();

        $retTag = '';
        foreach ($this->item as $value) {
            $tmp = $this->renderA($value, '#');
            $retTag .= $this->renderLi($tmp);
        }
        
        $tag = $this->renderUl($retTag);

        print_r($tag);
    }

    public function renderUl($tag)
    {
        return Html::tag('ul', $tag, ['class' => 'navbar-nav']);
    }

    public function renderLi($tag)
    {
        return Html::tag('li', $tag, ['class' => 'nav-item']);
    }

    public function renderA($tag, $uri)
    {
        return Html::tag('a', $tag, ['class' => 'nav-link', 'href' => $uri]);
    }
 
    public function run()
    {
        // return Html::encode($this->message);
    }
}
