<?php
require_once 'funcs/class.conexion.php';

class ComisionModel extends Conexion {
    private $db;

    public function __construct() {
        $this->db = $this->get_conexion();
    }

    public function getComisiones() {
        $sql = "SELECT * FROM jac_comisiones ORDER BY nombre_comision ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComisionById($id) {
        $sql = "SELECT * FROM jac_comisiones WHERE id_comision = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':id' => $id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarComision($nombre) {
        $sql = "INSERT INTO jac_comisiones (nombre_comision) VALUES (:nombre)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(':nombre' => $nombre));
    }

    public function actualizarComision($id, $nombre) {
        $sql = "UPDATE jac_comisiones SET nombre_comision = :nombre WHERE id_comision = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(':nombre' => $nombre, ':id' => $id));
    }

    public function eliminarComision($id) {
        // Verificar si hay afiliados usando esta comisión
        $check = $this->db->prepare("SELECT COUNT(*) FROM jac_afiliados WHERE id_comision = :id");
        $check->execute(array(':id' => $id));
        if ($check->fetchColumn() > 0) {
            return "exists"; // No se puede eliminar si hay afiliados
        }

        $sql = "DELETE FROM jac_comisiones WHERE id_comision = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(':id' => $id));
    }
}
