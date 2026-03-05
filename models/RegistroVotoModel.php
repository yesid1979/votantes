<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php";

class RegistroVotoModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function guardarVotos($data, $id_usuario) {
        $sql = "INSERT INTO registro_votos (dpto, muni, zona, puesto, mesa, aspirante, id_candidato, votos, id_usuario) 
                VALUES (:dpto, :muni, :zona, :puesto, :mesa, :aspirante, :id_candidato, :votos, :id_usuario)";
        
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare($sql);
            
            // Loop through each table (mesa) votes
            foreach ($data['mesas'] as $mesaNum => $cantidadVotos) {
                // Ignore empty or 0 if we want, but saving 0 might also be useful to know. 
                // Let's save only if it's > 0, or maybe save all configured that have an explicit value.
                if ($cantidadVotos !== '' && $cantidadVotos !== null) {
                    $stmt->bindValue(':dpto', $data['dpto']);
                    $stmt->bindValue(':muni', $data['muni']);
                    $stmt->bindValue(':zona', $data['zona']);
                    $stmt->bindValue(':puesto', $data['puesto']);
                    $stmt->bindValue(':mesa', $mesaNum);
                    $stmt->bindValue(':aspirante', $data['aspirante']);
                    $stmt->bindValue(':id_candidato', $data['cboCandidato']);
                    $stmt->bindValue(':votos', intval($cantidadVotos));
                    $stmt->bindValue(':id_usuario', $id_usuario);
                    $stmt->execute();
                }
            }
            
            $this->db->commit();
            
            // Auditoria
            $fecha=date("d/m/Y");
            $hora=date("h:i:s A");
            $lastlogin=isset($_SESSION['lastlogin']) ? $_SESSION['lastlogin'] : '';
            $nombre=isset($_SESSION['nombres']) ? $_SESSION['nombres'] : '';
            $lastaccion="Registro votos para " . $data['aspirante'] . " en Zona " . $data['zona'] . " Puesto " . $data['puesto'];
            $lasttipo="Registro";
            $tipoUser=isset($_SESSION['tipoUser']) ? $_SESSION['tipoUser'] : '';
            $tomarip=ipCheck();
            auditoria1($fecha, $hora, $lastlogin, $nombre, $lastaccion, $lasttipo, $tipoUser, $tomarip);
            
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function resultadosGenerales($aspirante, $dpto = '', $muni = '', $zona = '', $puesto = '') {
        $sql = "SELECT r.id_candidato, sum(r.votos) as total_votos, 
                c.nom_candidato, c.nom_partido, c.foto_candidato, c.logo_partido
                FROM registro_votos r
                LEFT JOIN candidatos c ON c.id_candidato = r.id_candidato
                WHERE r.aspirante = :aspirante ";
                
        $params = array(':aspirante' => $aspirante);
        
        if(!empty($dpto)) {
            $sql .= " AND r.dpto = :dpto ";
            $params[':dpto'] = $dpto;
        }
        if(!empty($muni)) {
            $sql .= " AND r.muni = :muni ";
            $params[':muni'] = $muni;
        }
        if(!empty($zona)) {
            $sql .= " AND r.zona = :zona ";
            $params[':zona'] = $zona;
        }
        if(!empty($puesto)) {
            $sql .= " AND r.puesto = :puesto ";
            $params[':puesto'] = $puesto;
        }
        
        $sql .= " GROUP BY r.id_candidato 
                  ORDER BY total_votos DESC";
                  
        $stmt = $this->db->prepare($sql);
        foreach($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatear especiales
        $datos = array();
        foreach($resultados as $r) {
            if ($r['id_candidato'] == 'EN_BLANCO') {
                $r['nom_candidato'] = 'VOTOS EN BLANCO';
                $r['nom_partido'] = '-';
            } else if ($r['id_candidato'] == 'NULOS') {
                $r['nom_candidato'] = 'VOTOS NULOS';
                $r['nom_partido'] = '-';
            } else if ($r['id_candidato'] == 'NO_MARCADOS') {
                $r['nom_candidato'] = 'TARJETONES NO MARCADOS';
                $r['nom_partido'] = '-';
            }
            $datos[] = $r;
        }
        
        return $datos;
    }

    public function detallePorZonaPuesto($id_candidato, $aspirante, $dpto = '', $muni = '') {
        $sql = "SELECT r.zona, r.puesto, r.mesa, r.votos, r.dpto, r.muni,
                       z.nom_puesto
                FROM registro_votos r
                LEFT JOIN zonas z ON z.num_zona = r.zona 
                                 AND z.pues_zona = r.puesto
                                 AND z.dpto_zona = r.dpto
                                 AND z.mun_zona  = r.muni
                WHERE r.id_candidato = :id_candidato AND r.aspirante = :aspirante ";

        $params = [':id_candidato' => $id_candidato, ':aspirante' => $aspirante];

        if (!empty($dpto)) {
            $sql .= " AND r.dpto = :dpto ";
            $params[':dpto'] = $dpto;
        }
        if (!empty($muni)) {
            $sql .= " AND r.muni = :muni ";
            $params[':muni'] = $muni;
        }

        $sql .= " ORDER BY r.dpto ASC, r.muni ASC, r.zona ASC, r.puesto ASC, r.mesa ASC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
