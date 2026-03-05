<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php";

class LiderModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function getLideresAjax($requestData) {
        $columns = array( 
            0 => 'id_lider', 1 => 'ced_lider', 2 => 'nom_lider', 
            3 => 'dir_lider', 4 => 'barrio_lider', 5 => 'comuna_lider', 
            6 => 'email_lider', 7 => 'cel_lider'
        );

        $sqlTotal = "SELECT count(id_lider) as total FROM lideres";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute();
        $rowTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $totalData = $rowTotal['total'];
        $totalFiltered = $totalData;

        $sqlBase = "FROM lideres WHERE 1=1 ";
        $params = array();

        if(!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $sqlBase .= "AND (id_lider LIKE :search OR ced_lider LIKE :search OR nom_lider LIKE :search OR barrio_lider LIKE :search OR comuna_lider LIKE :search) ";
            $params[':search'] = $searchValue . '%';
            
            $sqlCountFilter = "SELECT count(id_lider) as total " . $sqlBase;
            $stmtCount = $this->db->prepare($sqlCountFilter);
            foreach($params as $key => $val) { $stmtCount->bindValue($key, $val); }
            $stmtCount->execute();
            $rowCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $totalFiltered = $rowCount['total'];
        }

        $sqlData = "SELECT id_lider, ced_lider, nom_lider, dir_lider, barrio_lider, comuna_lider, email_lider, cel_lider " . $sqlBase;
        
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

    // --- CRUD Methods ---
    public function getLiderById($id) {
        $sql = "SELECT * FROM lideres WHERE id_lider = :id";
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

    public function getPuestoInfo($num_zona, $pues_zona) {
         $sql = "SELECT nom_puesto, dir_zona FROM zonas WHERE num_zona = :num AND pues_zona = :pues LIMIT 1";
         $stmt = $this->db->prepare($sql);
         $stmt->bindValue(':num', $num_zona);
         $stmt->bindValue(':pues', $pues_zona);
         $stmt->execute();
         return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarLider($data) {
        // Updated to include Voting Information
        $sql = "INSERT INTO lideres (ced_lider, nom_lider, dir_lider, barrio_lider, comuna_lider, email_lider, cel_lider, zona_lider, puesto_lider, mesa_lider) 
                VALUES (:ced, :nom, :dir, :bar, :com, :email, :cel, :zon, :pue, :mes)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ced', $data['txtCed_lider']);
            $stmt->bindValue(':nom', $data['txtNom_lider']);
            $stmt->bindValue(':dir', $data['txtDir_lider']);
            $stmt->bindValue(':bar', $data['txtBarrio_lider']);
            $stmt->bindValue(':com', $data['txtComuna_lider']);
            $stmt->bindValue(':email', $data['txtEmail_lider']);
            $stmt->bindValue(':cel', $data['txtCel_lider']);
            $stmt->bindValue(':zon', $data['CboNum_zona']);
            $stmt->bindValue(':pue', $data['CboNum_puesto']);
            $stmt->bindValue(':mes', $data['txtNum_mesa']);
            
            if ($stmt->execute()) {
                // Auto-crear el usuario con perfil líder
                try {
                    $pass_hash = password_hash($data['txtCed_lider'], PASSWORD_DEFAULT);
                    $sqlUser = "INSERT INTO usuarios (ced_usuario, usuario, password_usu, nombre, correo, id_tipo, dir_usuario, cel_usuario, activacion) 
                                VALUES (:ced, :usu, :pass, :nom, :email, 3, :dir, :cel, 1)";
                    $stmtUser = $this->db->prepare($sqlUser);
                    $stmtUser->bindValue(':ced', $data['txtCed_lider']);
                    $stmtUser->bindValue(':usu', $data['txtCed_lider']);
                    $stmtUser->bindValue(':pass', $pass_hash);
                    $stmtUser->bindValue(':nom', $data['txtNom_lider']);
                    $stmtUser->bindValue(':email', $data['txtEmail_lider']);
                    $stmtUser->bindValue(':dir', $data['txtDir_lider']);
                    $stmtUser->bindValue(':cel', $data['txtCel_lider']);
                    $stmtUser->execute();
                } catch(Exception $e) {
                    // Ignorar si el usuario ya existe o hay un error de duplicado
                }

                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=$_SESSION['lastlogin'];
                $nombre=$_SESSION['nombres'];
                $lastaccion="Ingreso datos de un lider";
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

    public function actualizarLider($data) {
        $id = intval($data['txtId']);
        
        $sql = "UPDATE lideres SET ced_lider=:ced, nom_lider=:nom, dir_lider=:dir, barrio_lider=:bar, comuna_lider=:com, email_lider=:email, cel_lider=:cel, zona_lider=:zon, puesto_lider=:pue, mesa_lider=:mes WHERE id_lider=:id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':ced', $data['txtCed_lider']);
            $stmt->bindValue(':nom', $data['txtNom_lider']);
            $stmt->bindValue(':dir', $data['txtDir_lider']);
            $stmt->bindValue(':bar', $data['txtBarrio_lider']);
            $stmt->bindValue(':com', $data['txtComuna_lider']);
            $stmt->bindValue(':email', $data['txtEmail_lider']);
            $stmt->bindValue(':cel', $data['txtCel_lider']);
            $stmt->bindValue(':zon', $data['CboNum_zona']);
            $stmt->bindValue(':pue', $data['CboNum_puesto']);
            $stmt->bindValue(':mes', $data['txtNum_mesa']);
            
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=$_SESSION['lastlogin'];
                $nombre=$_SESSION['nombres'];
                $lastaccion="Actualizo datos de un lider";
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

    public function bajaLider($ids) {
        if(!is_array($ids)) { $ids = array($ids); }
        $ids = array_map('intval', $ids);
        $idsStr = implode(',', $ids);
        
        $sql = "DELETE FROM lideres WHERE id_lider IN ($idsStr)";
        try {
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=isset($_SESSION['lastlogin']) ? $_SESSION['lastlogin'] : '';
                $nombre=isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '';
                $lastaccion="Elimino lideres con IDs: $idsStr";
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
