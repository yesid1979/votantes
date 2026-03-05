<?php
require_once "funcs/class.conexion.php";

class AjaxGeoModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function getDepartamentos() {
        $sql = "SELECT DISTINCT dpto_zona FROM zonas WHERE estado_zona = 'Activo' AND dpto_zona IS NOT NULL AND dpto_zona != '' ORDER BY dpto_zona ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMunicipios($dpto) {
        $sql = "SELECT DISTINCT mun_zona FROM zonas WHERE dpto_zona = :dpto AND estado_zona = 'Activo' AND mun_zona IS NOT NULL AND mun_zona != '' ORDER BY mun_zona ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':dpto', $dpto);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getZonas($dpto, $muni) {
        $sql = "SELECT DISTINCT num_zona FROM zonas WHERE dpto_zona = :dpto AND mun_zona = :muni AND estado_zona = 'Activo' AND num_zona IS NOT NULL AND num_zona != '' ORDER BY id_zona ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':dpto', $dpto);
        $stmt->bindValue(':muni', $muni);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPuestos($dpto, $muni, $zona) {
        $sql = "SELECT pues_zona, nom_puesto, dir_zona FROM zonas WHERE dpto_zona = :dpto AND mun_zona = :muni AND num_zona = :zona AND estado_zona = 'Activo'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':dpto', $dpto);
        $stmt->bindValue(':muni', $muni);
        $stmt->bindValue(':zona', $zona);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPuestoInfo($dpto, $muni, $zona, $puesto) {
        $sql = "SELECT nom_puesto, dir_zona FROM zonas WHERE dpto_zona = :dpto AND mun_zona = :muni AND num_zona = :zona AND pues_zona = :puesto LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':dpto', $dpto);
        $stmt->bindValue(':muni', $muni);
        $stmt->bindValue(':zona', $zona);
        $stmt->bindValue(':puesto', $puesto);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReverseGeo($zona, $puesto) {
        $sql = "SELECT dpto_zona, mun_zona FROM zonas WHERE num_zona = :zona AND pues_zona = :puesto LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':zona', $zona);
        $stmt->bindValue(':puesto', $puesto);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
