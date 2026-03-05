<?php
require_once "funcs/class.conexion.php";
require_once "funcs/funcs.php";
require_once "funcs/permisos_helper.php";
require_once "funcs/dashboard_stats.php";

class DashboardController {
    
    public function index() {
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: " . URL_BASE);
            exit;
        }

        $id_tipouser = $_SESSION['tipo_usuario'];
        $cedula = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : null;
        $puede_ver_todos = tienePermiso($id_tipouser, 'Votantes', 'ver_todos');
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        
        $stats = getDashboardStats($conexion, $id_tipouser, $cedula, $puede_ver_todos);
        
        require 'views/dashboard/home.php';
    }
}
?>
