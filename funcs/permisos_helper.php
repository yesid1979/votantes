<?php
if (!isset($_SESSION)) {
    session_start();
}

/**
 * Función para verificar permisos combinados (Híbrido)
 * 1. Busca si el usuario tiene un permiso específico.
 * 2. Si NO hay registro específico para el usuario, usa el permiso del ROL.
 * 
 * @param int $id_tipo_usuario El ID del rol del usuario
 * @param string $modulo_nombre El nombre del módulo (ej: 'usuario', 'auditoria')
 * @param string $accion La acción a verificar ('ver', 'crear', 'editar', 'eliminar')
 * @return bool
 */
function tienePermiso($id_tipo_usuario, $modulo_nombre, $accion) {
    // Si es super user o similar podrías poner una excepción aquí
    
    // 1. Obtener ID del usuario de la sesión
    $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;
    
    if (!$id_usuario) return false;

    // Obtener conexión
    require_once __DIR__ . '/class.conexion.php';
    $conn = new Conexion();
    $db = $conn->get_conexion();

    try {
        // Mapear la acción al nombre de la columna en la base de datos
        $columna = "puede_" . $accion;
        if ($accion == 'ver') $columna = "puede_ver"; 
        if ($accion == 'ver_todos') $columna = "puede_ver_todo";

        // A. BUSCAR PERMISO ESPECÍFICO DEL USUARIO
        $sqlUser = "SELECT pu.$columna 
                    FROM permisos_usuario pu
                    INNER JOIN modulos m ON pu.id_modulo = m.id_modulo
                    WHERE pu.id_usuario = :id_usuario AND m.nombre = :modulo";
        
        $stmtUser = $db->prepare($sqlUser);
        $stmtUser->bindValue(':id_usuario', $id_usuario);
        $stmtUser->bindValue(':modulo', $modulo_nombre);
        $stmtUser->execute();
        
        $permisoUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

        // Si existe un registro para este usuario, ESA ES LA ÚLTIMA PALABRA (pueda o no pueda)
        if ($permisoUser) {
            return $permisoUser[$columna] == 1;
        }

        // B. SI NO HAY PERMISO ESPECÍFICO, BUSCAR POR ROL
        $sqlRole = "SELECT p.$columna 
                    FROM permisos p
                    INNER JOIN modulos m ON p.id_modulo = m.id_modulo
                    WHERE p.id_tipo = :id_tipo AND m.nombre = :modulo";
        
        $stmtRole = $db->prepare($sqlRole);
        $stmtRole->bindValue(':id_tipo', $id_tipo_usuario);
        $stmtRole->bindValue(':modulo', $modulo_nombre);
        $stmtRole->execute();
        
        $permisoRole = $stmtRole->fetch(PDO::FETCH_ASSOC);

        if ($permisoRole) {
            return $permisoRole[$columna] == 1;
        }

        return false; // Por defecto no tiene permiso si no hay configuración
    } catch (Exception $e) {
        error_log("Error en tienePermiso: " . $e->getMessage());
        return false;
    }
}
?>
