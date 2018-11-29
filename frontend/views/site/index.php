<?php

$this->title = 'My Yii Application';
?>
<div class="site-index">

  <div class="jumbotron">
    <div class="container">
      <h3 class="display-4">Fluid jumbotron</h3>
      <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
    </div>
  </div>

  <div class="card-columns">
    <?=common\widgets\Article::widget(['item' => $article['list']])?>
  </div>

  <div>
    <div class="float-left"></div>
    <div class="float-right">
      <?= common\widgets\LinkPager::widget([
        'pagination'    => $article['page'],
        'prevPageLabel' => '&laquo;',
        'nextPageLabel' => '&raquo;'
      ]); ?>
    </div>
  </div>
  
</div>
