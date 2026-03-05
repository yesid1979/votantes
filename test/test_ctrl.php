<?php
require('index.php'); // which loads autoloader
$_SESSION['id_usuario'] = 5;
$_SESSION['tipo_usuario'] = 2; // Digitador
$_SESSION['cedula'] = '29706426';

$_REQUEST = [
    'draw' => 1,
    'start' => 0,
    'length' => 10,
    'search' => ['value' => ''],
    'order' => [['column' => 0, 'dir' => 'asc']]
];

$vc = new VotanteController();
$vc->ajaxListar();
?>
