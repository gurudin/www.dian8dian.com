<?php
use yii\helpers\Html;

frontend\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $this->registerCsrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="w-100 border border-muted border-top-0 border-left-0 border-right-0">
  <nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="/">Widget</a>

    <div class="collapse navbar-collapse">
      <?=common\widgets\Menu::widget(['item' => Yii::$app->menu->nav])?>
    </div>
  </nav>
</header>

<div class="content container-full h-100">
  <?=$content?>
</div>

<footer class="w-100 bg-secondary">
  <div class="top-footer"></div>
  <div class="bottom-footer bg-dark text-center"><small class="text-muted">© Fonticons, Inc.</small></div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
