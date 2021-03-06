<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
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
  <link rel="stylesheet" href="/css/solid.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/fontawesome.min.css" rel="stylesheet">
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">
  <div class="d-flex">
    <nav class="sidebar">
      <div class="sidebar-content">
        <a class="sidebar-brand" href="/">
          <span class="align-middle">AppStack</span>
        </a>

        <ul class="sidebar-nav" id="nav" v-cloak>
          <li class="sidebar-header">
            Main
          </li>
          
          <li class="sidebar-item" v-for="item in navItem">
            <a
              :href="item.href!='#' ? item.href : 'javascript:void(0)'"
              :data-toggle="typeof item.child=='object' ? 'collapse' : ''"
              class="sidebar-link"
              :class="{'collapsed':item.open==false}"
              @click="openChild(item)">
              <i v-if="item.icon!=''" :class="item.icon"></i>
              <span class="align-middle">{{item.label}}</span>
              <span class="sidebar-badge badge badge-primary" v-if="item.badge!=''">{{item.badge}}</span>
            </a>

            <ul
              class="sidebar-dropdown list-unstyled collapse"
              :class="{'show': item.open==true}"
              v-if="typeof item.child=='object'">
              <li class="sidebar-item" :class="{'active':child.href==currentUri}" v-for="child in item.child">
                <a class="sidebar-link" :href="child.href!='#' ? child.href : 'javascript:void(0)'">
                  <i v-if="child.icon!=''" :class="child.icon"></i>
                  {{child.label}}
                  <span class="sidebar-badge badge badge-primary" v-if="child.badge!=''">{{child.badge}}</span>
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>

    <div class="main">
      <nav class="navbar navbar-expand navbar-light bg-white">
        <a class="sidebar-toggle d-flex mr-2">
          <i class="hamburger align-self-center"></i>
        </a>

        <form class="form-inline d-none d-sm-inline-block">
          <input class="form-control mr-sm-2" type="text" placeholder="Search projects" aria-label="Search">
        </form>

        <div class="navbar-collapse collapse">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <?=Html::beginForm(['/site/logout'], 'post')?>
              <?=Html::submitButton('Sign out (' . Yii::$app->user->identity->username . ')', [
                'class' => 'btn btn-link logout text-dark'
              ])?>
              <?=Html::endForm()?>
            </li>
          </ul>
        </div>
      </nav>

      <main class="content" id="app" v-cloak>
        <div class="container-fluid p-0">
          <?= $content ?>
        </div>
      </main>
    </div>
  </div>
</div>

<?php $this->endBody() ?>

<?php if (isset($this->blocks['js'])): ?>
  <?= $this->blocks['js'] ?>
<?php endif; ?>

<script>
$(function(){
  initMenu(<?=json_encode(Yii::$app->params['nav'])?>);
});
</script>

</body>
</html>
<?php $this->endPage() ?>
