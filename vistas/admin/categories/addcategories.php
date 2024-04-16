<?php
require_once 'C:\xampp\htdocs\MVC\controllers\categories_controller.php';

use Controllers\categories_controller;

if (isset($_POST["category"])) {
  categories_controller::create_category($_POST["category"]);
}


?>

<div class="container">
  <div class="alert alert-primary" role="alert">
    <strong>Agregar categorías</strong>
  </div>

  <form method=post>
    <div class="mb-3 row">
      <label for="inputName" class="col-4 col-form-label">Categoría</label>
      <div class="col-8">
        <input type="text" class="form-control" name="category" id="inputName" placeholder="ingrese el nombre de la nueva categoría" />
      </div>
    </div>

    <div class="mb-3 row">
      <div class="offset-sm-4 col-sm-8">
        <button type="submit" name="ok1" class="btn btn-primary">
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
            <th scope="col">Nombre categoría</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php $result = categories_controller::get_categories(); ?>
          <?php if (!$result["error"]) : ?>
            <?php foreach ($result["result"] as $category) : ?>
              <tr>
                <td><input type='checkbox' name='eliminar[]' value='<?= $category->id ?>' title='<?= $category->id ?>'></td>
                <td><?= $category->id ?></td>
                <td><?= $category->category_name ?></td>
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