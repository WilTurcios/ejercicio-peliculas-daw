<?php

namespace Models;

class Category
{
  public ?int $id = null;
  public ?string $category_name;
  private $connection;

  public function __construct(?string $category_name = null)
  {
    $this->category_name = $category_name;
    try {
      $this->connection = new \mysqli('localhost', 'root', '12345', 'movies_system');
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  public function save()
  {
    $query = "INSERT INTO categories (category_name) VALUES (?)";

    $stmt = $this->connection->prepare($query);

    if ($stmt) {
      $stmt->bind_param("s", $this->category_name);

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
    $query = "DELETE FROM categories WHERE id = $this->id;";
    $result = $this->connection->query($query);

    $deleted_category = new self();

    $deleted_category->id = $this->id;
    $deleted_category->category_name = $this->category_name;
    $response = [
      "ok" => $result,
      "data" => [
        $deleted_category
      ]
    ];

    return $response;
  }
  public function update()
  {
    $query = "UPDATE categories SET category_name = $this->category_name";
    $result = $this->connection->query($query);
    $response = [
      "ok" => false,
      "data" => []
    ];

    if ($result) {
      $updated_category = new self();
      $updated_category->id = $this->id;
      $updated_category->category_name = $this->category_name;
      $response["ok"] = true;
      $response["data"][] = $updated_category;
    };

    return $result;
  }

  public static function get_all()
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM categories;";
    $result = $connection->query($query);
    $response = [
      "ok" => false,
      "data" => []
    ];

    if ($result && $result->num_rows > 0) {
      $response["ok"] = true;
      while ($row = $result->fetch_assoc()) {
        $category = new self();
        $category->id = $row["id"];
        $category->category_name = $row["category_name"];

        $response["data"][] = $category;
      }
    }

    return $response;
  }

  public static function delete_all()
  {
    $query = "DELETE FROM categories;";
    $result = (new self())->connection->query($query);
    return $result;
  }

  public static function get_one($id)
  {
    $query = "SELECT * FROM movies WHERE id = $id";
    $result = (new self())->connection->query($query);
    $response = [
      "ok" => false,
      "data" => []
    ];

    if ($result && $result->num_rows > 0) {
      $response["ok"] = true;
      while ($row = $result->fetch_assoc()) {

        $category = new self();
        $category->id = $row["id"];
        $category->category_name = $row["category_name"];

        $response["data"][] = $category;
      }
    }

    return $response;
  }
}
