-- =====================================================
-- Script para crear el sistema de permisos dinámicos
-- Ejecutar en phpMyAdmin o consola MySQL
-- =====================================================

-- Tabla de módulos del sistema
CREATE TABLE IF NOT EXISTS `modulos` (
  `id_modulo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `icono` varchar(50) DEFAULT 'bi-circle',
  `url` varchar(100) NOT NULL,
  `orden` int(11) DEFAULT 0,
  `grupo` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de permisos por tipo de usuario
CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `puede_ver` tinyint(1) DEFAULT 1,
  `puede_crear` tinyint(1) DEFAULT 1,
  `puede_editar` tinyint(1) DEFAULT 1,
  `puede_eliminar` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_permiso`),
  UNIQUE KEY `tipo_modulo` (`id_tipo`, `id_modulo`),
  FOREIGN KEY (`id_tipo`) REFERENCES `tipo_usuario`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_modulo`) REFERENCES `modulos`(`id_modulo`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar los módulos del sistema
INSERT INTO `modulos` (`nombre`, `descripcion`, `icono`, `url`, `orden`, `grupo`, `activo`) VALUES
('Dashboard', 'Panel principal', 'bi-speedometer2', 'dashboard/index', 1, NULL, 1),
('Zonas', 'Gestión de zonas', 'bi-geo-alt', 'zona/index', 2, 'datos_maestros', 1),
('Candidatos', 'Gestión de candidatos', 'bi-person-vcard', 'candidato/index', 3, 'datos_maestros', 1),
('Simpatizantes', 'Gestión de simpatizantes', 'bi-emoji-smile', 'simpatizante/index', 4, NULL, 1),
('Votantes', 'Gestión de votantes', 'bi-person-check-fill', 'votante/index', 5, NULL, 1),
('Líderes', 'Gestión de líderes', 'bi-person-badge', 'lider/index', 6, NULL, 1),
('Usuarios', 'Gestión de usuarios', 'bi-people', 'usuario/index', 7, 'seguridad', 1),
('Auditoría', 'Registro de auditoría', 'bi-eye', 'auditoria/index', 8, 'seguridad', 1);

-- Obtener IDs de módulos (asumiendo que se insertaron en orden)
SET @mod_dashboard = 1;
SET @mod_zonas = 2;
SET @mod_candidatos = 3;
SET @mod_simpatizantes = 4;
SET @mod_votantes = 5;
SET @mod_lideres = 6;
SET @mod_usuarios = 7;
SET @mod_auditoria = 8;

-- Permisos para Administrador (id_tipo = 1) - TODOS
INSERT INTO `permisos` (`id_tipo`, `id_modulo`, `puede_ver`, `puede_crear`, `puede_editar`, `puede_eliminar`) VALUES
(1, @mod_dashboard, 1, 1, 1, 1),
(1, @mod_zonas, 1, 1, 1, 1),
(1, @mod_candidatos, 1, 1, 1, 1),
(1, @mod_simpatizantes, 1, 1, 1, 1),
(1, @mod_votantes, 1, 1, 1, 1),
(1, @mod_lideres, 1, 1, 1, 1),
(1, @mod_usuarios, 1, 1, 1, 1),
(1, @mod_auditoria, 1, 1, 1, 1);

-- Permisos para Digitador (id_tipo = 2) - Todo menos Usuarios y Auditoría
INSERT INTO `permisos` (`id_tipo`, `id_modulo`, `puede_ver`, `puede_crear`, `puede_editar`, `puede_eliminar`) VALUES
(2, @mod_dashboard, 1, 1, 1, 1),
(2, @mod_zonas, 1, 1, 1, 1),
(2, @mod_candidatos, 1, 1, 1, 1),
(2, @mod_simpatizantes, 1, 1, 1, 1),
(2, @mod_votantes, 1, 1, 1, 1),
(2, @mod_lideres, 1, 1, 1, 1),
(2, @mod_usuarios, 0, 0, 0, 0),
(2, @mod_auditoria, 0, 0, 0, 0);

-- Permisos para Líder (id_tipo = 3) - Solo Simpatizantes y Votantes
INSERT INTO `permisos` (`id_tipo`, `id_modulo`, `puede_ver`, `puede_crear`, `puede_editar`, `puede_eliminar`) VALUES
(3, @mod_dashboard, 1, 0, 0, 0),
(3, @mod_zonas, 0, 0, 0, 0),
(3, @mod_candidatos, 0, 0, 0, 0),
(3, @mod_simpatizantes, 1, 1, 1, 1),
(3, @mod_votantes, 1, 1, 1, 1),
(3, @mod_lideres, 0, 0, 0, 0),
(3, @mod_usuarios, 0, 0, 0, 0),
(3, @mod_auditoria, 0, 0, 0, 0);

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================
