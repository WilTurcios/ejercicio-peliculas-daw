<?php
require_once 'controllers\users_controller.php';

use Controllers\users_controller;
use Models\User;

if (isset($_POST["ok"])) {
  if (!isset($_POST["user_name"]) || !isset($_POST["password"]) || !isset($_POST["is_admin"]) || !isset($_POST["email"])) {
    echo "
    <div class='alert alert-danger role='alert'>
      Asegurate de llenar todos los campos
    </div>
    ";
  }

  $is_admin = $_POST["is_admin"] == "admin" ? true : false;

  $hasSucced = users_controller::create_user(
    $_POST["user_name"],
    $_POST["email"],
    $_POST["password"],
    $is_admin
  );

  if (!$hasSucced)
    echo "
    <div class='alert alert-danger role='alert'>
      Asegurate de llenar todos los campos
    </div>
    ";
}


?>

<div class="container">
  <div class="alert alert-primary" role="alert">
    <strong>Agregar categorías</strong>
  </div>

  <form method=post>
    <div class="mb-3 row">
      <label for="inputName" class="col-4 col-form-label">Nombre del Usuario</label>
      <div class="col-8">
        <input type="text" class="form-control" name="user_name" id="inputName" placeholder="ingrese el nombre del nuevo usuario" />
      </div>
    </div>
    <div class="mb-3 row">
      <label for="inputName" class="col-4 col-form-label">E-mail</label>
      <div class="col-8">
        <input type="text" class="form-control" name="email" id="inputName" placeholder="Ingrese el e-mail del usuario" />
      </div>
    </div>
    <div class="mb-3 row">
      <label for="inputName" class="col-4 col-form-label">Contraseña</label>
      <div class="col-8">
        <input type="password" class="form-control" name="password" id="inputName" placeholder="Ingrese la contraseña del usuario" />
      </div>
    </div>
    <div class="mb-3 row">
      <label for="inputName" class="col-4 col-form-label">Nivel de acceso</label>
      <div class="col-8">

        <select class=" form-select" name="is_admin">
          <option value="admin">Administrador</option>
          <option value="user">Usuario</option>
        </select>
      </div>
    </div>


    <div class="mb-3 row">
      <div class="offset-sm-4 col-sm-8">
        <button type="submit" name="ok" class="btn btn-primary">
          Agregar
        </button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <div class="alert alert-primary" role="alert">
      <strong>Administrar categorías</strong>
    </div>
    <form method=post>
      <table class="table table-primary">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">ID</th>
            <th scope="col">Nombre usuario</th>
            <th scope="col">E-mail</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php $result = users_controller::get_users(); ?>
          <?php if (!$result["error"]) : ?>
            <?php foreach ($result["result"] as $user) : ?>
              <tr>
                <td><input type='checkbox' name='eliminar[]' value='<?= $user->id ?>' title='<?= $user->id ?>'></td>
                <td><?= $user->id ?></td>
                <td><?= $user->user_name ?></td>
                <td><?= $user->email ?></td>
                <td><a href=''>A</a></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>




          <tr>
            <td colspan=4>

              <input class='btn btn-danger' type="submit" value="Eliminar" name=del>

            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>