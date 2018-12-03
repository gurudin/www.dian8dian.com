<?php

backend\assets\AppAsset::addCss($this, ['solid.css']);
backend\assets\AppAsset::addCss($this, ['fontawesome.min.css']);

$this->title = 'My Yii Application';
?>
<div class="site-index">
  <!-- <nav aria-label="breadcrumb">
    <?=common\widgets\Breadcrumb::widget(['item' => Yii::$app->menu::routes()])?>
  </nav> -->

  <div class="rounded shadow p-3 mb-3 bg-light">
    <nav class="navbar navbar-light bg-light">
      <a class="navbar-brand" href="#"><i class="fas fa-tags" style="color:#748ffc"></i> 标签</a>
    </nav>
    <?=common\widgets\Tags::widget(['item' => $tags])?>
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
