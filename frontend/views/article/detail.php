<?php
use yii\helpers\Url;

backend\assets\AppAsset::addCss($this, ['prism.css']);
backend\assets\AppAsset::addScript($this, ['prism.js']);

$this->registerMetaTag([
  'name'    => 'description',
  'content' => $article['remark'] . ' ' . $article['title_search']
]);
$this->registerMetaTag([
  'name' => 'keywords',
  'keywords' => $article['keywords']
]);
$this->title = $article['category']['category'] . ' ' . $article['title'];
?>
<div class="site-index">
  <h2><?=$article['title']?></h2>
  <p>
    <!-- Attribute widget -->
    <?=common\widgets\Detail::widget(['item' => $article, 'mode' => 'attribute'])?>
    <!-- /Attribute widget -->

    <!-- Tags widget -->
    <?=common\widgets\Detail::widget([
      'item' => $article['tags'],
      'mode' => 'tags',
      'category_alias' => $article['category']['alias']
    ])?>
    <!-- /Tags widget -->
  </p>

  <!-- View source & demo -->
    <?=common\widgets\Detail::widget(['item' => $article, 'mode' => 'href'])?>
  <!-- /View source & demo -->
  <hr>

  <?=$article['content']?>
</div>

<?php $this->beginBlock('js'); ?>
<script>
$(function() {
  var tabs = $("table");
  $.each(tabs, function (i) { 
    $(this).addClass('table table-hover');
  });
});
</script>
<?php $this->endBlock(); ?>
