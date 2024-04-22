<?php

namespace Controllers;

require_once "models/user.php";

use Models\User;

class users_controller
{
  public static function create_user(
    string $user_name,
    string $email,
    string $password,
    bool $is_admin = false
  ) {
    $response = [
      "error" => false,
      "error_message" => "",
      "result" => []
    ];

    if (
      empty(trim($user_name)) ||
      empty($email) ||
      empty($password)
    ) {
      $response["error"] = true;
      $response["error_message"] = "Asegúrate de rellenar todos los campos correctamente. Sin espacios al inicio ni al final.";
      return $response;
    }

    $new_user = new User(
      null,
      $user_name,
      $password,
      $email,
      $is_admin
    );

    $result = $new_user->save();

    if (!$result) {
      $response["error"] = true;
      $response["error_message"] = "Algo salió mal, por favor inténtalo de nuevo";
      return $response;
    }

    $response["result"] = $result;
    return $response;
  }

  public static function get_users()
  {
    $response = [
      "error" => false,
      "error_message" => "",
      "result" => []
    ];

    $result = User::get_all();

    if (!$result["ok"]) {
      $response["error"] = true;
      $response["error_message"] = "Algo salió mal, por favor inténtalo de nuevo";
      return $response;
    }

    $response["result"] = $result["data"];
    return $response;
  }

  public static function validate_user(string $user_name, string $password): array
  {
    $response = [
      "error" => false,
      "error_message" => "",
      "result" => []
    ];

    $validation_result = User::authenticate($user_name, $password);

    if (!$validation_result["ok"]) {
      $response["error"] = true;
      $response["error_message"] = $validation_result["error_message"];
      return $response;
    }

    $response["result"] = $validation_result["data"];
    return $response;
  }
}
