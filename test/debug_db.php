<?php
require_once 'funcs/class.conexion.php';
$c = new Conexion();
$db = $c->get_conexion();
if(!$db) die('Connection failed');

echo "--- MODULOS ---\n";
$stmt = $db->query('SELECT id_modulo, nombre FROM modulos');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id_modulo'] . " - " . $row['nombre'] . "\n";
}

echo "\n--- PERMISOS LIDER (3) ---\n";
$stmt = $db->query('SELECT id_modulo, puede_ver FROM permisos WHERE id_tipo = 3');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id_modulo'] . " - " . $row['puede_ver'] . "\n";
}
?>
