<?php

namespace Controllers;

require_once 'C:\xampp\htdocs\MVC\models\movies.php';

use Models\Movie;

class movies_controller
{
  public static function create_movie(
    string $title,
    string $original_title,
    int $year,
    int $duration,
    string $synopsis,
    string $director,
    array $writers,
    string $image_url,
  ) {
    $response = [
      "error" => false,
      "error_message" => "",
      "result" => []
    ];
    if (
      !($title && strlen(trim($title)) == 0) &&
      !($original_title && strlen($original_title) == 0) &&
      !($synopsis && strlen($synopsis) == 0) &&
      !($director && strlen($director) == 0) &&
      !$year &&
      !$duration &&
      !(count($writers) == 0) &&
      !$image_url
    ) {
      $response["error"] = true;
      $response["error_message"] = "
      Asegurate de rellenar todos los campos correctamente. Sin espacios al inicio ni al final.
      ";
      return $response;
    }

    $new_movie = new Movie(
      $title,
      $original_title,
      $year,
      $duration,
      $synopsis,
      $director,
      $writers,
      $image_url
    );

    $result = $new_movie->save();

    if (!$result) {
      $response["error"] = true;
      $response["error_message"] = "Algo salió mal, por favor intentalo de nuevo";

      return $response;
    }

    $response["result"] = $result;

    return $response;
  }

  public static function get_movies()
  {
    $response = [
      "error" => false,
      "error_message" => "",
      "result" => []
    ];

    $result = Movie::get_all();

    if (!$result["ok"]) {
      $response["error"] = true;
      $response["error_message"] = "Algo salió mal, por favor intentalo de nuevo";

      return $response;
    }

    $response["result"] = $result["data"];

    return $response;
  }
}
