<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class Article extends Widget
{
    /**
     * Article list
     *
     * @var Array
     */
    public $item;

    // <div class="card">
    //   <img class="card-img-top" src=".../100px160/" alt="Card image cap">
    //   <div class="card-body">
    //     <h5 class="card-title">1Card title that wraps to a new line</h5>
    //     <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
    //     <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
    //   </div>
    // </div>

    public function init()
    {
        parent::init();

        $result = '';
        foreach ($this->item as $key => $item) {
            $tmp_img = $item->cover != ''
                ? Html::a(
                    Html::img(Yii::$app->params['imgUrl'] . $item->cover, [
                        'class' => 'card-img-top',
                        'alt'   => $item->title,
                        'title' => $item->title,
                    ]),
                    Url::to(['article', 'id' => $item->id]), // TODO:
                    []
                )
                : '';

            $author = Html::tag(
                'footer',
                Html::tag(
                    'small',
                    Html::a(
                        '<cite title="Source Title">' . $item->author . '</cite>',
                        Url::to(['search', 'author' => $item->author]),
                        ['class' => 'btn-link text-muted font-italic']
                    ),
                    ['class' => 'text-muted']
                ),
                ['class' => 'blockquote-footer text-right']
            );

            $tmp_title = Html::a(
                Html::tag('h6', $item->title, ['class' => 'card-title']),
                Url::to(['article', 'id' => $item->id]), // TODO:
                [
                    'class' => 'btn-link text-info',
                    'alt'   => $item->title,
                    'title' => $item->title
                ]
            );


            $tmp_remark = $item->remark != ''
                ? Html::tag('p', $item->remark, ['class' => 'card-text text-muted font13'])
                : '';

            $tmp_body = Html::tag('div', $tmp_title . $tmp_remark . $author, ['class' => 'card-body']);
            $tmp_card = Html::tag('div', $tmp_img . $tmp_body, ['class' => 'card p-2']);

            $result .= $tmp_card;
        }

        echo $result;
    }

    private function rederA(string $tag, array $options = [])
    {

    }

    private function renderP(string $tag, array $options = [])
    {
        return Html::tag('p', $tag, $options);
    }

    private function renderH5(string $tag, array $options = [])
    {
        return Html::tag('h5', $tag, $options);
    }

    private function renderDiv(string $tag, array $options = [])
    {
        return Html::tag('div', $tag, $options);
    }

    private function renderImg(string $src, array $options = [])
    {
        return Html::img(Yii::$app->params['imgUrl'] . $src, $options);
    }
 
    public function run()
    {
        // return Html::decode($this->setMenu());
    }
}
