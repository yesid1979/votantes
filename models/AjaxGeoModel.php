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

    public function getPuestosByMuni($dpto, $muni) {
        $sql = "SELECT DISTINCT num_zona, pues_zona, nom_puesto FROM zonas WHERE dpto_zona = :dpto AND mun_zona = :muni AND estado_zona = 'Activo' ORDER BY nom_puesto ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':dpto', $dpto);
        $stmt->bindValue(':muni', $muni);
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

    public function getZonasCompleto($dpto, $muni) {
        // Asumiendo que la columna total_mesa existe en la base de datos como indica el usuario
        // Si total_mesa no existe y da error, se debería corregir aquí.
        // Se ha agregado a la consulta asumiendo que el esquema está actualizado.
        $sql = "SELECT id_zona, num_zona, pues_zona, nom_puesto, comuna_zona, barr_zona, total_mesa 
                FROM zonas 
                WHERE dpto_zona = :dpto AND mun_zona = :muni AND estado_zona = 'Activo' 
                ORDER BY num_zona ASC, pues_zona ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':dpto', $dpto);
        $stmt->bindValue(':muni', $muni);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCandidatosByAspirante($aspirante) {
        $sql = "SELECT id_candidato, nom_candidato, nom_partido, logo_partido, foto_candidato, num_candidato 
                FROM candidatos 
                WHERE aspirante_a = :aspirante AND estado_candidato = 'Activo'
                ORDER BY CAST(num_candidato AS UNSIGNED) ASC, nom_candidato ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':aspirante', $aspirante);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
