<?php
require_once 'funcs/class.conexion.php';

class RolModel extends Conexion {
    private $db;

    public function __construct() {
        $this->db = $this->get_conexion();
    }

    public function getRoles() {
        $sql = "SELECT * FROM tipo_usuario ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRolById($id) {
        $sql = "SELECT * FROM tipo_usuario WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':id' => $id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarRol($tipo) {
        $sql = "INSERT INTO tipo_usuario (tipo) VALUES (:tipo)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':tipo' => $tipo));
        return $this->db->lastInsertId();
    }

    public function actualizarRol($id, $tipo) {
        $sql = "UPDATE tipo_usuario SET tipo = :tipo WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(':tipo' => $tipo, ':id' => $id));
    }

    public function eliminarRol($id) {
        // Verificar si hay usuarios con este rol
        $check = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE id_tipo = :id");
        $check->execute(array(':id' => $id));
        if ($check->fetchColumn() > 0) {
            return "exists";
        }
        
        $sql = "DELETE FROM tipo_usuario WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(':id' => $id));
    }
}
