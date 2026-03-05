<?php
require_once 'models/UsuarioModel.php';
require_once 'models/PermisoModel.php';

class UsuarioController {
    private $model;
    private $permisoModel;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new UsuarioModel();
        $this->permisoModel = new PermisoModel();
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        require 'views/usuarios/listar.php';
    }

    public function perfil() {
        $id = $_SESSION['id_usuario'];
        $usuario = $this->model->getUsuarioById($id);
        require 'views/usuarios/perfil.php';
    }
    
    // --- Create ---
    public function create() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        $tipos = $this->model->getTiposUsuario();
        $listaModulosPermisos = $this->permisoModel->getModulos();
        require 'views/usuarios/crear.php';
    }

    public function store() {
        header('Content-Type: application/json');
        if ($this->model->registrarUsuario($_POST)) {
            echo json_encode(['status' => 'success', 'message' => 'Bien hecho, los datos han sido agregados correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error, no se pudo registrar los datos.']);
        }
    }

    // --- Edit ---
    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'datos'; // Capturar la pestaña
        
        $usuario = $this->model->getUsuarioById($id);
        
        if(!$usuario) {
            header("Location: index.php?url=usuario/index");
            exit;
        }

        $id_tipouser = $_SESSION['tipo_usuario'];
        $tipos = $this->model->getTiposUsuario();
        
        // Cargar módulos y permisos
        $listaModulosPermisos = $this->permisoModel->getModulos();
        $permisosUsuario = $this->permisoModel->getPermisosByUsuario($id);
        
        require 'views/usuarios/editar.php';
    }

    public function update() {
        header('Content-Type: application/json');
        try {
            if ($this->model->actualizarUsuario($_POST)) {
                 echo json_encode(['status' => 'success', 'message' => 'Bien hecho, el registro ha sido actualizado correctamente.']);
            } else {
                 echo json_encode(['status' => 'error', 'message' => 'Error, no se pudo actualizar los datos.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete() {
        header('Content-Type: application/json');
        if(isset($_POST['id'])) {
             if ($this->model->bajaUsuario($_POST['id'])) {
                 echo json_encode(['status' => 'success', 'message' => 'Registros eliminados correctamente.']);
             } else {
                 echo json_encode(['status' => 'error', 'message' => 'Error al eliminar registros.']);
             }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se seleccionaron registros.']);
        }
    }

    public function updatePerfil() {
        header('Content-Type: application/json');
        $id = $_SESSION['id_usuario'];
        $nombre = $_POST['nombre_up'];
        $email = $_POST['email_up'];
        $tel = $_POST['telefono_up'];
        $cel = $_POST['cel_up'];
        $dir = $_POST['direccion_up'];

        if ($this->model->updatePerfil($id, $nombre, $email, $tel, $cel, $dir)) {
            echo json_encode(['status' => 'success', 'message' => 'Perfil actualizado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el perfil.']);
        }
    }

    public function changePassword() {
        header('Content-Type: application/json');
        $id = $_SESSION['id_usuario'];
        $pass1 = $_POST['newPassword1_up'];
        $pass2 = $_POST['newPassword2_up'];

        if ($pass1 !== $pass2) {
            echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
            return;
        }

        if ($this->model->changePassword($id, $pass1)) {
            echo json_encode(['status' => 'success', 'message' => 'Contraseña cambiada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al cambiar la contraseña.']);
        }
    }

    public function ajaxListar() {
        $requestData = $_REQUEST;
        
        $result = $this->model->getUsuariosAjax($requestData);
        
        $dataFormatted = array();
        foreach ($result['data'] as $row) {
            $nestedData = array();
            $nestedData[] = $row["id"];
            $nestedData[] = $row["ced_usuario"];
            $nestedData[] = $row["usuario"];
            $nestedData[] = $row["nombre"];
            $nestedData[] = $row["correo"];
            $nestedData[] = $row["tipo"];
            
            // Estado con Badge
            $estado = ($row['activacion'] == 1) 
                ? '<span class="badge bg-success">Activo</span>' 
                : '<span class="badge bg-danger">Inactivo</span>';
            $nestedData[] = $estado;

            $nestedData[] = '<div class="text-center">
                             <a href="index.php?url=usuario/edit&id='.$row['id'].'&tab=datos" class="btn btn-sm btn-info text-white" title="Editar Información">
                                <i class="bi bi-pencil-square"></i>
                             </a>
                             <a href="index.php?url=usuario/edit&id='.$row['id'].'&tab=permisos" class="btn btn-sm btn-warning text-white" title="Gestionar Permisos">
                                <i class="bi bi-shield-lock"></i>
                             </a>
                             </div>';
            
            $nestedData[] = '<div class="text-center"><input type="checkbox" name="student_id[]" class="delete_student form-check-input" value="'.$row['id'].'"></div>';
            
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
