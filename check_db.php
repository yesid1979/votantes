<?php
require_once 'funcs/class.conexion.php';
$m = new Conexion();
$db = $m->get_conexion();
if(!$db) {
    echo "Fallo de conexión";
    exit;
}
echo "Conectado\n";
$s = $db->query("SELECT count(*) as total, estado_zona FROM zonas GROUP BY estado_zona");
while($r = $s->fetch(PDO::FETCH_ASSOC)) {
    print_r($r);
}
