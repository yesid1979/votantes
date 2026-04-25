<?php
require_once 'models/RolModel.php';

class RolController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
            header("Location: index.php");
            exit;
        }
        $this->model = new RolModel();
    }

    public function index() {
        $roles = $this->model->getRoles();
        require 'views/roles/listar.php';
    }

    public function store() {
        $tipo = $_POST['tipo'];
        $id = $this->model->registrarRol($tipo);
        if ($id) {
            // Inicializar permisos básicos para el nuevo rol (todos en 0)
            $db = (new Conexion())->get_conexion();
            $modulos = $db->query("SELECT id_modulo FROM modulos")->fetchAll(PDO::FETCH_ASSOC);
            foreach($modulos as $m) {
                $db->prepare("INSERT INTO permisos (id_tipo, id_modulo, puede_ver, puede_crear, puede_editar, puede_eliminar) VALUES (?, ?, 0, 0, 0, 0)")
                   ->execute([$id, $m['id_modulo']]);
            }
            echo json_encode(array('status' => 'success', 'message' => 'Rol creado correctamente.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error al crear rol.'));
        }
    }

    public function update() {
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
        if ($this->model->actualizarRol($id, $tipo)) {
            echo json_encode(array('status' => 'success', 'message' => 'Rol actualizado.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error al actualizar.'));
        }
    }

    public function delete() {
        $id = $_POST['id'];
        // No permitir borrar Admin
        if($id == 1) {
            echo json_encode(array('status' => 'error', 'message' => 'No se puede eliminar el rol de Administrador.'));
            return;
        }
        
        $result = $this->model->eliminarRol($id);
        if ($result === "exists") {
            echo json_encode(array('status' => 'error', 'message' => 'No se puede eliminar porque existen usuarios con este rol.'));
        } elseif ($result) {
            echo json_encode(array('status' => 'success', 'message' => 'Rol eliminado.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error al eliminar.'));
        }
    }
}
