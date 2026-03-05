<?php
require_once 'models/VotanteModel.php';
require_once 'funcs/permisos_helper.php';

class VotanteController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new VotanteModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        $puede_ver_todos = tienePermiso($id_tipouser, 'Votantes', 'ver_todos');
        require 'views/votantes/listar.php';
    }

    public function create() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        $lideres = $this->model->getLideresForSelect();
        $zonas = $this->model->getZonasForSelect();
        require 'views/votantes/crear.php';
    }

    public function store() {
        if ($this->model->registrarVotante($_POST)) {
            echo json_encode(['status' => 'success', 'message' => 'Votante registrado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar.']);
        }
    }

    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $votante = $this->model->getVotanteById($id);
        
        if(!$votante) {
            header("Location: index.php?url=votante/index");
            exit;
        }

        // Restriction for Leaders: Can only edit their own votantes UNLESS they have 'ver_todos'
        $puede_ver_todos = tienePermiso($_SESSION['tipo_usuario'], 'Votantes', 'ver_todos');
        if($_SESSION['tipo_usuario'] == 3 && !$puede_ver_todos && $votante['ced_lider'] != $_SESSION['cedula']) {
            header("Location: index.php?url=votante/index");
            exit;
        }

        $id_tipouser = $_SESSION['tipo_usuario'];
        $lideres = $this->model->getLideresForSelect();
        $zonas = $this->model->getZonasForSelect();
        $puestos = $this->model->getPuestosByZona($votante['zona_votante']);
        
        require 'views/votantes/editar.php';
    }

    public function update() {
        if ($this->model->actualizarVotante($_POST)) {
             echo json_encode(['status' => 'success', 'message' => 'Datos actualizados correctamente.']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Error al actualizar.']);
        }
    }

    public function delete() {
        if(isset($_POST['id'])) {
             $ids = $_POST['id'];
             if(!is_array($ids)) { $ids = array($ids); }

             // Restriction for Leaders: Can only delete their own votantes UNLESS they have 'ver_todos'
             $puede_ver_todos = tienePermiso($_SESSION['tipo_usuario'], 'Votantes', 'ver_todos');
             if($_SESSION['tipo_usuario'] == 3 && !$puede_ver_todos) {
                 foreach($ids as $id) {
                     $v = $this->model->getVotanteById($id);
                     if($v && $v['ced_lider'] != $_SESSION['cedula']) {
                         echo json_encode(['status' => 'error', 'message' => 'No tiene permiso para eliminar uno o más de los registros seleccionados.']);
                         exit;
                     }
                 }
             }

             if ($this->model->bajaVotante($ids)) {
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
        $puede_ver_todos = tienePermiso($tipo_usuario, 'Votantes', 'ver_todos');

        $ced_lider_filter = isset($requestData['ced_lider_filter']) ? $requestData['ced_lider_filter'] : '';

        $result = $this->model->getVotantesAjax($requestData, $tipo_usuario, $cedula, $puede_ver_todos, $ced_lider_filter);
        
        $dataFormatted = array();
        foreach ($result['data'] as $row) {
            $nestedData = array();
            $nestedData[] = $row["id_votante"];
            $nestedData[] = $row["ced_votante"];
            $nestedData[] = $row["nom_votante"];
            $nestedData[] = $row["dir_votante"];
            $nestedData[] = $row["barrio_votante"];
            $nestedData[] = $row["comuna_votante"];
            $nestedData[] = $row["email_votante"];
            $nestedData[] = $row["cel_votante"];
            $nestedData[] = $row["lider"];

            $nestedData[] = '<div class="text-center">
                             <a href="index.php?url=votante/edit&id='.$row['id_votante'].'" class="btn btn-sm btn-info text-white" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                             </a>
                             </div>';
            
            $nestedData[] = '<div class="text-center"><input type="checkbox" name="student_id[]" class="delete_student form-check-input" value="'.$row['id_votante'].'"></div>';
            
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
