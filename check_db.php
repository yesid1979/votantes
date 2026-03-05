<?php
require_once 'funcs/class.conexion.php';
$c = new Conexion();
$db = $c->get_conexion();
$s = $db->query('SHOW COLUMNS FROM zonas');
$columns = $s->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($columns);
?>
