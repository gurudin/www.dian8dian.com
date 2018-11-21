<?php
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Article List';
?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Article
    <a href="<?=URL::to(['/article/save'])?>" class="btn btn-success float-right">Create</a>
    </h4>
    <hr>

  </div>
</div>

<?php $this->beginBlock('js'); ?>
<script>
const vm = new Vue({
  el: '#app'
});
</script>
<?php $this->endBlock(); ?>
