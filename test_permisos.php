<?php
require_once 'funcs/class.conexion.php';
require_once 'funcs/permisos_helper.php';

// Simular sesión para Andres Erazo (id=8, tipo=3)
if (!isset($_SESSION)) session_start();
$_SESSION['id_usuario'] = 8;
$_SESSION['tipo_usuario'] = 3;

$modulos = ['Dashboard', 'Zonas', 'Candidatos', 'Simpatizantes', 'Votantes', 'Líderes', 'Usuarios', 'Auditoría'];

echo "Resultados para LIDER (ID 8, Tipo 3):\n";
foreach($modulos as $m) {
    echo "$m: " . (tienePermiso(3, $m, 'ver') ? 'SI' : 'NO') . "\n";
}
?>
