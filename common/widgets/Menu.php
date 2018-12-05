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

    /**
     * Current nav.
     *
     * @var string
     */
    public $current;

    public function init()
    {
        parent::init();

        $this->current = Yii::$app->request->getPathInfo();
    }

    public function setMenu()
    {
        $homeCls = '';
        if (in_array($this->current, ['', 'site/index'])) {
            $homeCls = 'active';
        }

        $retTag = $this->renderLi($this->renderA('首页', '/'), $homeCls);
        if (!empty($this->item)) {
            foreach ($this->item as $key => $value) {
                $tmp = $this->renderA($value['category'], Url::toRoute(['nav/index', 'menu' => $value['alias']], true));

                $tmpCls = in_array($this->current, ['nav/' . $value['alias']]) ? 'active' : '';
                $retTag .= $this->renderLi($tmp, $tmpCls);
            }
        }

        return $this->renderUl($retTag);
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
        return Html::decode($this->setMenu());
    }
}
