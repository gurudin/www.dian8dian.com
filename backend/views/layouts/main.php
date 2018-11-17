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
  <link href="https://appstack.bootlab.io/css/app.css" rel="stylesheet">
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">
  <div class="d-flex">
    <nav class="sidebar">
      <div class="sidebar-content">
        <a class="sidebar-brand" href="index.html">
          <span class="align-middle">AppStack</span>
        </a>

        <ul class="sidebar-nav" id="nav">
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
              
              <span class="align-middle">{{item.label}}</span>
              <span class="sidebar-badge badge badge-primary" v-if="item.badge!=''">{{item.badge}}</span>
            </a>

            <ul
              class="sidebar-dropdown list-unstyled collapse"
              :class="{'show': item.open==true}"
              v-if="typeof item.child=='object'">
              <li class="sidebar-item" :class="{'active':child.href==window.location.pathname}" v-for="child in item.child">
                <a class="sidebar-link" :href="child.href!='#' ? child.href : 'javascript:void(0)'">
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
              <a class="nav-link" href="javascript:void(0)">
                <span class="d-none d-sm-inline-block">
                  <span class="text-dark">Sign out</span>
                </span>
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <main class="content">
        <div class="container-fluid p-0">
          <?= $content ?>
        </div>
      </main>
    </div>
  </div>
</div>

<?php $this->endBody() ?>
<script>
const nav = new Vue({
  el: '#nav',
  data() {
    return {
      navItem: <?=json_encode(Yii::$app->params['nav'])?>
    };
  },
  methods: {
    openChild(item) {
      if (typeof item.open == 'undefined') {
        item.open = true;
      } else {
        item.open = !item.open;
      }
    }
  },
  created() {
    this.navItem.forEach(row => {
      if (typeof row.child=='object') {
        row.child.forEach(child => {
          if (child.href == window.location.pathname) {
            row.open = true;
          }
        });
      }
    });
  }
});
</script>
</body>
</html>
<?php $this->endPage() ?>
