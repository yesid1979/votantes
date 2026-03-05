<?php
require('funcs/class.conexion.php');
ini_set('display_errors', 1); error_reporting(E_ALL);
$c = new Conexion();
$db = $c->get_conexion();
require('models/VotanteModel.php');
$m = new VotanteModel();

$requestData = [
   'draw' => 1,
   'start' => 0,
   'length' => 10,
   'search' => ['value' => ''],
   // ced_lider_filter IS OMITTED intentionally to mimic list page behavior
];

$tipo_usuario = 3; 
$cedula = '34606215'; // Lucia's cedula (she is lider)
$puede_ver_todos = false;
$ced_lider_filter = isset($requestData['ced_lider_filter']) ? $requestData['ced_lider_filter'] : '';

$res = $m->getVotantesAjax($requestData, $tipo_usuario, $cedula, $puede_ver_todos, $ced_lider_filter);
var_dump($res);
?>
