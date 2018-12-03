<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class Detail extends Widget
{
    /**
     * Data list
     *
     * @var Array
     */
    public $item;

    /**
     * Mode
     *
     * @var string
     *
     * @example tags|href
     */
    public $mode;

    /**
     * Category alias
     *
     * @var string
     */
    public $category_alias;

    public function init()
    {
        parent::init();
    }

    private function tags()
    {
        if (empty($this->item)) {
            return '';
        }

        $result = Html::tag('i', '', ['class' => 'fas fa-tags text-muted']);
        foreach ($this->item as $key => $item) {
            $result .= ' ' . Html::a(
                $item['title'],
                Url::to(["nav/{$this->category_alias}", 'tag' => $item['alias']]),
                [
                    'class' => 'text-info badge badge-light p-2',
                    'title' => $item['title'] . ' ' . $item['alias'],
                    'alt'   => $item['title'] . ' ' . $item['alias']
                ]
            );
        }
        $result = Html::tag('small', $result);

        return $result;
    }

    private function href()
    {
        $result = '';
        if ($this->item['source'] != '') {
            $result .= Html::tag(
                'small',
                '来源：' . Html::a(
                    $this->item['source'],
                    $this->item['source'],
                    ['target' => "_blank", 'class' => 'text-muted']
                )
            );
        }
        if ($this->item['demo'] != '') {
            $result .= Html::tag('br', '');
            $result .= Html::tag(
                'small',
                'Demo：' . Html::a(
                    $this->item['demo'],
                    $this->item['demo'],
                    ['target' => '_blank', 'class' => 'text-muted']
                )
            );
        }

        return $result;
    }

    public function attribute()
    {
        $result = '';
        
        if (empty($this->item['category']['pic'])) {
            $left = '分类:';
        } else {
            $left = Html::img(Yii::$app->params['imgUrl'] . $this->item['category']['pic'], [
                'class' => 'rounded',
                'width' => '15',
                'title' => $this->item['category']['category'],
                'alt'   => $this->item['category']['category']
            ]);
        }
        $category = Html::tag(
            'small',
            $left . ' ' . Html::a(
                $this->item['category']['category'],
                Url::to(['nav/' . $this->item['category']['alias']], true),
                [
                    'title' => $this->item['category']['category'],
                    'alt'   => $this->item['category']['category']
                ]
            ),
            ['class' => 'text-muted']
        );
        
        $author = Html::tag(
            'small',
            Html::tag('i', '', ['class' => 'fas fa-user text-muted']) . ' '.
            Html::a($this->item['author'], 'http://')
        );

        return $category . '&nbsp;&nbsp;' . $author . '&nbsp;&nbsp;';
    }
    
    public function run()
    {
        switch ($this->mode) {
            case 'tags':
                return Html::decode($this->tags());
                break;
            case 'href':
                return Html::decode($this->href());
                break;
            case 'attribute':
                return Html::decode($this->attribute());
                break;
            default:
                return Html::decode('');
                break;
        }
    }
}
