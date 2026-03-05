<?php
require_once 'models/PerfilModel.php';

class PerfilController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }
        $this->model = new PerfilModel();
    }

    public function index() {
        $id = $_SESSION['id_usuario'];
        $usuario = $this->model->getUsuarioById($id);
        
        if(!$usuario) {
            header("Location: index.php");
            exit;
        }

        require 'views/usuarios/perfil.php';
    }

    public function updatePersonal() {
        if ($this->model->actualizarPersonal($_POST)) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Datos personales actualizados correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
             echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> Error al actualizar datos personales.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }

    public function updatePassword() {
        if ($this->model->actualizarPassword($_POST)) {
             echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Contraseña actualizada correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
             echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> Error al actualizar contraseña.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }
}
?>
