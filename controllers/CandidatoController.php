<?php
require_once 'models/CandidatoModel.php';

class CandidatoController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new CandidatoModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/candidatos/listar.php';
    }

    public function create() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/candidatos/crear.php';
    }

    public function store() {
        if ($this->model->registrarCandidato($_POST, $_FILES)) {
            echo json_encode(['status' => 'success', 'message' => 'Candidato registrado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar.']);
        }
    }

    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $candidato = $this->model->getCandidatoById($id);
        
        if(!$candidato) {
            header("Location: index.php?url=candidato/index");
            exit;
        }

        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/candidatos/editar.php';
    }

    public function update() {
        if ($this->model->actualizarCandidato($_POST, $_FILES)) {
             echo json_encode(['status' => 'success', 'message' => 'Candidato actualizado correctamente.']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Error al actualizar.']);
        }
    }

    public function delete() {
        if(isset($_POST['id'])) {
             if ($this->model->bajaCandidato($_POST['id'])) {
                 echo json_encode(['status' => 'success', 'message' => 'Registros eliminados correctamente.']);
             } else {
                 echo json_encode(['status' => 'error', 'message' => 'Error al eliminar registros.']);
             }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se seleccionaron registros.']);
        }
    }

    public function toggleEstado() {
        if(isset($_POST['id'])) {
             if ($this->model->toggleEstadoCandidato($_POST['id'])) {
                 echo json_encode(['status' => 'success', 'message' => 'Estado de los candidatos actualizados correctamente.']);
             } else {
                 echo json_encode(['status' => 'error', 'message' => 'Error al cambiar estado de los candidatos.']);
             }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se seleccionaron registros.']);
        }
    }

    public function ajaxListar() {
        $requestData = $_REQUEST;
        $result = $this->model->getCandidatosAjax($requestData);
        
        $dataFormatted = array();
        foreach ($result['data'] as $row) {
            $nestedData = array();
            $nestedData[] = $row["id_candidato"];
            
            $logoPath = "images/partidos/" . $row['logo_partido'];
            $nestedData[] = '<img src="'.$logoPath.'" class="img-thumbnail" style="height: 40px;">';
            
            $nestedData[] = $row["nom_partido"];
            $nestedData[] = $row["num_candidato"];
            
            $fotoPath = "images/partidos/" . $row['foto_candidato'];
            $nestedData[] = '<img src="'.$fotoPath.'" class="rounded-circle" style="height: 40px; width:40px; object-fit:cover;"> ' . $row["nom_candidato"];
            
            $nestedData[] = $row["aspirante_a"];
            $nestedData[] = $row["estado_candidato"] == 'Activo' ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>';

            $nestedData[] = '<div class="text-center">
                             <a href="index.php?url=candidato/edit&id='.$row['id_candidato'].'" class="btn btn-sm btn-info text-white" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                             </a>
                             </div>';
            
            $nestedData[] = '<div class="text-center"><input type="checkbox" name="student_id[]" class="delete_student form-check-input" value="'.$row['id_candidato'].'"></div>';
            
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
