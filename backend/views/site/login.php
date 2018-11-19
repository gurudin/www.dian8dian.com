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
                <div class="text-center">
                  
                </div>
                <form>
                  <div class="form-group">
                    <label>Email</label>
                    <input class="form-control form-control-lg" type="email" name="email" placeholder="Enter your email" />
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input class="form-control form-control-lg" type="password" name="password" placeholder="Enter your password" />
                  </div>
                  <div class="text-center mt-3">
                    <a href="index.html" class="btn btn-lg btn-success">Sign in</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
