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
  <script>
  var _hmt = _hmt || [];
  (function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?bd1cecca932b04e26fa06afaa7901450";
    var s = document.getElementsByTagName("script")[0]; 
    s.parentNode.insertBefore(hm, s);
  })();
  </script>
</head>
<body style="overflow-x: hidden;">
<?php $this->beginBody() ?>

<header class="w-100" style="background-color: #396fe0; border-bottom: 1px solid #2e51f5;">
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #396fe0;">
    <a class="navbar-brand" href="/"><img src="/images/logo.png" width="27"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <?=common\widgets\Menu::widget(['item' => Yii::$app->menu->nav])?>
    </div>
    
    <?php if (in_array(Yii::$app->request->pathInfo, ['', 'site/index'])): ?>
    <span class="navbar-text">
      <a class="text-light" href="https://github.com/gurudin/www.dian8dian.com" target="_blank">
        <i class="fas fa-code-branch"></i>
      </a>
    </span>
    <?php else: ?>
      <div class="form-inline">
        <div class="input-group input-group-sm">
          <?= Html::input('text', 'keywords', '', [
            'class'       => 'form-control border-right-0',
            'placeholder' => 'Search...',
            'required'    => 'required'
          ]) ?>
          <div class="input-group-append">
            <span class="input-group-text bg-white">
              <?=Html::button('<i class="fas fa-search"></i>', [
                'style' => 'border: none; background: white;',
                'name'  => 'btn-keywords'
              ])?>
            </span>
          </div>
        </div>
      </div>
    <?php endif; ?>
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
