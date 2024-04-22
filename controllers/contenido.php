<?php

class cls_contenido
{
  private $pages = [
    "addcategories" => "vistas/admin/categories/addcategories.php",
    "addmovies" => "vistas/admin/movies/addmovies.php",
    "addusers" => "vistas/admin/users/addusers.php",
    "login" => "vistas/login/login.php",
    "inicio" => "vistas/inicio.php"
  ];
  public function mostrar_archivo()
  {
    $pagina = "";
    $url = isset($_GET["url"]) ? $_GET["url"] : null;
    $url = explode('/', $url);
    if (!isset($_SESSION["user"])) return $this->pages["login"];
    if ($url[0] == null) {
      $pagina = "vistas/inicio.php";
    } else {

      if (array_key_exists($url[0], $this->pages)) {
        $pagina = $this->pages[$url[0]];
      } else {
        $pagina = "vistas/e404.php";
      }
    }

    return $pagina;
  }
}

[
  "controllers" => [
    "categories_controller.php",
    "contenido.php",
    "movies_controller.php",
  ],
  "modelos" => [
    "category.php",
    "connection.php",
    "movies.php",
  ],
  "vistas" => [
    "admin" => [
      "categories" => ["addcategories.php"],
      "movies" => ["addmovies.php"],
      "index.php",
      "menu.php"
    ],
    "usuario" => ["index.php"],
    "404.php",
    "inicio.php",
  ],
  ".htaccess",
  "index.php"
];
