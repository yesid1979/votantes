<?php
require_once 'models/LiderModel.php';
require_once 'models/VotanteModel.php';
require_once 'models/SimpatizanteModel.php';

class ReporteController {
    private $liderModel;
    private $votanteModel;
    private $simpatizanteModel;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }

        require_once 'funcs/permisos_helper.php';
        if (!tienePermiso($_SESSION['tipo_usuario'], 'Reportes G.', 'ver')) {
            header("Location: index.php?url=dashboard/index");
            exit;
        }

        $this->liderModel = new LiderModel();
        $this->votanteModel = new VotanteModel();
        $this->simpatizanteModel = new SimpatizanteModel();
    }

    public function index() {
        require 'views/reporte/index.php';
    }

    public function ajaxLideres() {
        $data = $this->liderModel->getLideresAjax($_REQUEST);
        echo json_encode($data);
        exit;
    }

    public function ajaxVotantes() {
        $tipo_usuario = $_SESSION['tipo_usuario'];
        $cedula = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : '';
        // Administradores (1) y Digitadores (2) pueden ver todo
        $puede_ver_todos = ($tipo_usuario == 1 || $tipo_usuario == 2);
        
        $data = $this->votanteModel->getVotantesAjax($_REQUEST, $tipo_usuario, $cedula, $puede_ver_todos);
        echo json_encode($data);
        exit;
    }

    public function ajaxSimpatizantes() {
        $tipo_usuario = $_SESSION['tipo_usuario'];
        $cedula = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : '';
        
        $data = $this->simpatizanteModel->getSimpatizantesAjax($_REQUEST, $tipo_usuario, $cedula);
        echo json_encode($data);
        exit;
    }
}
?>
