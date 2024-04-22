<?php
session_start();
// session_destroy();
require_once("./controllers/contenido.php");
require_once("./controllers/movies_controller.php");
//index principal
define("URL", "http://localhost/MVC");

$objcontenido = new cls_contenido();

require_once("vistas/admin/index.php");

//para ver ruta actual
//echo getcwd();
