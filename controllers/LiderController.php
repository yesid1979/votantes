<?php
require_once 'models/LiderModel.php';

class LiderController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new LiderModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/lideres/listar.php';
    }

    public function create() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        $zonas = $this->model->getZonasForSelect();
        require 'views/lideres/crear.php';
    }

    public function store() {
        if ($this->model->registrarLider($_POST)) {
            echo json_encode(array('status' => 'success', 'message' => 'Líder registrado correctamente.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error al registrar.'));
        }
    }

    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $lider = $this->model->getLiderById($id);
        
        if(!$lider) {
            header("Location: index.php?url=lider/index");
            exit;
        }

        $id_tipouser = $_SESSION['tipo_usuario'];
        $zonas = $this->model->getZonasForSelect();
        $puestos = $this->model->getPuestosByZona($lider['zona_lider']);
        
        require 'views/lideres/editar.php';
    }

    public function update() {
        if ($this->model->actualizarLider($_POST)) {
             echo json_encode(array('status' => 'success', 'message' => 'Datos actualizados correctamente.'));
        } else {
             echo json_encode(array('status' => 'error', 'message' => 'Error al actualizar.'));
        }
    }

    public function delete() {
        if(isset($_POST['id'])) {
             if ($this->model->bajaLider($_POST['id'])) {
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
        $result = $this->model->getLideresAjax($requestData);
        
        $dataFormatted = array();
        foreach ($result['data'] as $row) {
            $nestedData = array();
            $nestedData[] = $row["id_lider"];
            $nestedData[] = $row["ced_lider"];
            $nestedData[] = $row["nom_lider"];
            $nestedData[] = $row["dir_lider"];
            $nestedData[] = $row["barrio_lider"];
            $nestedData[] = $row["comuna_lider"];
            $nestedData[] = $row["email_lider"];
            $nestedData[] = $row["cel_lider"];
            
            $nestedData[] = '<div class="text-center">
                             <a href="index.php?url=lider/edit&id='.$row['id_lider'].'" class="btn btn-sm btn-info text-white" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                             </a>
                             </div>';
            
            $nestedData[] = '<div class="text-center"><input type="checkbox" name="student_id[]" class="delete_student form-check-input" value="'.$row['id_lider'].'"></div>';
            
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

    public function ajaxGetZonas() {
        $zonas = $this->model->getZonasForSelect();
        echo '<option value="">Seleccione Zona</option>';
        if ($zonas) {
            foreach ($zonas as $zona) {
                echo '<option value="'.$zona['num_zona'].'">'.$zona['num_zona'].'</option>';
            }
        }
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
