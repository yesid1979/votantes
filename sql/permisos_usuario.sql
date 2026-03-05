-- =====================================================
-- Script para agregar permisos por usuario individual
-- Ejecutar en phpMyAdmin o consola MySQL
-- =====================================================

-- Tabla de permisos por usuario individual
CREATE TABLE IF NOT EXISTS `permisos_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `puede_ver` tinyint(1) DEFAULT 1,
  `puede_crear` tinyint(1) DEFAULT 1,
  `puede_editar` tinyint(1) DEFAULT 1,
  `puede_eliminar` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_modulo` (`id_usuario`, `id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================
