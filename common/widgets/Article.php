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

    /**
     * Mode
     *
     * @var string
     */
    public $mode = 'nav';

    public function init()
    {
        parent::init();
    }

    private function nav()
    {
        $result = '';
        foreach ($this->item as $key => $item) {
            $cover = $item->cover != ''
                ? Html::a(
                    Html::img(Yii::$app->params['imgUrl'] . $item->cover, [
                        'class' => 'card-img-top',
                        'alt'   => $item->title,
                        'title' => $item->title,
                    ]),
                    Url::toRoute(['article/detail', 'id' => $item->id, 'title' => $item->title], true),
                    []
                )
                : '';

            $author = Html::tag(
                'footer',
                Html::tag(
                    'small',
                    Html::a(
                        '<cite>' . $item->author . '</cite>',
                        Url::toRoute(['nav/search', 'keywords' => 'author-' . $item->author], true),
                        [
                            'class' => 'btn-link text-muted font-italic',
                            'title' => $item->author,
                            'alt'   => $item->author,
                        ]
                    ),
                    ['class' => 'text-muted']
                ),
                ['class' => 'blockquote-footer text-right']
            );

            $title = Html::a(
                Html::tag('h6', $item->title, ['class' => 'card-title']),
                Url::toRoute(['article/detail', 'id' => $item->id, 'title' => $item->title], true),
                [
                    'class' => 'btn-link text-info',
                    'alt'   => $item->title,
                    'title' => $item->title
                ]
            );


            $remark = $item->remark != ''
                ? Html::tag('p', $item->remark, ['class' => 'card-text text-muted font13'])
                : '';

            $tags = '';
            if ($item->tags != '') {
                $tagHtml = '';
                foreach ($item->tags as $key => $tag) {
                    $tagHtml .= ' ' . Html::a(
                        $tag,
                        Url::toRoute(['nav/search', 'keywords' => 'tag-' . $tag], true),
                        [
                            'class' => 'text-info badge badge-light p-2',
                            'title' => $tag,
                            'alt'   => $tag,
                        ]
                    );
                }
                $tags = Html::tag(
                    'p',
                    Html::tag('small', $tagHtml, []),
                    [
                        'class' => 'card-text',
                        'style' => 'overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'
                    ]
                );
            }

            $tmp_body = Html::tag('div', $title . $remark . $author . $tags, ['class' => 'card-body']);
            $tmp_card = Html::tag('div', $cover . $tmp_body, ['class' => 'card p-2']);

            $result .= $tmp_card;
        }

        return Html::decode($result);
    }

    private function search()
    {
        $result = '';
        foreach ($this->item as $key => $article) {
            $image = '';
            if ($article->cover != '') {
                $image = Html::a(
                    Html::img(
                        Yii::$app->params['imgUrl'] . $article->cover,
                        [
                            'class' => 'card-img-top',
                            'title' => $article->title,
                            'alt'   => $article->title
                        ]
                    ),
                    Url::toRoute(['article/detail', 'id' => $article->id, 'title' => $article->title], true),
                    [
                        'title' => $article->title,
                        'alt'   => $article->title
                    ]
                );
            }
            
            $author = Html::tag(
                'footer',
                Html::tag(
                    'small',
                    Html::a(
                        '<cite>' . $article->author . '</cite>',
                        Url::toRoute(['nav/search', 'keywords' => 'author-' . $article->author], true),
                        [
                            'class' => 'btn-link text-muted font-italic',
                            'title' => $article->author,
                            'alt'   => $article->author,
                        ]
                    ),
                    ['class' => 'text-muted']
                ),
                ['class' => 'blockquote-footer text-right']
            );

            $title = Html::a(
                Html::tag('h6', $article->title, ['class' => 'card-title']),
                Url::toRoute(['article/detail', 'id' => $article->id, 'title' => $article->title], true),
                [
                    'class' => 'btn-link text-info',
                    'alt'   => $article->title,
                    'title' => $article->title
                ]
            );

            $remark = $article->remark != ''
                ? Html::tag('p', $article->remark, ['class' => 'card-text text-muted font13'])
                : '';

            $tags = '';
            if ($article->tags != '') {
                $tagHtml = '';
                foreach ($article->tags as $key => $tag) {
                    $tagHtml .= ' ' . Html::a(
                        $tag,
                        Url::toRoute(['nav/search', 'keywords' => 'tag-' . $tag], true),
                        [
                            'class' => 'text-info badge badge-light p-2',
                            'title' => $tag,
                            'alt'   => $tag,
                        ]
                    );
                }
                $tags = Html::tag(
                    'p',
                    Html::tag('small', $tagHtml, []),
                    [
                        'class' => 'card-text',
                        'style' => 'overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'
                    ]
                );
            }

            $card_body = Html::tag('div', $title . $remark . $author . $tags, ['class' => 'card-body']);
            $card = Html::tag('div', $image . $card_body, ['class' => 'card search-card p-1 mb-4']);

            $result .= Html::tag('div', $card, ['class' => 'col-sm-3']);
        }

        return Html::decode($result);
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
                return $this->nav();
                break;
        }
    }
}
