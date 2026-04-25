<?php
require_once 'funcs/class.conexion.php';
$c = new Conexion();
$db = $c->get_conexion();
$s = $db->query('SHOW TABLES');
echo implode(", ", $s->fetchAll(PDO::FETCH_COLUMN));
