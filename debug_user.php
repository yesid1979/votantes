<?php
require_once 'funcs/class.conexion.php';
$c = new Conexion();
$db = $c->get_conexion();
if(!$db) die('Connection failed');

echo "--- USUARIO ANDRES ERAZO ---\n";
$stmt = $db->prepare('SELECT id, nombre, id_tipo FROM usuarios WHERE nombre LIKE ?');
$stmt->execute(['%Andres Erazo%']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($user);

if ($user) {
    echo "\n--- PERMISOS PERSONALIZADOS ---\n";
    $stmt = $db->prepare('SELECT id_modulo, puede_ver FROM permisos_usuario WHERE id_usuario = ?');
    $stmt->execute([$user['id']]);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['id_modulo'] . " - " . $row['puede_ver'] . "\n";
    }
}
?>
