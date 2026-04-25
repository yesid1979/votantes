<?php
require_once 'funcs/class.conexion.php';
$c = new Conexion();
$db = $c->get_conexion();
$s = $db->query('DESCRIBE tipo_usuario');
var_dump($s->fetchAll(PDO::FETCH_ASSOC));
