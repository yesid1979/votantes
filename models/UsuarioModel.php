<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php"; 

class UsuarioModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }
    
    public function getUsuariosAjax($requestData) {
        $columns = array( 
            0 => 'id', 1 => 'ced_usuario', 2 => 'usuario', 
            3 => 'nombre', 4 => 'correo', 5 => 'tipo', 6 => 'activacion'  
        );

        $sqlTotal = "SELECT count(id) as total FROM usuarios";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute();
        $rowTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $totalData = $rowTotal['total'];
        $totalFiltered = $totalData;

        $sqlBase = "FROM usuarios a INNER JOIN tipo_usuario b ON a.id_tipo = b.id WHERE 1=1 ";
        $params = array();

        if(!empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $sqlBase .= "AND (a.id LIKE :search OR a.ced_usuario LIKE :search OR a.usuario LIKE :search OR a.nombre LIKE :search OR a.correo LIKE :search OR b.tipo LIKE :search) ";
            $params[':search'] = $searchValue . '%';
            
            $sqlCountFilter = "SELECT count(a.id) as total " . $sqlBase;
            $stmtCount = $this->db->prepare($sqlCountFilter);
            foreach($params as $key => $val) { $stmtCount->bindValue($key, $val); }
            $stmtCount->execute();
            $rowCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $totalFiltered = $rowCount['total'];
        }

        $sqlData = "SELECT a.id, a.ced_usuario, a.usuario, a.nombre, a.correo, a.last_session, a.activacion, a.id_tipo, b.tipo " . $sqlBase;
        
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

    public function getUsuarioById($id) {
        $sql = "SELECT a.*, b.tipo 
                FROM usuarios a 
                INNER JOIN tipo_usuario b ON a.id_tipo = b.id 
                WHERE a.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTiposUsuario() {
        $sql = "SELECT * FROM tipo_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarUsuario($data) {
        $pass_hash = password_hash($data['txtPassword'], PASSWORD_DEFAULT);
        
        $dpto = isset($data['dpto_asignada']) ? $data['dpto_asignada'] : 'VALLE';
        $muni = isset($data['muni_asignada']) ? $data['muni_asignada'] : 'CALI';
        $zona = isset($data['zona_asignada']) ? $data['zona_asignada'] : null;
        $puesto = isset($data['puesto_asignado']) ? $data['puesto_asignado'] : null;

        $sql = "INSERT INTO usuarios (ced_usuario, nombre, usuario, password_usu, correo, id_tipo, dir_usuario, tel_usuario, cel_usuario, activacion, dpto_asignado, muni_asignado, zona_asignada, puesto_asignado) 
                VALUES (:ced, :nom, :usu, :pass, :email, :tipo, :dir, :tel, :cel, :est, :dpto, :muni, :zon, :pue)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ced', $data['txtCed_usuario']);
            $stmt->bindValue(':nom', $data['txtNom_usuario']);
            $stmt->bindValue(':usu', $data['txtUsuario']);
            $stmt->bindValue(':pass', $pass_hash);
            $stmt->bindValue(':email', $data['txtEmail']);
            $stmt->bindValue(':tipo', $data['Cbotipo']);
            $stmt->bindValue(':dir', $data['txtDir_usuario']);
            $stmt->bindValue(':tel', $data['txtTel_usuario']);
            $stmt->bindValue(':cel', $data['txtCel_usuario']);
            $stmt->bindValue(':est', $data['CboEstado']);
            $stmt->bindValue(':dpto', $dpto);
            $stmt->bindValue(':muni', $muni);
            $stmt->bindValue(':zon', $zona);
            $stmt->bindValue(':pue', $puesto);
            
            if ($stmt->execute()) {
                $id_usuario = $this->db->lastInsertId();
                if(isset($data['permisos']) && is_array($data['permisos'])) {
                    $this->guardarPermisosUsuario($id_usuario, $data['permisos']);
                }
                return true; 
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function actualizarUsuario($data) {
        $id = intval($data['txtId']);
        $tab = isset($data['tab']) ? $data['tab'] : 'datos';

        try {
            // SI SOLO ESTAMOS ACTUALIZANDO PERMISOS, NO TOCAMOS LOS DATOS DEL USUARIO
            if ($tab == 'permisos') {
                if(isset($data['permisos']) && is_array($data['permisos'])) {
                    return $this->guardarPermisosUsuario($id, $data['permisos']);
                }
                return true;
            }

            $dpto = isset($data['dpto_asignada']) ? $data['dpto_asignada'] : 'VALLE';
            $muni = isset($data['muni_asignada']) ? $data['muni_asignada'] : 'CALI';
            $zona = isset($data['zona_asignada']) ? $data['zona_asignada'] : null;
            $puesto = isset($data['puesto_asignado']) ? $data['puesto_asignado'] : null;

            // SI ESTAMOS ACTUALIZANDO DATOS
            $sql = "UPDATE usuarios SET ced_usuario=:ced, nombre=:nom, usuario=:usu, correo=:email, id_tipo=:tipo, dir_usuario=:dir, tel_usuario=:tel, cel_usuario=:cel, activacion=:est, dpto_asignado=:dpto, muni_asignado=:muni, zona_asignada=:zon, puesto_asignado=:pue";
            
            $hasPassword = !empty($data['txtPassword']);
            if ($hasPassword) {
                $sql .= ", password_usu=:pass";
            }
            $sql .= " WHERE id=:id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':ced', $data['txtCed_usuario']);
            $stmt->bindValue(':nom', $data['txtNom_usuario']);
            $stmt->bindValue(':usu', $data['txtUsuario']);
            $stmt->bindValue(':email', $data['txtEmail']);
            $stmt->bindValue(':tipo', $data['Cbotipo']);
            $stmt->bindValue(':dir', $data['txtDir_usuario']);
            $stmt->bindValue(':tel', $data['txtTel_usuario']);
            $stmt->bindValue(':cel', $data['txtCel_usuario']);
            $stmt->bindValue(':est', $data['CboEstado']);
            $stmt->bindValue(':dpto', $dpto);
            $stmt->bindValue(':muni', $muni);
            $stmt->bindValue(':zon', $zona);
            $stmt->bindValue(':pue', $puesto);
            
            if ($hasPassword) {
                $pass_hash = password_hash($data['txtPassword'], PASSWORD_DEFAULT);
                $stmt->bindValue(':pass', $pass_hash);
            }
            
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    private function guardarPermisosUsuario($id_usuario, $permisos) {
        try {
            $sqlDelete = "DELETE FROM permisos_usuario WHERE id_usuario = :id_usuario";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->bindValue(':id_usuario', $id_usuario);
            $stmtDelete->execute();

            if (empty($permisos)) return true;

            $sqlInsert = "INSERT INTO permisos_usuario (id_usuario, id_modulo, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_ver_todo) 
                          VALUES (:id_usuario, :id_modulo, :puede_ver, :puede_crear, :puede_editar, :puede_eliminar, :puede_ver_todo)";
            $stmtInsert = $this->db->prepare($sqlInsert);

            foreach ($permisos as $id_modulo => $acciones) {
                $ver = isset($acciones['ver']) ? 1 : 0;
                $crear = isset($acciones['crear']) ? 1 : 0;
                $editar = isset($acciones['editar']) ? 1 : 0;
                $eliminar = isset($acciones['eliminar']) ? 1 : 0;
                $ver_todo = isset($acciones['ver_todo']) ? 1 : 0;

                // Guardamos SIEMPRE el registro para que el "0" (desmarcado) sea una orden directa de prohibir
                $stmtInsert->bindValue(':id_usuario', $id_usuario);
                $stmtInsert->bindValue(':id_modulo', $id_modulo);
                $stmtInsert->bindValue(':puede_ver', $ver);
                $stmtInsert->bindValue(':puede_crear', $crear);
                $stmtInsert->bindValue(':puede_editar', $editar);
                $stmtInsert->bindValue(':puede_eliminar', $eliminar);
                $stmtInsert->bindValue(':puede_ver_todo', $ver_todo);
                $stmtInsert->execute();
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updatePerfil($id, $nombre, $email, $tel, $cel, $dir) {
        try {
            $sql = "UPDATE usuarios SET nombre=:nom, correo=:email, tel_usuario=:tel, cel_usuario=:cel, dir_usuario=:dir WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':nom', $nombre);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':tel', $tel);
            $stmt->bindValue(':cel', $cel);
            $stmt->bindValue(':dir', $dir);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updatePerfil: " . $e->getMessage());
            return false;
        }
    }

    public function changePassword($id, $newPassword) {
        try {
            $pass_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET password_usu=:pass WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':pass', $pass_hash);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error changePassword: " . $e->getMessage());
            return false;
        }
    }

    public function bajaUsuario($ids) {
        if(!is_array($ids)) $ids = array($ids);
        $idsStr = implode(',', array_map('intval', $ids));
        $sql = "DELETE FROM usuarios WHERE id IN ($idsStr)";
        return $this->db->query($sql);
    }
}
?>
