<?php
require_once "controllers/users_controller.php";

use Controllers\users_controller;

if (isset($_POST["ok"])) {
  if (empty($_POST["user_name"]) || empty($_POST["password"])) {
    echo "
    <div class='alert alert-danger role='alert'>
      Usuario o contraseña incorrectos
    </div>
    ";
  } else {
    $user = users_controller::validate_user($_POST["user_name"], $_POST["password"]);

    if ($user) {
      $_SESSION["user"] = $user;
      header("location: inicio");
      exit;
    } else unset($_SESSION["user"]);
  }
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title text-center">Iniciar sesión</h5>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="mb-3">
              <label for="username" class="form-label">Usuario</label>
              <input type="text" name="user_name" class="form-control" id="username" placeholder="Miguel">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña">
            </div>
            <div class="d-grid gap-2">
              <button type="submit" name="ok" value="1" class="btn btn-primary">Iniciar sesión</button>
            </div>
            <div class="text-center mt-3">
              <a href="#" class="link-secondary">¿Olvidaste tu contraseña?</a>
            </div>
            <hr>
            <div class="text-center">
              <p class="mb-0">¿No tienes una cuenta?</p>
              <a href="#" class="btn btn-link">Crear cuenta</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>