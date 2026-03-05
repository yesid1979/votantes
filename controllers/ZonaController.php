<?php
require_once 'models/ZonaModel.php';

class ZonaController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new ZonaModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/zonas/listar.php';
    }

    public function create() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/zonas/crear.php';
    }

    public function store() {
        if ($this->model->registrarZona($_POST)) {
            echo json_encode(['status' => 'success', 'message' => 'Zona agregada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar.']);
        }
    }

    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $zona = $this->model->getZonaById($id);
        
        if(!$zona) {
            header("Location: index.php?url=zona/index");
            exit;
        }

        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/zonas/editar.php';
    }

    public function update() {
        if ($this->model->actualizarZona($_POST)) {
             echo json_encode(['status' => 'success', 'message' => 'Zona actualizada correctamente.']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Error al actualizar.']);
        }
    }

    public function delete() {
        if(isset($_POST['id'])) {
             if ($this->model->bajaZona($_POST['id'])) {
                 echo json_encode(['status' => 'success', 'message' => 'Registros eliminados correctamente.']);
             } else {
                 echo json_encode(['status' => 'error', 'message' => 'Error al eliminar registros.']);
             }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se seleccionaron registros.']);
        }
    }

    public function ajaxListar() {
        $requestData = $_REQUEST;
        $result = $this->model->getZonasAjax($requestData);
        
        $dataFormatted = array();
        foreach ($result['data'] as $row) {
            $nestedData = array();
            $nestedData[] = $row["id_zona"];
            $nestedData[] = $row["num_zona"];
            $nestedData[] = $row["pues_zona"];
            $nestedData[] = $row["mun_zona"];
            $nestedData[] = $row["nom_puesto"];
            $nestedData[] = $row["comuna_zona"];
            $nestedData[] = $row["dir_zona"];
            $nestedData[] = $row["barr_zona"];
            $nestedData[] = $row["estado_zona"];

            $nestedData[] = '<div class="text-center">
                             <a href="index.php?url=zona/edit&id='.$row['id_zona'].'" class="btn btn-sm btn-info text-white" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                             </a>
                             </div>';
            
            $nestedData[] = '<div class="text-center"><input type="checkbox" name="student_id[]" class="delete_student form-check-input" value="'.$row['id_zona'].'"></div>';
            
            $dataFormatted[] = $nestedData;
        }

        $draw = isset($requestData['draw']) ? intval($requestData['draw']) : 0;

        $json_data = array(
            "draw"            => $draw,
            "recordsTotal"    => intval($result['totalData']),
            "recordsFiltered" => intval($result['totalFiltered']),
            "data"            => $dataFormatted
        );

        echo json_encode($json_data);
        exit;
    }
}
?>
