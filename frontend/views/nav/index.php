<?php

$this->title = 'My Yii Application';
?>
<div class="site-index">
  <!-- <nav aria-label="breadcrumb">
    <?=common\widgets\Breadcrumb::widget(['item' => Yii::$app->menu::routes()])?>
  </nav> -->

  <div class="rounded shadow p-3 mb-3 bg-light">
    <?=common\widgets\Tags::widget(['item' => Yii::$app->menu::children(Yii::$app->menu::routes()[1]['title'])])?>
  </div>

  <div class="card-columns column4">
    <?=common\widgets\Article::widget(['item' => $list])?>
  </div>

  <div>
    <div class="float-left"></div>
    <div class="float-right">
      <?= common\widgets\LinkPager::widget([
        'pagination'    => $page,
        'prevPageLabel' => '&laquo;',
        'nextPageLabel' => '&raquo;'
      ]); ?>
    </div>
  </div>
</div>
