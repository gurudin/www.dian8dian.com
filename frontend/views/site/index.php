<?php
use yii\helpers\Html;
use yii\helpers\Url;

backend\assets\AppAsset::addCss($this, ['solid.css', 'fontawesome.min.css']);

$this->title = 'My Yii Application';
?>

<?php $this->beginBlock('jumbotron'); ?>
<div class="jumbotron jumbotron-fluid text-center text-white" style="background-color:#4aa0f8;background-image: url('https://cdnp.iconscout.com/photo/free/preview/assorted-colors-pencil-on-wooden-table-1057616.jpg');">
  <div class="container">
    <h3 class="display-4">搜索组件</h3>

    <div class="input-group m-auto" style="width:600px;">
        <?=Html::input('text', 'keywords', '', [
          'class' => 'form-control form-control-lg border-right-0',
          'placeholder' => 'Search components...',
          'required' => 'required'
        ])?>
        <div class="input-group-append">
          <span class="input-group-text bg-white">
            <?=Html::button('<i class="fas fa-search"></i>', [
              'class' => 'btn btn-primary',
              'name' => 'btn-keywords'
            ])?>
          </span>
        </div>
    </div>

    <div class="mb-1"></div>
    
    <p class="m-auto text-left" style="width:600px;">
      <a class="badge badge-primary p-2" href="#">图片效果</a>
    </p>
    
    <p class="lead mt-5">We are the people who change the world.</p>
  </div>
</div>
<?php $this->endBlock(); ?>

<div class="site-index">
  <div class="card-columns">
    <?=common\widgets\Article::widget(['item' => $article['list']])?>
  </div>

  <nav aria-label="Page navigation">
    <?= common\widgets\LinkPager::widget([
      'pagination'    => $article['page'],
      'prevPageLabel' => '&laquo;',
      'nextPageLabel' => '&raquo;',
      'options'       => ['class' => 'pagination pagination-sm justify-content-end']
    ]); ?>
  </nav>
</div>

<?php $this->beginBlock('js'); ?>
<script>
$(function() {
  var search = function () {
    if ($("input[name='keywords']").val() == '') {
      return false;
    } else {
      window.location.href = "<?=Url::toRoute(['nav/search'], true)?>/" + $("input[name='keywords']").val();
    }
  }

  $("button[name='btn-keywords']").click(() =>{
    search();
  });
  $("input[name='keywords']").keyup(event =>{
    if (event.which == 13) {
      search();
    }
  });
  
});
</script>
<?php $this->endBlock(); ?>
