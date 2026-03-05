<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php";

class VotanteModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function getVotantesAjax($requestData, $tipo_usuario, $cedula, $puede_ver_todos = false, $ced_lider_filter = '') {
        $columns = array( 
            0 => 'id_votante', 1 => 'ced_votante', 2 => 'nom_votante', 3 => 'dir_votante', 
            4 => 'barrio_votante', 5 => 'comuna_votante', 6 => 'email_votante', 
            7 => 'cel_votante', 8 => 'lider'
        );

        $sqlTotal = "SELECT count(a.id_votante) as total FROM votantes a LEFT JOIN lideres b ON a.ced_lider=b.ced_lider ";
        if (!empty($ced_lider_filter)) {
            $sqlTotal .= "WHERE a.ced_lider = :ced_lider_filter ";
        } else if ($tipo_usuario == 3 && !$puede_ver_todos) {
            $sqlTotal .= "WHERE a.ced_lider = :cedula";
        }

        $stmtTotal = $this->db->prepare($sqlTotal);
        if (!empty($ced_lider_filter)) { $stmtTotal->bindValue(':ced_lider_filter', $ced_lider_filter); }
        else if ($tipo_usuario == 3 && !$puede_ver_todos) { $stmtTotal->bindValue(':cedula', $cedula); }
        $stmtTotal->execute();
        $totalData = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
        $totalFiltered = $totalData;

        $sqlBase = "FROM votantes a LEFT JOIN lideres b ON a.ced_lider=b.ced_lider WHERE 1=1 ";
        $params = array();
        
        if (!empty($ced_lider_filter)) {
            $sqlBase .= "AND a.ced_lider = :ced_lider_filter ";
            $params[':ced_lider_filter'] = $ced_lider_filter;
        } else if ($tipo_usuario == 3 && !$puede_ver_todos) {
            $sqlBase .= "AND a.ced_lider = :cedula ";
            $params[':cedula'] = $cedula;
        }

        if(!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $sqlBase .= "AND (a.id_votante LIKE :search OR a.ced_votante LIKE :search OR a.nom_votante LIKE :search OR a.barrio_votante LIKE :search OR a.comuna_votante LIKE :search) ";
            $params[':search'] = $searchValue . '%';
            
            $sqlCountFilter = "SELECT count(a.id_votante) as total " . $sqlBase;
            $stmtCount = $this->db->prepare($sqlCountFilter);
            foreach($params as $key => $val) { $stmtCount->bindValue($key, $val); }
            $stmtCount->execute();
            $rowCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $totalFiltered = $rowCount['total'];
        }

        $sqlData = "SELECT a.id_votante, a.ced_votante, a.nom_votante, a.dir_votante, a.barrio_votante, a.comuna_votante, a.email_votante, a.cel_votante, a.ced_lider, b.nom_lider as lider " . $sqlBase;
        
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
    public function getVotanteById($id) {
        $sql = "SELECT * FROM votantes WHERE id_votante = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', intval($id));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLideresForSelect() {
        $sql = "SELECT ced_lider, nom_lider FROM lideres ORDER BY nom_lider ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function registrarVotante($data) {
        // Updated to include voting info: zona_votante, puesto_votante, nom_puestov, dir_puestov, mesa_votante
        $sql = "INSERT INTO votantes (ced_votante, nom_votante, dir_votante, barrio_votante, comuna_votante, email_votante, cel_votante, ced_lider, zona_votante, puesto_votante, nom_puestov, dir_puestov, mesa_votante) 
                VALUES (:ced, :nom, :dir, :bar, :com, :email, :cel, :lider, :zon, :pue, :nomp, :dirp, :mes)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ced', $data['txtCed_votante']);
            $stmt->bindValue(':nom', $data['txtNom_votante']);
            $stmt->bindValue(':dir', $data['txtDir_votante']);
            $stmt->bindValue(':bar', $data['txtBarrio_votante']);
            $stmt->bindValue(':com', $data['txtComuna_votante']);
            $stmt->bindValue(':email', $data['txtEmail_votante']);
            $stmt->bindValue(':cel', $data['txtCel_votante']);
            $stmt->bindValue(':lider', $data['CboLider']); 
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
                $lastaccion="Ingreso datos de un votante";
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

    public function actualizarVotante($data) {
        $id = intval($data['txtId']);
        
        $sql = "UPDATE votantes SET ced_votante=:ced, nom_votante=:nom, dir_votante=:dir, barrio_votante=:bar, comuna_votante=:com, email_votante=:email, cel_votante=:cel, ced_lider=:lider, zona_votante=:zon, puesto_votante=:pue, nom_puestov=:nomp, dir_puestov=:dirp, mesa_votante=:mes WHERE id_votante=:id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':ced', $data['txtCed_votante']);
            $stmt->bindValue(':nom', $data['txtNom_votante']);
            $stmt->bindValue(':dir', $data['txtDir_votante']);
            $stmt->bindValue(':bar', $data['txtBarrio_votante']);
            $stmt->bindValue(':com', $data['txtComuna_votante']);
            $stmt->bindValue(':email', $data['txtEmail_votante']);
            $stmt->bindValue(':cel', $data['txtCel_votante']);
            $stmt->bindValue(':lider', $data['CboLider']);
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
                $lastaccion="Actualizo datos de un votante";
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

    public function bajaVotante($ids) {
        if(!is_array($ids)) {
            $ids = array($ids);
        }
        $ids = array_map('intval', $ids);
        $idsStr = implode(',', $ids);
        
        $sql = "DELETE FROM votantes WHERE id_votante IN ($idsStr)";
        
        try {
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=isset($_SESSION['lastlogin']) ? $_SESSION['lastlogin'] : '';
                $nombre=isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '';
                $lastaccion="Elimino votantes con IDs: $idsStr";
                $lasttipo="Eliminacion";
                $tipoUser=isset($_SESSION['tipoUser']) ? $_SESSION['tipoUser'] : '';
                $tomarip=ipCheck();
                auditoria($fecha, $hora, $lastlogin, $nombre, $lastaccion, $lasttipo, $tipoUser, $tomarip);
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
