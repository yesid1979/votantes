<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php";

class ZonaModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function getZonasAjax($requestData) {
        $columns = array( 
            0 => 'id_zona', 1 => 'num_zona', 2 => 'pues_zona', 3 => 'mun_zona', 
            4 => 'nom_puesto', 5 => 'comuna_zona', 6 => 'dir_zona', 
            7 => 'barr_zona', 8 => 'estado_zona'
        );

        $sqlTotal = "SELECT count(id_zona) as total FROM zonas";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute();
        $rowTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $totalData = $rowTotal['total'];
        $totalFiltered = $totalData;

        $sqlBase = "FROM zonas WHERE 1=1 ";
        $params = array();

        if(!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $sqlBase .= "AND (id_zona LIKE :search OR num_zona LIKE :search OR pues_zona LIKE :search OR nom_puesto LIKE :search OR comuna_zona LIKE :search OR barr_zona LIKE :search) ";
            $params[':search'] = $searchValue . '%';
            
            $sqlCountFilter = "SELECT count(id_zona) as total " . $sqlBase;
            $stmtCount = $this->db->prepare($sqlCountFilter);
            foreach($params as $key => $val) { $stmtCount->bindValue($key, $val); }
            $stmtCount->execute();
            $rowCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $totalFiltered = $rowCount['total'];
        }

        $sqlData = "SELECT id_zona, num_zona, pues_zona, mun_zona, nom_puesto, comuna_zona, dir_zona, barr_zona, estado_zona " . $sqlBase;
        
        if (isset($requestData['order'])) {
             $colIndex = $requestData['order'][0]['column'];
             $dir = $requestData['order'][0]['dir'];
             if(isset($columns[$colIndex])) {
                 $sqlData .= " ORDER BY " . $columns[$colIndex] . " " . $dir;
             }
        }
        
        if (isset($requestData['start']) && $requestData['length'] != -1) {
             $sqlData .= " LIMIT " . intval($requestData['start']) . ", " . intval($requestData['length']);
        }

        $stmtData = $this->db->prepare($sqlData);
        foreach($params as $key => $val) { $stmtData->bindValue($key, $val); }
        $stmtData->execute();
        $data = $stmtData->fetchAll(PDO::FETCH_ASSOC);

        return array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
    }

    // --- CRUD ---
    public function getZonaById($id) {
        $sql = "SELECT * FROM zonas WHERE id_zona = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', intval($id));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarZona($data) {
        $sql = "INSERT INTO zonas (num_zona, pues_zona, mun_zona, nom_puesto, comuna_zona, dir_zona, barr_zona, estado_zona) 
                VALUES (:num, :pue, :mun, :nom, :com, :dir, :bar, :est)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':num', $data['txtNum_zona']);
            $stmt->bindValue(':pue', $data['txtPues_zona']);
            $stmt->bindValue(':mun', $data['txtMun_zona']);
            $stmt->bindValue(':nom', $data['txtNom_puesto']);
            $stmt->bindValue(':com', $data['txtComuna_zona']);
            $stmt->bindValue(':dir', $data['txtDir_zona']);
            $stmt->bindValue(':bar', $data['txtBarr_zona']);
            $stmt->bindValue(':est', $data['CboEstado']);
            
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=$_SESSION['lastlogin'];
                $nombre=$_SESSION['nombres'];
                $lastaccion="Ingreso una zona";
                $lasttipo="Registro";
                $tipoUser=$_SESSION['tipoUser'];
                $tomarip=ipCheck();
                auditoria1($fecha, $hora, $lastlogin, $nombre, $lastaccion, $lasttipo, $tipoUser, $tomarip);
                return true; 
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function actualizarZona($data) {
        $id = intval($data['txtId']);
        
        $sql = "UPDATE zonas SET num_zona=:num, pues_zona=:pue, mun_zona=:mun, nom_puesto=:nom, comuna_zona=:com, dir_zona=:dir, barr_zona=:bar, estado_zona=:est WHERE id_zona=:id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':num', $data['txtNum_zona']);
            $stmt->bindValue(':pue', $data['txtPues_zona']);
            $stmt->bindValue(':mun', $data['txtMun_zona']);
            $stmt->bindValue(':nom', $data['txtNom_puesto']);
            $stmt->bindValue(':com', $data['txtComuna_zona']);
            $stmt->bindValue(':dir', $data['txtDir_zona']);
            $stmt->bindValue(':bar', $data['txtBarr_zona']);
            $stmt->bindValue(':est', $data['CboEstado']);
            
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=$_SESSION['lastlogin'];
                $nombre=$_SESSION['nombres'];
                $lastaccion="Actualizo datos de una zona";
                $lasttipo="Modifico";
                $tipoUser=$_SESSION['tipoUser'];
                $tomarip=ipCheck();
                auditoria1($fecha, $hora, $lastlogin, $nombre, $lastaccion, $lasttipo, $tipoUser, $tomarip);
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function bajaZona($ids) {
        if(!is_array($ids)) { $ids = array($ids); }
        $ids = array_map('intval', $ids);
        $idsStr = implode(',', $ids);
        
        $sql = "DELETE FROM zonas WHERE id_zona IN ($idsStr)";
        try {
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=isset($_SESSION['lastlogin']) ? $_SESSION['lastlogin'] : '';
                $nombre=isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '';
                $lastaccion="Elimino zonas con IDs: $idsStr";
                $lasttipo="Eliminacion";
                $tipoUser=isset($_SESSION['tipoUser']) ? $_SESSION['tipoUser'] : '';
                $tomarip=ipCheck();
                auditoria1($fecha, $hora, $lastlogin, $nombre, $lastaccion, $lasttipo, $tipoUser, $tomarip);
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
