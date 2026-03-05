<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php";

class SimpatizanteModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function getSimpatizantesAjax($requestData, $tipo_usuario, $cedula) {
        $columns = array( 
            0 => 'id_simpatizante', 1 => 'ced_simpatizante', 2 => 'nom_simpatizante', 
            3 => 'dir_simpatizante', 4 => 'barrio_simpatizante', 5 => 'comuna_simpatizante', 
            6 => 'cel_simpatizante'
        );

        $sqlTotal = "SELECT count(id_simpatizante) as total FROM simpatizante";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute();
        $rowTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $totalData = $rowTotal['total'];
        $totalFiltered = $totalData;

        $sqlBase = "FROM simpatizante WHERE 1=1 ";
        $params = array();

        if(!empty($requestData['search']['value'])) {
             $searchValue = $requestData['search']['value'];
             $sqlBase .= "AND (id_simpatizante LIKE :search OR ced_simpatizante LIKE :search OR nom_simpatizante LIKE :search OR barrio_simpatizante LIKE :search OR comuna_simpatizante LIKE :search) ";
             $params[':search'] = $searchValue . '%';
             
             $sqlCountFilter = "SELECT count(id_simpatizante) as total " . $sqlBase;
             $stmtCount = $this->db->prepare($sqlCountFilter);
             foreach($params as $key => $val) { $stmtCount->bindValue($key, $val); }
             $stmtCount->execute();
             $rowCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
             $totalFiltered = $rowCount['total'];
        }

        $sqlData = "SELECT * " . $sqlBase;
        
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
    public function getSimpatizanteById($id) {
        $sql = "SELECT * FROM simpatizante WHERE id_simpatizante = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', intval($id));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getZonasForSelect() {
        $sql = "SELECT num_zona FROM zonas GROUP BY num_zona HAVING count(*) > 1 ORDER BY id_zona ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPuestosByZona($num_zona) {
        $sql = "SELECT pues_zona, nom_puesto, dir_zona FROM zonas WHERE num_zona = :num_zona";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':num_zona', $num_zona);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Helper to get specific puesto info
    public function getPuestoInfo($num_zona, $pues_zona) {
         $sql = "SELECT nom_puesto, dir_zona FROM zonas WHERE num_zona = :num AND pues_zona = :pues LIMIT 1";
         $stmt = $this->db->prepare($sql);
         $stmt->bindValue(':num', $num_zona);
         $stmt->bindValue(':pues', $pues_zona);
         $stmt->execute();
         return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarSimpatizante($data) {
        $sql = "INSERT INTO simpatizante (ced_simpatizante, nom_simpatizante, dir_simpatizante, barrio_simpatizante, comuna_simpatizante, email_simpatizante, tel_simpatizante, cel_simpatizante, fechnac_simpatizante, edad_simpatizante, sexo_simpatizante, zona_simpatizante, puesto_simpatizante, nom_puestov, dir_puestov, mesa_simpatizante) 
                VALUES (:ced, :nom, :dir, :bar, :com, :email, :tel, :cel, :fec, :ed, :sex, :zon, :pue, :nomp, :dirp, :mes)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ced', $data['txtCed_votante']);
            $stmt->bindValue(':nom', $data['txtNom_votante']);
            $stmt->bindValue(':dir', $data['txtDir_votante']);
            $stmt->bindValue(':bar', $data['txtBarrio']);
            $stmt->bindValue(':com', $data['txtComuna']);
            $stmt->bindValue(':email', $data['txtEmail']);
            $stmt->bindValue(':tel', $data['txtTel_votante']);
            $stmt->bindValue(':cel', $data['txtCel_votante']);
            $stmt->bindValue(':fec', $data['txtFechNac']);
            $stmt->bindValue(':ed', $data['txtEdad']);
            $stmt->bindValue(':sex', $data['CboSexo']);
            $stmt->bindValue(':zon', $data['CboNum_zona']);
            $stmt->bindValue(':pue', $data['CboNum_puesto']);
            $stmt->bindValue(':nomp', $data['txtNom_puesto']);
            $stmt->bindValue(':dirp', $data['txtDir_puesto']);
            $stmt->bindValue(':mes', $data['txtNum_mesa']);
            
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=$_SESSION['lastlogin'];
                $nombre=$_SESSION['nombres'];
                $lastaccion="Ingreso datos de un simpatizante";
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

    public function actualizarSimpatizante($data) {
        $id = intval($data['id']);
        
        $sql = "UPDATE simpatizante SET ced_simpatizante=:ced, nom_simpatizante=:nom, dir_simpatizante=:dir, barrio_simpatizante=:bar, comuna_simpatizante=:com, email_simpatizante=:email, tel_simpatizante=:tel, cel_simpatizante=:cel, fechnac_simpatizante=:fec, edad_simpatizante=:ed, sexo_simpatizante=:sex, zona_simpatizante=:zon, puesto_simpatizante=:pue, nom_puestov=:nomp, dir_puestov=:dirp, mesa_simpatizante=:mes WHERE id_simpatizante=:id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
             $stmt->bindValue(':ced', $data['txtCed_votante']);
            $stmt->bindValue(':nom', $data['txtNom_votante']);
            $stmt->bindValue(':dir', $data['txtDir_votante']);
            $stmt->bindValue(':bar', $data['txtBarrio']);
            $stmt->bindValue(':com', $data['txtComuna']);
            $stmt->bindValue(':email', $data['txtEmail']);
            $stmt->bindValue(':tel', $data['txtTel_votante']);
            $stmt->bindValue(':cel', $data['txtCel_votante']);
            $stmt->bindValue(':fec', $data['txtFechNac']);
            $stmt->bindValue(':ed', $data['txtEdad']);
            $stmt->bindValue(':sex', $data['CboSexo']);
            $stmt->bindValue(':zon', $data['CboNum_zona']);
            $stmt->bindValue(':pue', $data['CboNum_puesto']);
            $stmt->bindValue(':nomp', $data['txtNom_puesto']);
            $stmt->bindValue(':dirp', $data['txtDir_puesto']);
            $stmt->bindValue(':mes', $data['txtNum_mesa']);
            
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=$_SESSION['lastlogin'];
                $nombre=$_SESSION['nombres'];
                $lastaccion="Actualizo datos de un simpatizante";
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

    public function bajaSimpatizante($ids) {
        if(!is_array($ids)) { $ids = array($ids); }
        $ids = array_map('intval', $ids);
        $idsStr = implode(',', $ids);
        
        $sql = "DELETE FROM simpatizante WHERE id_simpatizante IN ($idsStr)";
        try {
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=isset($_SESSION['lastlogin']) ? $_SESSION['lastlogin'] : '';
                $nombre=isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '';
                $lastaccion="Elimino simpatizantes con IDs: $idsStr";
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
