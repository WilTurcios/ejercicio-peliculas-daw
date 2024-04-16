<?php

namespace Models;

class User
{
  public ?int $id = null;
  public ?string $user_name;
  public ?string $password;
  public ?string $email;
  public ?bool $is_admin;
  private $connection;

  public function __construct(
    ?int $id = null,
    ?string $user_name = null,
    ?string $password = null,
    ?string $email = null,
    ?bool $is_admin = null
  ) {
    $this->id = $id;
    $this->user_name = $user_name;
    $this->password = $password;
    $this->email = $email;
    $this->is_admin = $is_admin;

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

    if ($stmt) {
      $stmt->bind_param(
        "sssi",
        $this->user_name,
        $this->password,
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


  public static function get_one($id)
  {
    $query = "SELECT * FROM users WHERE id = $id";
    $result = (new self())->connection->query($query);
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

        $response["data"] = $user;
      }
    }

    return $response;
  }
}
