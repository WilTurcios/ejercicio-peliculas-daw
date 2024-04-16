<?php

namespace Models;

class Movie
{
  public ?int $id = null;
  public ?string $title;
  public ?string $original_title;
  public ?int $year;
  public ?int $duration;
  public ?string $synopsis;
  public ?string $director;
  public ?array $writers;
  public ?string $image;
  private $connection;

  public function __construct(
    ?string $title = null,
    ?string $original_title = null,
    ?int $year = null,
    ?int $duration = null,
    ?string $synopsis = null,
    ?string $director = null,
    ?array $writers = null,
    ?string $image = null
  ) {
    $this->title = $title;
    $this->original_title = $original_title;
    $this->year = $year;
    $this->duration = $duration;
    $this->synopsis = $synopsis;
    $this->director = $director;
    $this->writers = $writers;
    $this->image = $image;

    try {
      $this->connection = new \mysqli('localhost', 'root', '12345', 'movies_system');
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  public function save()
  {
    $writers_string = implode(", ", $this->writers);
    $query = "
      INSERT INTO movies 
      (title, original_title, year, duration, synopsis, director, writers, image)     
      VALUES (?,?,?,?,?,?,?,?);";
    $stmt = $this->connection->prepare($query);

    if ($stmt) {
      $stmt->bind_param(
        "ssiissss",
        $this->title,
        $this->original_title,
        $this->year,
        $this->duration,
        $this->synopsis,
        $this->director,
        $writers_string,
        $this->image,
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
    $query = "DELETE FROM movies WHERE id = $this->id;";
    $result = $this->connection->query($query);

    $response = [
      "ok" => $result,
      "data" => [
        "title" => $this->title,
        "original_title" => $this->original_title,
        "year" => $this->year,
        "duration" => $this->duration,
        "synopsis" => $this->synopsis,
        "director" => $this->director,
        "writers" => $this->writers,
      ]
    ];

    return $response;
  }
  public function update()
  {
    $writers_string = implode(", ", $this->writers);
    $query = "
      UPDATE movies SET 
        title = $this->title,
        original_title = $this->original_title,
        year = $this->year,
        duration = $this->duration,
        director = $this->director,
        writers = $writers_string,
        image = $this->image
      WHERE id = $this->id
    ;";
    $result = $this->connection->query($query);
    $response = [
      "ok" => $result,
      "data" => [
        [
          "title" => $this->title,
          "original_title" => $this->original_title,
          "year" => $this->year,
          "duration" => $this->duration,
          "synopsis" => $this->synopsis,
          "director" => $this->director,
          "writers" => $this->writers,
          "image" => $this->image,
        ]
      ]
    ];

    return $response;
  }

  public static function get_all()
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM movies;";
    $result = $connection->query($query);
    $response = [
      "ok" => false,
      "data" => []
    ];

    if ($result && $result->num_rows > 0) {
      $response["ok"] = true;
      while ($row = $result->fetch_assoc()) {
        $writers_array = explode(", ", $row["writers"]);

        $movie = new self(
          $row["title"],
          $row["original_title"],
          $row["year"],
          $row["duration"],
          $row["synopsis"],
          $row["director"],
          $writers_array,
          $row["image"]
        );

        $response["data"][] = $movie;
      }
    }

    return $response;
  }

  public static function delete_all()
  {
    $query = "DELETE FROM movies;";
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
        $writers_array = implode(", ", $row["writers"]);

        $movie = new self(
          $row["id"],
          $row["title"],
          $row["original_title"],
          $row["year"],
          $row["duration"],
          $row["synopsis"],
          $row["director"],
          $writers_array,
          $row["image"]
        );

        $response["data"][] = $movie;
      }
    }

    return $response;
  }
}
