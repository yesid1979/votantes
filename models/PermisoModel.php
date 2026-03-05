<?php
require_once __DIR__ . "/../funcs/class.conexion.php";

class PermisoModel {
    private $db;

    public function __construct() {
        try {
            $conexion = new Conexion();
            $this->db = $conexion->get_conexion();
        } catch (Exception $e) {
            error_log("Error en PermisoModel: " . $e->getMessage());
        }
    }

    public function getModulos() {
        try {
            // Usamos MIN(id_modulo) para asegurar que el ID coincida con los primeros insertados (los del script original)
            $sql = "SELECT MIN(id_modulo) as id_modulo, nombre, icono, url, orden FROM modulos WHERE activo = 1 GROUP BY nombre ORDER BY orden";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getModulos: " . $e->getMessage());
            return array();
        }
    }

    public function getPermisosByUsuario($id_usuario) {
        try {
            $sql = "SELECT id_modulo, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_ver_todo 
                    FROM permisos_usuario 
                    WHERE id_usuario = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_usuario', $id_usuario);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getPermisosByUsuario: " . $e->getMessage());
            return array();
        }
    }

    public function getPermisosByTipo($id_tipo) {
        try {
            // El nombre correcto de la columna es id_tipo
            $sql = "SELECT id_modulo, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_ver_todo 
                    FROM permisos 
                    WHERE id_tipo = :id_tipo";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_tipo', $id_tipo);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getPermisosByTipo: " . $e->getMessage());
            return array();
        }
    }
}
?>
