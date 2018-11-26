<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
  <div class="card-columns column4">
    <?=common\widgets\Article::widget(['item' => $list])?>
  </div>
</div>
