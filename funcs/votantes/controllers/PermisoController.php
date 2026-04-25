<?php
require_once 'models/PermisoModel.php';

class PermisoController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        // Solo administrador puede gestionar permisos
        if($_SESSION['tipo_usuario'] != 1) {
            header("Location: index.php?url=dashboard/index");
            exit;
        }
        $this->model = new PermisoModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        $tipos = $this->model->getTiposUsuario();
        $usuarios = $this->model->getUsuarios();
        $modulos = $this->model->getModulos();
        require 'views/permisos/listar.php';
    }

    // Obtener permisos por Rol
    public function getPermisos() {
        header('Content-Type: application/json');
        $id_tipo = isset($_GET['id_tipo']) ? intval($_GET['id_tipo']) : 0;
        
        if($id_tipo > 0) {
            $permisos = $this->model->getPermisosByTipo($id_tipo);
            echo json_encode(array('status' => 'success', 'data' => $permisos));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Tipo de usuario no válido'));
        }
    }

    // Obtener permisos por Usuario
    public function getPermisosUsuario() {
        header('Content-Type: application/json');
        $id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0;
        
        if($id_usuario > 0) {
            $permisos = $this->model->getPermisosByUsuario($id_usuario);
            $tienePersonalizados = $this->model->tienePermisosPersonalizados($id_usuario);
            echo json_encode(array(
                'status' => 'success', 
                'data' => $permisos,
                'personalizado' => $tienePersonalizados
            ));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Usuario no válido'));
        }
    }

    // Guardar permisos de Rol
    public function guardarRol() {
        header('Content-Type: application/json');
        
        $id_tipo = isset($_POST['id_tipo']) ? intval($_POST['id_tipo']) : 0;
        $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];
        
        if($id_tipo > 0) {
            if($this->model->guardarPermisosRol($id_tipo, $permisos)) {
                echo json_encode(array('status' => 'success', 'message' => 'Permisos del rol guardados correctamente.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Error al guardar permisos.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Tipo de usuario no válido.'));
        }
    }

    // Guardar permisos de Usuario
    public function guardarUsuario() {
        header('Content-Type: application/json');
        
        $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
        $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];
        
        if($id_usuario > 0) {
            if($this->model->guardarPermisosUsuario($id_usuario, $permisos)) {
                echo json_encode(array('status' => 'success', 'message' => 'Permisos del usuario guardados correctamente.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Error al guardar permisos.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Usuario no válido.'));
        }
    }

    // Restaurar permisos del usuario a los del rol
    public function restaurarUsuario() {
        header('Content-Type: application/json');
        
        $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
        
        if($id_usuario > 0) {
            if($this->model->eliminarPermisosUsuario($id_usuario)) {
                echo json_encode(array('status' => 'success', 'message' => 'Permisos restaurados a los del rol.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Error al restaurar permisos.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Usuario no válido.'));
        }
    }
}
?>
