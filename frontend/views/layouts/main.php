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

<header class="w-100" style="background-color: #396fe0; border-bottom: 1px solid #2e51f5;">
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #396fe0;">
    <a class="navbar-brand" href="/"><img src="/images/logo.png" width="27"></a>

    <div class="collapse navbar-collapse">
        <?=common\widgets\Menu::widget(['item' => Yii::$app->menu->nav])?>
    </div>
    <span class="navbar-text">
      Navbar text with an inline element
    </span>
  </nav>
</header>


<?php if (isset($this->blocks['jumbotron'])) : ?>
    <?=$this->blocks['jumbotron']?>
<?php endif; ?>

<div class="content container-full h-100">
    <?=$content?>
</div>

<footer class="w-100 bg-secondary">
  <div class="top-footer"></div>
  <div class="bottom-footer bg-dark text-center">
    <small class="text-muted">&copy; 2018, Inc.</small>
  </div>
</footer>
<?php $this->endBody() ?>

<?php if (isset($this->blocks['js'])) : ?>
    <?= $this->blocks['js'] ?>
<?php endif; ?>

</body>
</html>
<?php $this->endPage() ?>
