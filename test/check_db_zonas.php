<?php
require_once 'funcs/class.conexion.php';
$c = new Conexion();
$db = $c->get_conexion();
$s = $db->query('SELECT dpto_zona, mun_zona, num_zona, pues_zona FROM zonas LIMIT 10');
echo json_encode($s->fetchAll(PDO::FETCH_ASSOC));
?>
