<?php
require_once 'models/JacModel.php';
require_once 'funcs/permisos_helper.php';

class JacController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new JacModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        $puede_ver_todos = tienePermiso($id_tipouser, 'JAC', 'ver_todos');
        require 'views/jac/listar.php';
    }

    public function create() {
        $comisiones = $this->model->getComisiones();
        $lideres = $this->model->getLideresForSelect();
        require 'views/jac/crear.php';
    }

    public function store() {
        if ($this->model->registrarAfiliado($_POST)) {
            echo json_encode(array('status' => 'success', 'message' => 'Afiliado registrado correctamente.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error al registrar. Verifique si la cédula ya existe.'));
        }
    }

    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $afiliado = $this->model->getAfiliadoById($id);
        
        if(!$afiliado) {
            header("Location: index.php?url=jac/index");
            exit;
        }

        $comisiones = $this->model->getComisiones();
        $lideres = $this->model->getLideresForSelect();
        require 'views/jac/editar.php';
    }

    public function update() {
        if ($this->model->actualizarAfiliado($_POST)) {
             echo json_encode(array('status' => 'success', 'message' => 'Datos actualizados correctamente.'));
        } else {
             echo json_encode(array('status' => 'error', 'message' => 'Error al actualizar.'));
        }
    }

    public function delete() {
        if(isset($_POST['id'])) {
             $ids = $_POST['id'];
             if(!is_array($ids)) { $ids = array($ids); }

             if ($this->model->bajaAfiliados($ids)) {
                 echo json_encode(array('status' => 'success', 'message' => 'Registros eliminados correctamente.'));
             } else {
                 echo json_encode(array('status' => 'error', 'message' => 'Error al eliminar registros.'));
             }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'No se seleccionaron registros.'));
        }
    }

    public function ajaxListar() {
        $requestData = $_REQUEST;
        $tipo_usuario = $_SESSION['tipo_usuario'];
        $cedula = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : '';
        $puede_ver_todos = tienePermiso($tipo_usuario, 'JAC', 'ver_todos');

        $result = $this->model->getAfiliadosAjax($requestData, $tipo_usuario, $cedula, $puede_ver_todos);
        
        $dataFormatted = array();
        foreach ($result['data'] as $row) {
            $nestedData = array();
            $nestedData[] = $row["id_afiliado"];
            $nestedData[] = $row["ced_afiliado"];
            $nestedData[] = $row["nom_afiliado"];
            $nestedData[] = $row["nombre_comision"];
            $nestedData[] = $row["tel_afiliado"];
            $nestedData[] = $row["lider"];

            $nestedData[] = '<div class="text-center">
                             <a href="index.php?url=jac/edit&id='.$row['id_afiliado'].'" class="btn btn-sm btn-info text-white" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                             </a>
                             </div>';
            
            $nestedData[] = '<div class="text-center"><input type="checkbox" name="item_id[]" class="delete_item form-check-input" value="'.$row['id_afiliado'].'"></div>';
            
            $dataFormatted[] = $nestedData;
        }

        echo json_encode(array(
            "draw"            => isset($requestData['draw']) ? intval($requestData['draw']) : 0,
            "recordsTotal"    => intval($result['totalData']),
            "recordsFiltered" => intval($result['totalFiltered']),
            "data"            => $dataFormatted
        ));
        exit;
    }
}
