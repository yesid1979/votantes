<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php";

class AuditoriaModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function getAuditoriaAjax($requestData) {
        $columns = array( 
            0 => 'lastnro', 1 => 'lastfecha', 2 => 'lasthora', 
            3 => 'lastlogin', 4 => 'lastname', 
            5 => 'lastaccion', 6 => 'lasttipo', 
            7 => 'tipousu', 8 => 'lastip'
        );

        $sqlTotal = "SELECT count(lastnro) as total FROM auditoria";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute();
        $rowTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $totalData = $rowTotal['total'];
        $totalFiltered = $totalData;

        $sqlBase = "FROM auditoria WHERE 1=1 ";
        $params = array();

        if(!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $sqlBase .= "AND (lastnro LIKE :search OR lastfecha LIKE :search OR lastlogin LIKE :search OR lastname LIKE :search OR lasttipo LIKE :search OR tipousu LIKE :search) ";
            $params[':search'] = $searchValue . '%';
            
            $sqlCountFilter = "SELECT count(lastnro) as total " . $sqlBase;
            $stmtCount = $this->db->prepare($sqlCountFilter);
            foreach($params as $key => $val) { $stmtCount->bindValue($key, $val); }
            $stmtCount->execute();
            $rowCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $totalFiltered = $rowCount['total'];
        }

        $sqlData = "SELECT lastnro, lastfecha, lasthora, lastlogin, lastname, lastaccion, lasttipo, tipousu, lastip " . $sqlBase;
        
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

    public function bajaAuditoria($ids) {
        if(!is_array($ids)) {
            $ids = array($ids);
        }
        $ids = array_map('intval', $ids);
        $idsStr = implode(',', $ids);
        
        $sql = "DELETE FROM auditoria WHERE lastnro IN ($idsStr)";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
