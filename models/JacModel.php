<?php
require_once 'funcs/class.conexion.php';

class JacModel extends Conexion {
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

    public function getLideresForSelect() {
        $sql = "SELECT ced_lider, nom_lider FROM lideres ORDER BY nom_lider ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarAfiliado($data) {
        try {
            $sql = "INSERT INTO jac_afiliados (fecha_inscripcion, ced_afiliado, nom_afiliado, dir_afiliado, tel_afiliado, ocupacion_afiliado, id_comision, fechnac_afiliado, edad_afiliado, ced_lider) 
                    VALUES (:fecha, :cedula, :nombre, :direccion, :telefono, :ocupacion, :id_comision, :fechnac, :edad, :ced_lider)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(array(
                ':fecha' => $data['txtFecha'],
                ':cedula' => $data['txtCedula'],
                ':nombre' => $data['txtNombre'],
                ':direccion' => $data['txtDireccion'],
                ':telefono' => $data['txtTelefono'],
                ':ocupacion' => $data['txtOcupacion'],
                ':id_comision' => $data['CboComision'],
                ':fechnac' => $data['txtFechnac'],
                ':edad' => $data['txtEdad'],
                ':ced_lider' => $data['CboLider']
            ));
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAfiliadosAjax($requestData, $tipo_usuario, $cedula_session, $puede_ver_todos) {
        $columns = array(
            0 => 'a.id_afiliado',
            1 => 'a.ced_afiliado',
            2 => 'a.nom_afiliado',
            3 => 'c.nombre_comision',
            4 => 'a.tel_afiliado',
            5 => 'l.nom_lider'
        );

        $sql = "SELECT a.*, c.nombre_comision, l.nom_lider as lider 
                FROM jac_afiliados a 
                LEFT JOIN jac_comisiones c ON a.id_comision = c.id_comision 
                LEFT JOIN lideres l ON a.ced_lider = l.ced_lider WHERE 1=1";

        // Filter by leader if not admin/digitador
        if ($tipo_usuario == 3 && !$puede_ver_todos) {
            $sql .= " AND a.ced_lider = '$cedula_session'";
        }

        if (!empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $sql .= " AND (a.ced_afiliado LIKE '%$search%' ";
            $sql .= " OR a.nom_afiliado LIKE '%$search%' ";
            $sql .= " OR c.nombre_comision LIKE '%$search%' ";
            $sql .= " OR l.nom_lider LIKE '%$search%') ";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $totalData = $stmt->rowCount();
        $totalFiltered = $totalData;

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
    }

    public function getAfiliadoById($id) {
        $sql = "SELECT * FROM jac_afiliados WHERE id_afiliado = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':id' => $id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarAfiliado($data) {
        $sql = "UPDATE jac_afiliados SET 
                fecha_inscripcion = :fecha,
                ced_afiliado = :cedula,
                nom_afiliado = :nombre,
                dir_afiliado = :direccion,
                tel_afiliado = :telefono,
                ocupacion_afiliado = :ocupacion,
                id_comision = :id_comision,
                fechnac_afiliado = :fechnac,
                edad_afiliado = :edad,
                ced_lider = :ced_lider 
                WHERE id_afiliado = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(
            ':fecha' => $data['txtFecha'],
            ':cedula' => $data['txtCedula'],
            ':nombre' => $data['txtNombre'],
            ':direccion' => $data['txtDireccion'],
            ':telefono' => $data['txtTelefono'],
            ':ocupacion' => $data['txtOcupacion'],
            ':id_comision' => $data['CboComision'],
            ':fechnac' => $data['txtFechnac'],
            ':edad' => $data['txtEdad'],
            ':ced_lider' => $data['CboLider'],
            ':id' => $data['id_afiliado']
        ));
    }

    public function bajaAfiliados($ids) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM jac_afiliados WHERE id_afiliado IN ($in)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($ids);
    }
}
