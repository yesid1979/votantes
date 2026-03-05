<?php
require_once "class.conexion.php";

$modelo = new Conexion();
$conexion = $modelo->get_conexion();

// For backward compatibility during refactor, though we aim to replace usage.
// If any script mistakenly still tries to use $mysqli, it will fail, which is good for finding bugs.
?>