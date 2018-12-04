<?php

backend\assets\AppAsset::addCss($this, ['solid.css', 'fontawesome.min.css']);

$this->registerMetaTag([
  'name' => 'description',
  'content' => Yii::$app->menu->current->category . ' ' . Yii::$app->menu->current->remark
]);
$this->registerMetaTag([
  'name' => 'keywords',
  'keywords' => Yii::$app->menu->current->search_text
]);
$this->title = Yii::$app->menu->current->category . ' ' . Yii::$app->menu->current->remark;
?>
<div class="site-index">
  <div class="rounded shadow p-3 mb-3 bg-light">
    <nav class="navbar navbar-light bg-light">
      <a class="navbar-brand" href="#"><i class="fas fa-tags" style="color:#748ffc"></i> 标签</a>
    </nav>
    <?=common\widgets\Tags::widget(['item' => $tags])?>
  </div>

  <div class="card-columns column4">
    <?=common\widgets\Article::widget(['item' => $list])?>
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
