<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<div class="site-error card">
  <div class="card-body">
    <h1 class="card-title"><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
      <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>The above error occurred while the Web server was processing your request.</p>

    <p>Please contact us if you think this is a server error. Thank you.</p>
  </div>
</div>

<?php $this->beginBlock('js'); ?>
<script>
const vm = new Vue({
  el: '#app',
});
</script>
<?php $this->endBlock(); ?>
