<?php

/* @var $this yii\web\View */

$this->title = 'Welcome ' . Yii::$app->user->identity->username;
?>

<div class="jumbotron bg-white">
  <h1 class="display-4">Welcome, <?=Yii::$app->user->identity->username?>!</h1>
  <p class="lead">You have successfully logged in Widget Admin.</p>
  <hr class="my-4">
  <p><i class="fas fa-exclamation-triangle"></i> We log every actions and operations, please use with care.</p>
</div>
