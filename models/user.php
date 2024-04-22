<?php

namespace Models;

require_once "utils/encript.php";

use Utils\encryptor;

class User
{
  private $connection;

  public function __construct(
    public ?int $id = null,
    public ?string $user_name = null,
    public ?string $password = null,
    public ?string $email = null,
    public ?bool $is_admin = null
  ) {
    try {
      $this->connection = new \mysqli('localhost', 'root', '12345', 'movies_system');
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }


  public function save()
  {
    $query = "
      INSERT INTO users 
      (user_name, password, email, isAdmin)     
      VALUES (?,?,?,?);
    ";
    $stmt = $this->connection->prepare($query);

    $encryted_password = encryptor::encrypt($this->password);
    if ($stmt) {
      $stmt->bind_param(
        "sssi",
        $this->user_name,
        $encryted_password,
        $this->email,
        $this->is_admin
      );

      $result = $stmt->execute();

      if ($result) {
        $this->id = $this->connection->insert_id;
      } else {
        echo "Error al ejecutar la consulta: " . $stmt->error;
      }

      $stmt->close();

      return $result;
    } else {
      echo "Error al preparar la consulta: " . $this->connection->error;

      return false;
    }
  }

  public function delete()
  {
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $this->connection->prepare($query);

    if ($stmt) {
      $stmt->bind_param("i", $this->id);
      $result = $stmt->execute();

      $stmt->close();

      $response = [
        "ok" => $result,
        "data" => [
          "id" => $this->id,
          "user_name" => $this->user_name,
          "password" => $this->password,
          "email" => $this->email,
          "isAdmin" => $this->is_admin,
        ]
      ];

      return $response;
    } else {
      echo "Error al preparar la consulta: " . $this->connection->error;
      return false;
    }
  }

  public function update()
  {
    $query = "
      UPDATE users SET 
        user_name = ?,
        password = ?,
        email = ?,
        isAdmin = ?
      WHERE id = ?
    ";
    $stmt = $this->connection->prepare($query);

    if ($stmt) {
      $stmt->bind_param(
        "ssssi",
        $this->user_name,
        $this->password,
        $this->email,
        $this->is_admin,
        $this->id
      );

      $result = $stmt->execute();
      $stmt->close();

      $updated_user = new self($this->id, $this->user_name, $this->password, $this->email, $this->is_admin);

      $response = [
        "ok" => $result,
        "data" => [
          $updated_user
        ]
      ];

      return $response;
    } else {
      echo "Error al preparar la consulta: " . $this->connection->error;
      return false;
    }
  }


  public static function get_all()
  {

    $connection = (new self())->connection;
    $query = "SELECT * FROM users;";
    $result = $connection->query($query);
    $response = [
      "ok" => false,
      "data" => []
    ];

    if ($result && $result->num_rows > 0) {
      $response["ok"] = true;
      while ($row = $result->fetch_assoc()) {
        $user = new self(
          $row["id"],
          $row["user_name"],
          $row["password"],
          $row["email"],
          $row["isAdmin"]
        );

        $response["data"][] = $user;
      }
    }

    return $response;
  }


  public static function delete_all()
  {
    $query = "DELETE FROM users;";
    $result = (new self())->connection->query($query);
    return $result;
  }


  public static function get_one($user_name)
  {
    $connection = new \mysqli('localhost', 'root', '12345', 'movies_system');
    $query = "SELECT * FROM users WHERE user_name = ?";
    $stmt = $connection->prepare($query);

    if ($stmt) {
      $stmt->bind_param("s", $user_name);
      $stmt->execute();
      $result = $stmt->get_result();
      $response = [
        "ok" => false,
        "data" => []
      ];

      if ($result && $result->num_rows > 0) {
        $response["ok"] = true;
        while ($row = $result->fetch_assoc()) {
          $user = new self(
            $row["id"],
            $row["user_name"],
            $row["password"],
            $row["email"],
            $row["isAdmin"]
          );

          $response["data"][] = $user;
        }
      }

      $stmt->close();
      return $response;
    } else {
      echo "Error al preparar la consulta: " . $connection->error;
      return [
        "ok" => false,
        "data" => []
      ];
    }
  }

  public static function authenticate($user_name, $password)
  {
    $response = [
      "ok" => true,
      "error_message" => null,
      "data" => [],
    ];

    $decryptedPassword = encryptor::decrypt($password);

    $user = self::get_one($user_name);

    if ($user["ok"] && $decryptedPassword !== false) {
      if (!empty($user["data"])) {
        $userDecryptedPassword = encryptor::decrypt($user["data"][0]->password);

        if (
          $decryptedPassword !== $userDecryptedPassword
        ) {
          $response["ok"] = false;
          $response["error_message"] = "Contraseña incorrecta. Por favor, inténtalo de nuevo.";
        } else {
          $response["data"] = $user["data"];
        }
      } else {
        $response["ok"] = false;
        $response["error_message"] = "Usuario no encontrado.";
      }
    } else {
      $response["ok"] = false;
      $response["error_message"] = "Error al buscar el usuario o al desencriptar la contraseña.";
    }

    return $response;
  }
}
