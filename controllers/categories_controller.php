<?php

namespace Controllers;

require_once "models/category.php";

use Models\Category;



class categories_controller
{
  public static function create_category(string $name)
  {
    $response = [
      "error" => false,
      "error_message" => "",
      "result" => []
    ];
    if ($name && strlen(trim($name)) == 0) {
      $response["error"] = true;
      $response["error_message"] = "
      Asegurate de rellenar el campo de nombre correctamente. Sin espacios al inicio ni al final.
      ";
      return $response;
    }

    $new_category = new Category($name);

    $result = $new_category->save();

    if (!$result) {
      $response["error"] = true;
      $response["error_message"] = "Algo salió mal, por favor intentalo de nuevo";

      return $response;
    }

    $response["result"] = $result;

    return $response;
  }
  public static function get_categories()
  {
    $response = [
      "error" => false,
      "error_message" => "",
      "result" => []
    ];

    $result = Category::get_all();

    if (!$result["ok"]) {
      $response["error"] = true;
      $response["error_message"] = "Algo salió mal, por favor intentalo de nuevo";

      return $response;
    }

    $response["result"] = $result["data"];

    return $response;
  }
}
