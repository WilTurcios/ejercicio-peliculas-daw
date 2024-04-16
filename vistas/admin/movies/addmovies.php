<?php

use Controllers\movies_controller;

require_once 'C:\xampp\htdocs\MVC\controllers\movies_controller.php';

if (isset($_POST["ok"])) {
  $movie_info = [];

  if (isset($_POST["title"])) {
    $movie_info["title"] = $_POST["title"];
  }
  if (isset($_POST["original_title"])) {
    $movie_info["original_title"] = $_POST["original_title"];
  }
  if (isset($_POST["year"])) {
    $movie_info["year"] = $_POST["year"];
  }
  if (isset($_POST["duration"])) {
    $movie_info["duration"] = $_POST["duration"];
  }
  if (isset($_POST["director"])) {
    $movie_info["director"] = $_POST["director"];
  }
  if (isset($_POST["writers"])) {
    $movie_info["writers"] = explode(", ", $_POST["writers"]);
  }
  if (isset($_POST["synopsis"])) {
    $movie_info["synopsis"] = $_POST["synopsis"];
  }

  if ($_FILES["imagen"]["error"]) {
    echo "<pre>";
    echo var_dump($_FILES);
    echo "</pre>";
    echo $_FILES["imagen"]["error"];
  }

  if (isset($_FILES['imagen'])) {
    $target_dir = "C:/xampp/htdocs/MVC/img/";
    $file_name = basename($_FILES["imagen"]["name"]);
    $target_file = $target_dir . $file_name;

    $uploadOk = !getimagesize($_FILES["imagen"]["tmp_name"]) ? 0 : 1;

    if (!$uploadOk) {
      echo "Lo siento, tu archivo no fue subido.";
    } else {
      if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        echo "File has been stored successfully";

        movies_controller::create_movie(
          $movie_info["title"],
          $movie_info["original_title"],
          (int)$movie_info["year"],
          (int)$movie_info["duration"],
          $movie_info["synopsis"],
          $movie_info["director"],
          $movie_info["writers"],
          $file_name,
        );
      }
    }
  }
}

?>

<div class="container">
  <section>

    <h2>Formulario de Película</h2>
    <form method="post" enctype="multipart/form-data">
      <div class=" mb-3">
        <label for="title" class="form-label">Título:</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="mb-3">
        <label for="original_title" class="form-label">Título Original:</label>
        <input type="text" class="form-control" id="original_title" name="original_title">
      </div>
      <div class="mb-3">
        <label for="year" class="form-label">Año:</label>
        <input type="number" class="form-control" id="year" name="year">
      </div>
      <div class="mb-3">
        <label for="duration" class="form-label">Duración (minutos):</label>
        <input type="number" class="form-control" id="duration" name="duration">
      </div>
      <div class="mb-3">
        <label for="synopsis" class="form-label">Sinopsis:</label>
        <textarea class="form-control" id="synopsis" name="synopsis" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label for="director" class="form-label">Director:</label>
        <input type="text" class="form-control" id="director" name="director">
      </div>
      <div class="mb-3">
        <label for="writers" class="form-label">Escritores (separados por comas ", "):</label>
        <input type="text" class="form-control" id="writers" name="writers">
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Imagen:</label>
        <input type="file" name="imagen" class="form-control" id="image" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary" name="ok">Guardar</button>
    </form>
  </section>
  <section>
    <h1 class="text-center">Información de las películas</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php $result = movies_controller::get_movies(); ?>
      <?php foreach ($result["result"] as $movie) : ?>
        <div class="col">
          <div class="card h-100">
            <img src="http://localhost/MVC/img/<?= $movie->image; ?>" class="card-img-top" alt="Imagen de la película">
            <div class="card-body">
              <h5 class="card-title"><?php echo $movie->title; ?></h5>
              <p class="card-text"><?php echo $movie->synopsis; ?></p>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Año:</strong> <?php echo $movie->year; ?></li>
                <li class="list-group-item"><strong>Duración:</strong> <?php echo $movie->duration; ?> minutos</li>
                <li class="list-group-item"><strong>Director:</strong> <?php echo $movie->director; ?></li>
                <li class="list-group-item"><strong>Guionistas:</strong> <?php echo implode(", ", $movie->writers); ?></li>
              </ul>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>