<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $keywords;
?>

<div class="site-index">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=Url::to('/')?>">首页</a></li>
      <li class="breadcrumb-item">搜索</li>
      <li class="breadcrumb-item active">
        关键字“<?=Html::tag('span', $keywords, ['class' => 'text-danger'])?>”，为您找到
        <?=Html::tag('span', $page->totalCount, ['class' => 'text-danger'])?> 个结果。
      </li>
    </ol>
  </nav>

  <div class="row">
    <?=common\widgets\Article::widget(['item' => $list, 'mode' => 'search'])?>
  </div>

  <nav aria-label="Page navigation">
    <?= common\widgets\LinkPager::widget([
      'pagination'    => $page,
      'prevPageLabel' => '&laquo;',
      'nextPageLabel' => '&raquo;',
      'options'       => ['class' => 'pagination pagination-sm justify-content-end']
    ]); ?>
  </nav>
</div>
