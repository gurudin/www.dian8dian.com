<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>

<main class="main h-100 w-100">
  <div class="container h-100">
    <div class="row h-100">
      <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-75">
        <div class="d-table-cell align-middle">
          <div class="text-center mt-4">
            <h1 class="h2">Welcome back</h1>
            <p class="lead">
              Sign in to your account to continue
            </p>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="m-sm-4">
                <div class="text-center"></div>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                  <div class="form-group">
                    <?=$form->field($model, 'username')->textInput([
                      'autofocus'   => true,
                      'placeholder' => 'Enter your email',
                      'class'       => 'form-control form-control-lg',
                      'value'       => 'admin'
                    ])?>
                  </div>

                  <div class="form-group">
                    <?=$form->field($model, 'password')->passwordInput([
                      'class'       => 'form-control form-control-lg',
                      'placeholder' => 'Enter your password',
                      'value'       => 'admin123'
                    ]) ?>
                  </div>
                  
                  <?=$form->field($model, 'rememberMe')->checkbox()?>
                  
                  <div class="text-center mt-3">
                    <?= Html::submitButton('Sign in', [
                      'class' => 'btn btn-lg btn-success',
                      'name'  => 'login-button'
                    ]) ?>
                  </div>
                <?php ActiveForm::end(); ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
