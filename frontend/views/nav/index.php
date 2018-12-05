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
  <div class="rounded shadow p-3 mb-3">
    <nav class="navbar navbar-light">
      <span class="navbar-brand" style="color: #4aa0f8;"><i class="fas fa-tags"></i> 标签</span>
    </nav>
    <?=common\widgets\Tags::widget(['item' => $tags, 'mode' => 'nav'])?>
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
