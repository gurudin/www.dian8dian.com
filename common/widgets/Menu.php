<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

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
        $homeCls = '';
        if (in_array(Yii::$app->request->getPathInfo(), ['', 'site/index'])) {
            $homeCls = 'active';
        }

        $retTag = $this->renderLi($this->renderA('首页', '/'), $homeCls);
        foreach ($this->item as $key => $value) {
            $tmp = $this->renderA($value['category'], Url::to(['nav/index', 'menu' => $value['alias']], true));
            $retTag .= $this->renderLi($tmp);
        }

        // $retTag = '';
        // foreach ($this->item as $value) {
        //     $tmp = $this->renderA($value, '#');
        //     $retTag .= $this->renderLi($tmp);
        // }
        
        $tag = $this->renderUl($retTag);

        print_r($tag);

        // print_r($this->item);
    }

    public function renderUl(string $tag)
    {
        return Html::tag('ul', $tag, ['class' => 'navbar-nav']);
    }

    public function renderLi(string $tag, string $class = '')
    {
        return Html::tag('li', $tag, ['class' => 'nav-item ' . $class]);
    }

    public function renderA(string $tag, string $uri)
    {
        return Html::tag('a', $tag, ['class' => 'nav-link', 'href' => $uri]);
    }
 
    public function run()
    {
        // return Html::encode($this->message);
    }
}
