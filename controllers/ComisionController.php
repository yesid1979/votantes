<?php
require_once 'models/ComisionModel.php';

class ComisionController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new ComisionModel();
    }

    public function index() {
        $comisiones = $this->model->getComisiones();
        require 'views/comisiones/listar.php';
    }

    public function store() {
        $nombre = $_POST['nombre_comision'];
        if ($this->model->registrarComision($nombre)) {
            echo json_encode(array('status' => 'success', 'message' => 'Comisión creada correctamente.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error al crear la comisión.'));
        }
    }

    public function update() {
        $id = $_POST['id_comision'];
        $nombre = $_POST['nombre_comision'];
        if ($this->model->actualizarComision($id, $nombre)) {
            echo json_encode(array('status' => 'success', 'message' => 'Comisión actualizada.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error al actualizar.'));
        }
    }

    public function delete() {
        $id = $_POST['id'];
        $result = $this->model->eliminarComision($id);
        if ($result === "exists") {
            echo json_encode(array('status' => 'error', 'message' => 'No se puede eliminar la comisión porque tiene afiliados vinculados.'));
        } elseif ($result) {
            echo json_encode(array('status' => 'success', 'message' => 'Comisión eliminada.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error al eliminar.'));
        }
    }
}
