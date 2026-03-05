<?php
require('funcs/class.conexion.php');
ini_set('display_errors', 1); error_reporting(E_ALL);
$c = new Conexion();
$db = $c->get_conexion();

// The problematic logic
$tipo_usuario = 2; // Digitador
$puede_ver_todos = true;
$cedula = '1234';
$ced_lider_filter = '';

$sqlTotal = "SELECT count(a.id_votante) as total FROM votantes a LEFT JOIN lideres b ON a.ced_lider=b.ced_lider ";
if (!empty($ced_lider_filter)) {
    $sqlTotal .= "WHERE a.ced_lider = :ced_lider_filter ";
} else if ($tipo_usuario == 3 && !$puede_ver_todos) {
    $sqlTotal .= "WHERE a.ced_lider = :cedula";
}

$stmtTotal = $db->prepare($sqlTotal);
if (!empty($ced_lider_filter)) { $stmtTotal->bindValue(':ced_lider_filter', $ced_lider_filter); }
else if ($tipo_usuario == 3 && !$puede_ver_todos) { $stmtTotal->bindValue(':cedula', $cedula); }
$stmtTotal->execute();
$totalData = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
echo "Total data: $totalData\n";

$sqlBase = "FROM votantes a LEFT JOIN lideres b ON a.ced_lider=b.ced_lider WHERE 1=1 ";
$params = array();

if (!empty($ced_lider_filter)) {
    $sqlBase .= "AND a.ced_lider = :ced_lider_filter ";
    $params[':ced_lider_filter'] = $ced_lider_filter;
} else if ($tipo_usuario == 3 && !$puede_ver_todos) {
    $sqlBase .= "AND a.ced_lider = :cedula ";
    $params[':cedula'] = $cedula;
}

$sqlData = "SELECT a.id_votante, a.ced_votante, a.nom_votante, a.dir_votante, a.barrio_votante, a.comuna_votante, a.email_votante, a.cel_votante, a.ced_lider, b.nom_lider as lider " . $sqlBase;
$sqlData .= " LIMIT 0, 10";

$stmtData = $db->prepare($sqlData);
foreach($params as $key => $val) { $stmtData->bindValue($key, $val); }
$stmtData->execute();
$data = $stmtData->fetchAll(PDO::FETCH_ASSOC);

echo "Data rows: " . count($data) . "\n";
print_r($data);
?>
