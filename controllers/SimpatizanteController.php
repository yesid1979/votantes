<?php
require_once 'models/SimpatizanteModel.php';

class SimpatizanteController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new SimpatizanteModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/simpatizantes/listar.php';
    }

    public function create() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        $zonas = $this->model->getZonasForSelect();
        require 'views/simpatizantes/crear.php';
    }

    public function store() {
        if ($this->model->registrarSimpatizante($_POST)) {
            echo json_encode(['status' => 'success', 'message' => 'Simpatizante registrado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar.']);
        }
    }

    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $simpatizante = $this->model->getSimpatizanteById($id);
        
        if(!$simpatizante) {
            header("Location: index.php?url=simpatizante/index");
            exit;
        }

        $id_tipouser = $_SESSION['tipo_usuario'];
        $zonas = $this->model->getZonasForSelect();
        $puestos = $this->model->getPuestosByZona($simpatizante['zona_simpatizante']);
        
        require 'views/simpatizantes/editar.php';
    }

    public function update() {
        if ($this->model->actualizarSimpatizante($_POST)) {
             echo json_encode(['status' => 'success', 'message' => 'Datos actualizados correctamente.']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Error al actualizar.']);
        }
    }

    public function delete() {
        if(isset($_POST['id'])) {
             if ($this->model->bajaSimpatizante($_POST['id'])) {
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
        $tipo_usuario = $_SESSION['tipo_usuario'];
        $cedula = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : '';

        $result = $this->model->getSimpatizantesAjax($requestData, $tipo_usuario, $cedula);
        
        $dataFormatted = array();
        foreach ($result['data'] as $row) {
            $nestedData = array();
            $nestedData[] = $row["id_simpatizante"];
            $nestedData[] = $row["ced_simpatizante"];
            $nestedData[] = $row["nom_simpatizante"];
            $nestedData[] = $row["dir_simpatizante"];
            $nestedData[] = $row["barrio_simpatizante"];
            $nestedData[] = $row["comuna_simpatizante"];
            $nestedData[] = $row["cel_simpatizante"];

            $nestedData[] = '<div class="text-center">
                             <a href="index.php?url=simpatizante/edit&id='.$row['id_simpatizante'].'" class="btn btn-sm btn-info text-white" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                             </a>
                             </div>';
            
            $nestedData[] = '<div class="text-center"><input type="checkbox" name="student_id[]" class="delete_student form-check-input" value="'.$row['id_simpatizante'].'"></div>';
            
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

    public function ajaxGetPuestos() {
        $num_zona = $_POST['num_zona'];
        $puestos = $this->model->getPuestosByZona($num_zona);
        
        echo '<option value="">Seleccione Puesto</option>';
        if ($puestos) {
            foreach ($puestos as $puesto) {
                echo '<option value="'.$puesto['pues_zona'].'">'.$puesto['pues_zona'].'</option>';
            }
        }
        exit;
    }

    public function ajaxGetPuestoInfo() {
        $num_zona = $_POST['CboNum_zona'];
        $pues_zona = $_POST['CboNum_puesto'];
        
        $info = $this->model->getPuestoInfo($num_zona, $pues_zona);
        
        if ($info) {
            echo json_encode(array(
                'nom_puesto' => $info['nom_puesto'],
                'dir_puesto' => $info['dir_zona']
            ));
        } else {
            echo json_encode(array('nom_puesto'=>'', 'dir_puesto'=>''));
        }
        exit;
    }
}
?>
