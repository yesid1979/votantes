<?php
require_once "funcs/class.conexion.php";
$conexion = new Conexion();
$db = $conexion->get_conexion();
$res = $db->query("SELECT DISTINCT dpto_zona FROM zonas WHERE dpto_zona LIKE '%VALLE%'")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($res);
