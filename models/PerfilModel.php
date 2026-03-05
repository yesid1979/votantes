<?php
require_once "funcs/class.conexion.php";
require_once __DIR__ . "/../funcs/funcs.php";

class PerfilModel {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->get_conexion();
    }

    public function getUsuarioById($id) {
        $sql = "SELECT a.id, a.ced_usuario, a.usuario, a.nombre, a.correo, a.last_session, 
                       a.id_tipo, b.tipo, a.tel_usuario, a.cel_usuario, a.dir_usuario, a.password_usu 
                FROM usuarios a 
                INNER JOIN tipo_usuario b ON a.id_tipo=b.id 
                WHERE a.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', intval($id));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPersonal($data) {
        $sql = "UPDATE usuarios SET 
                    ced_usuario = :ced, 
                    nombre = :nom, 
                    correo = :email, 
                    tel_usuario = :tel, 
                    cel_usuario = :cel, 
                    dir_usuario = :dir 
                WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ced', $data['cedula_up']);
            $stmt->bindValue(':nom', $data['nombre_up']);
            $stmt->bindValue(':email', $data['email_up']);
            $stmt->bindValue(':tel', $data['telefono_up']);
            $stmt->bindValue(':cel', $data['cel_up']);
            $stmt->bindValue(':dir', $data['direccion_up']);
            $stmt->bindValue(':id', $data['usuario_id']);
            
            if ($stmt->execute()) {
                // Update session name if changed
                if(isset($_SESSION['nombres'])) {
                    $_SESSION['nombres'] = $data['nombre_up'];
                }
                
                $this->registrarAuditoria("Actualizo datos del perfil (Personal)");
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function actualizarPassword($data) {
        // Hash password (assuming hashPassword function exists or using native)
        // Checking legacy code: it used `hashPassword` function from funcs.php
        $newPass = hashPassword($data['newPassword1_up']);
        
        $sql = "UPDATE usuarios SET password_usu = :pass WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':pass', $newPass);
            $stmt->bindValue(':id', $data['usuario_id']);
            
            if ($stmt->execute()) {
                $this->registrarAuditoria("Cambio de contraseña");
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    private function registrarAuditoria($accion) {
        $fecha = date("d/m/Y");
        $hora = date("h:i:s A");
        $lastlogin = $_SESSION['lastlogin'];
        $nombre = $_SESSION['nombres'];
        $lastaccion = $accion;
        $lasttipo = "Modifico";
        $tipoUser = $_SESSION['tipoUser'];
        $tomarip = ipCheck();
        
        auditoria1($fecha, $hora, $lastlogin, $nombre, $lastaccion, $lasttipo, $tipoUser, $tomarip);
    }
}
?>
