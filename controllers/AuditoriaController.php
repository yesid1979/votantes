<?php
require_once 'models/AuditoriaModel.php';

class AuditoriaController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new AuditoriaModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/auditoria/listar.php';
    }

    public function ajaxListar() {
        $requestData = $_REQUEST;
        $result = $this->model->getAuditoriaAjax($requestData);
        
        $dataFormatted = array();
        foreach ($result['data'] as $row) {
            $nestedData = array();
            $nestedData[] = $row["lastnro"];
            $nestedData[] = $row["lastfecha"];
            $nestedData[] = $row["lasthora"];
            $nestedData[] = $row["lastlogin"];
            $nestedData[] = $row["lastname"];
            $nestedData[] = $row["lastaccion"];
            
            // Badges related to action types would be cool, but plain text for now
            $badgeClass = 'bg-secondary';
            if($row["lasttipo"] == 'Registro') $badgeClass = 'bg-success';
            if($row["lasttipo"] == 'Modifico') $badgeClass = 'bg-info text-dark';
            if($row["lasttipo"] == 'Elimino') $badgeClass = 'bg-danger';
            
            $nestedData[] = '<span class="badge '.$badgeClass.'">'.$row["lasttipo"].'</span>';
            $nestedData[] = $row["tipousu"];
            $nestedData[] = $row["lastip"];
            
            $nestedData[] = '<div class="text-center"><input type="checkbox" name="student_id[]" class="delete_student form-check-input" value="'.$row['lastnro'].'"></div>';
            
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

    public function delete() {
        header('Content-Type: application/json');
        if(isset($_POST['id'])) {
            if ($this->model->bajaAuditoria($_POST['id'])) {
                echo json_encode(array('status' => 'success', 'message' => 'Registros eliminados correctamente.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Error al eliminar registros.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'No se seleccionaron registros.'));
        }
    }
}
?>
