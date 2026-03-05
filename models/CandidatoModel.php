<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php";

class CandidatoModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function getCandidatosAjax($requestData) {
        $columns = array( 
            0 => 'id_candidato', 1 => 'logo_partido', 2 => 'nom_partido', 
            3 => 'num_candidato', 4 => 'nom_candidato', 
            5 => 'aspirante_a', 6 => 'estado_candidato'
        );

        $sqlTotal = "SELECT count(id_candidato) as total FROM candidatos";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute();
        $rowTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $totalData = $rowTotal['total'];
        $totalFiltered = $totalData;

        $sqlBase = "FROM candidatos WHERE 1=1 ";
        $params = array();

        if(!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $sqlBase .= "AND (id_candidato LIKE :search OR nom_partido LIKE :search OR num_candidato LIKE :search OR nom_candidato LIKE :search OR aspirante_a LIKE :search OR estado_candidato LIKE :search) ";
            $params[':search'] = $searchValue . '%';
            
            $sqlCountFilter = "SELECT count(id_candidato) as total " . $sqlBase;
            $stmtCount = $this->db->prepare($sqlCountFilter);
            foreach($params as $key => $val) { $stmtCount->bindValue($key, $val); }
            $stmtCount->execute();
            $rowCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $totalFiltered = $rowCount['total'];
        }

        $sqlData = "SELECT id_candidato, logo_partido, foto_candidato, nom_partido, num_candidato, nom_candidato, aspirante_a, estado_candidato " . $sqlBase;
        
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

    public function getCandidatoById($id) {
        $sql = "SELECT * FROM candidatos WHERE id_candidato = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', intval($id));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     public function registrarCandidato($data, $files) {
        $sql = "INSERT INTO candidatos (nom_partido, num_candidato, nom_candidato, aspirante_a, estado_candidato, logo_partido, foto_candidato) 
                VALUES (:nom_part, :num, :nom_cand, :aspirante, :estado, :logo, :foto)";
        
        try {
            // Handle File Uploads (Basic Implementation)
            $logoName = "default_logo.png";
            $fotoName = "default_photo.png";

            if (isset($files['logo_partido']) && $files['logo_partido']['error'] == 0) {
                $logoName = time() . '_' . $files['logo_partido']['name'];
                move_uploaded_file($files['logo_partido']['tmp_name'], 'images/partidos/' . $logoName);
            }

             if (isset($files['foto_candidato']) && $files['foto_candidato']['error'] == 0) {
                $fotoName = time() . '_' . $files['foto_candidato']['name'];
                move_uploaded_file($files['foto_candidato']['tmp_name'], 'images/partidos/' . $fotoName);
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nom_part', $data['txtNomPartido']);
            $stmt->bindValue(':num', $data['txtNumCandidato']);
            $stmt->bindValue(':nom_cand', $data['txtNomCandidato']);
            $stmt->bindValue(':aspirante', $data['txtAspirante']);
            $stmt->bindValue(':estado', $data['selEstado']);
            $stmt->bindValue(':logo', $logoName);
            $stmt->bindValue(':foto', $fotoName);
            
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=$_SESSION['lastlogin'];
                $nombre=$_SESSION['nombres'];
                $lastaccion="Ingreso datos de un candidato";
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

    public function actualizarCandidato($data, $files) {
        $id = intval($data['txtId']);
        
        // Build SQL dynamically based on file uploads
        $sql = "UPDATE candidatos SET nom_partido=:nom_part, num_candidato=:num, nom_candidato=:nom_cand, aspirante_a=:aspirante, estado_candidato=:estado";
        
        $params = array(
             ':nom_part' => $data['txtNomPartido'],
             ':num' => $data['txtNumCandidato'],
             ':nom_cand' => $data['txtNomCandidato'],
             ':aspirante' => $data['txtAspirante'],
             ':estado' => $data['selEstado'],
             ':id' => $id
        );

        if (isset($files['logo_partido']) && $files['logo_partido']['error'] == 0) {
             $logoName = time() . '_' . $files['logo_partido']['name'];
             move_uploaded_file($files['logo_partido']['tmp_name'], 'images/partidos/' . $logoName);
             $sql .= ", logo_partido=:logo";
             $params[':logo'] = $logoName;
        }
        
        if (isset($files['foto_candidato']) && $files['foto_candidato']['error'] == 0) {
             $fotoName = time() . '_' . $files['foto_candidato']['name'];
             move_uploaded_file($files['foto_candidato']['tmp_name'], 'images/partidos/' . $fotoName);
             $sql .= ", foto_candidato=:foto";
             $params[':foto'] = $fotoName;
        }

        $sql .= " WHERE id_candidato=:id";

        try {
            $stmt = $this->db->prepare($sql);
            foreach($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            
            if ($stmt->execute()) {
                 $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=$_SESSION['lastlogin'];
                $nombre=$_SESSION['nombres'];
                $lastaccion="Actualizo datos de un candidato";
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

    public function bajaCandidato($ids) {
        if(!is_array($ids)) { $ids = array($ids); }
        $ids = array_map('intval', $ids);
        $idsStr = implode(',', $ids);
        
        $sql = "DELETE FROM candidatos WHERE id_candidato IN ($idsStr)";
        try {
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=isset($_SESSION['lastlogin']) ? $_SESSION['lastlogin'] : '';
                $nombre=isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '';
                $lastaccion="Elimino candidatos con IDs: $idsStr";
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

    public function toggleEstadoCandidato($ids) {
        if(!is_array($ids)) { $ids = array($ids); }
        $ids = array_map('intval', $ids);
        $idsStr = implode(',', $ids);
        
        $sql = "UPDATE candidatos SET estado_candidato = IF(estado_candidato='Activo', 'Inactivo', 'Activo') WHERE id_candidato IN ($idsStr)";
        try {
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute()) {
                $fecha=date("d/m/Y");
                $hora=date("h:i:s A");
                $lastlogin=isset($_SESSION['lastlogin']) ? $_SESSION['lastlogin'] : '';
                $nombre=isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '';
                $lastaccion="Cambio estado de candidatos con IDs: $idsStr";
                $lasttipo="Modifico";
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
