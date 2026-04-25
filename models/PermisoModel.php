<?php
require_once __DIR__ . "/../funcs/class.conexion.php";

class PermisoModel {
    private $db;

    public function __construct() {
        try {
            $conexion = new Conexion();
            $this->db = $conexion->get_conexion();
        } catch (Exception $e) {
            error_log("Error en PermisoModel: " . $e->getMessage());
        }
    }

    public function getTiposUsuario() {
        try {
            $sql = "SELECT id, tipo FROM tipo_usuario ORDER BY tipo ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getTiposUsuario: " . $e->getMessage());
            return array();
        }
    }

    public function getUsuarios() {
        try {
            $sql = "SELECT id_usuario, nombres FROM usuarios ORDER BY nombres ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getUsuarios: " . $e->getMessage());
            return array();
        }
    }

    public function getModulos() {
        try {
            // Agrupamos por nombre para evitar que los duplicados aparezcan varias veces
            $sql = "SELECT MIN(id_modulo) as id_modulo, nombre, icono, grupo FROM modulos GROUP BY nombre ORDER BY grupo ASC, nombre ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getModulos: " . $e->getMessage());
            return array();
        }
    }

    public function getPermisosByUsuario($id_usuario) {
        try {
            $sql = "SELECT id_modulo, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_ver_todo 
                    FROM permisos_usuario 
                    WHERE id_usuario = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_usuario', $id_usuario);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getPermisosByUsuario: " . $e->getMessage());
            return array();
        }
    }

    public function getPermisosByTipo($id_tipo) {
        try {
            $sql = "SELECT id_modulo, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_ver_todo 
                    FROM permisos 
                    WHERE id_tipo = :id_tipo";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_tipo', $id_tipo);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getPermisosByTipo: " . $e->getMessage());
            return array();
        }
    }

    public function tienePermisosPersonalizados($id_usuario) {
        $sql = "SELECT COUNT(*) FROM permisos_usuario WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_usuario]);
        return $stmt->fetchColumn() > 0;
    }

    public function guardarPermisosRol($id_tipo, $permisos) {
        try {
            $this->db->beginTransaction();
            // Limpiar permisos actuales del rol
            $this->db->prepare("DELETE FROM permisos WHERE id_tipo = ?")->execute([$id_tipo]);
            
            foreach($permisos as $id_modulo => $acc) {
                $sql = "INSERT INTO permisos (id_tipo, id_modulo, puede_ver, puede_crear, puede_editar, puede_eliminar) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $this->db->prepare($sql)->execute([
                    $id_tipo, 
                    $id_modulo, 
                    isset($acc['ver']) ? 1 : 0,
                    isset($acc['crear']) ? 1 : 0,
                    isset($acc['editar']) ? 1 : 0,
                    isset($acc['eliminar']) ? 1 : 0
                ]);
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function guardarPermisosUsuario($id_usuario, $permisos) {
        try {
            $this->db->beginTransaction();
            // 1. Obtener todos los IDs de módulos existentes
            $modulos = $this->db->query("SELECT id_modulo FROM modulos")->fetchAll(PDO::FETCH_COLUMN);
            
            // 2. Limpiar permisos actuales
            $this->db->prepare("DELETE FROM permisos_usuario WHERE id_usuario = ?")->execute([$id_usuario]);
            
            // 3. Insertar registro para CADA módulo (si no viene en $permisos, se guarda en 0)
            foreach($modulos as $id_modulo) {
                $acc = isset($permisos[$id_modulo]) ? $permisos[$id_modulo] : array();
                
                $sql = "INSERT INTO permisos_usuario (id_usuario, id_modulo, puede_ver, puede_crear, puede_editar, puede_eliminar) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $this->db->prepare($sql)->execute([
                    $id_usuario, 
                    $id_modulo, 
                    isset($acc['ver']) ? 1 : 0,
                    isset($acc['crear']) ? 1 : 0,
                    isset($acc['editar']) ? 1 : 0,
                    isset($acc['eliminar']) ? 1 : 0
                ]);
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error guardarPermisosUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarPermisosUsuario($id_usuario) {
        $sql = "DELETE FROM permisos_usuario WHERE id_usuario = :id";
        return $this->db->prepare($sql)->execute([':id' => $id_usuario]);
    }
}
