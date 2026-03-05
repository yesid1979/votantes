/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50527
Source Host           : localhost:3306
Source Database       : votantes

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2019-08-01 22:17:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `auditoria`
-- ----------------------------
DROP TABLE IF EXISTS `auditoria`;
CREATE TABLE `auditoria` (
  `lastnro` int(11) NOT NULL AUTO_INCREMENT,
  `lastfecha` varchar(50) DEFAULT NULL,
  `lasthora` varchar(50) DEFAULT NULL,
  `lastlogin` varchar(50) DEFAULT NULL,
  `lastname` varchar(200) DEFAULT NULL,
  `lastaccion` mediumtext,
  `lasttipo` varchar(200) DEFAULT NULL,
  `tipousu` varchar(50) DEFAULT NULL,
  `lastip` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`lastnro`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auditoria
-- ----------------------------
INSERT INTO `auditoria` VALUES ('2', '28/07/2019', '01:59:03 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('3', '28/07/2019', '04:34:55 PM', 'daniel.piedrahita', 'Jose Daniel Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Lider', '::1');
INSERT INTO `auditoria` VALUES ('4', '28/07/2019', '04:38:51 PM', 'daniel.piedrahita', 'Jose Daniel Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Lider', '::1');
INSERT INTO `auditoria` VALUES ('5', '28/07/2019', '04:47:50 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('6', '28/07/2019', '04:57:11 PM', 'daniel.piedrahita', 'Jose Daniel Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Lider', '::1');
INSERT INTO `auditoria` VALUES ('7', '28/07/2019', '05:01:02 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('8', '28/07/2019', '05:10:38 PM', 'daniel.piedrahita', 'Jose Daniel Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Lider', '::1');
INSERT INTO `auditoria` VALUES ('9', '28/07/2019', '07:13:51 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('10', '28/07/2019', '08:39:45 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('11', '28/07/2019', '08:40:34 PM', 'yesid.piedrahita', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Digitador', '::1');
INSERT INTO `auditoria` VALUES ('12', '28/07/2019', '08:46:47 PM', 'daniel.piedrahita', 'Jose Daniel Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Lider', '::1');
INSERT INTO `auditoria` VALUES ('13', '28/07/2019', '08:53:33 PM', 'yesid.piedrahita', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Digitador', '::1');
INSERT INTO `auditoria` VALUES ('14', '28/07/2019', '08:54:41 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('15', '28/07/2019', '08:58:16 PM', 'yesid.piedrahita', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Digitador', '::1');
INSERT INTO `auditoria` VALUES ('16', '28/07/2019', '09:00:11 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('17', '28/07/2019', '09:01:33 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '192.168.1.4');
INSERT INTO `auditoria` VALUES ('18', '28/07/2019', '09:09:19 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('19', '28/07/2019', '10:43:04 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('20', '28/07/2019', '10:49:33 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('21', '29/07/2019', '08:21:38 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('23', '29/07/2019', '08:31:56 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '192.168.1.6');
INSERT INTO `auditoria` VALUES ('24', '29/07/2019', '08:35:31 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '192.168.1.3');
INSERT INTO `auditoria` VALUES ('25', '29/07/2019', '08:59:01 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('26', '31/07/2019', '09:12:20 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('27', '31/07/2019', '09:25:11 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('28', '31/07/2019', '09:26:15 PM', 'leidy.semanate', 'Leidy Johana Semanate Franco', 'Ingreso al sistema', 'Inicio session', 'Digitador', '::1');
INSERT INTO `auditoria` VALUES ('29', '31/07/2019', '09:26:39 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('30', '31/07/2019', '10:40:36 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos de un usuario', 'Modifico', null, '::1');
INSERT INTO `auditoria` VALUES ('31', '31/07/2019', '10:58:45 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('32', '31/07/2019', '10:59:37 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos de un usuario', 'Modifico', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('33', '01/08/2019', '08:53:06 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso al sistema', 'Inicio session', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('35', '01/08/2019', '08:56:38 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Borro datos en la auditoria', 'Elimino', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('36', '01/08/2019', '08:57:21 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos de una zona', 'Modifico', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('38', '01/08/2019', '08:59:11 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Borro datos en la auditoria', 'Elimino', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('39', '01/08/2019', '08:59:37 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos de un lider', 'Modifico', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('40', '01/08/2019', '09:00:12 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos de un votante', 'Modifico', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('41', '01/08/2019', '09:00:52 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos de un usuario', 'Modifico', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('42', '01/08/2019', '09:43:53 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos de un votante', 'Modifico', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('43', '01/08/2019', '09:54:19 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Ingreso datos de un votante', 'Registro', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('44', '01/08/2019', '10:01:00 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos de un lider', 'Modifico', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('45', '01/08/2019', '10:15:34 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos en mis datos', 'Modifico', 'Administrador', '::1');
INSERT INTO `auditoria` VALUES ('46', '01/08/2019', '10:16:06 PM', 'yesid1979', 'Yesid Javier Piedrahita Correa', 'Actualizo datos del perfil', 'Modifico', 'Administrador', '::1');

-- ----------------------------
-- Table structure for `lideres`
-- ----------------------------
DROP TABLE IF EXISTS `lideres`;
CREATE TABLE `lideres` (
  `id_lider` int(11) NOT NULL AUTO_INCREMENT,
  `ced_lider` varchar(60) DEFAULT NULL,
  `nom_lider` varchar(300) DEFAULT NULL,
  `dir_lider` varchar(100) DEFAULT NULL,
  `barrio_lider` varchar(60) DEFAULT NULL,
  `comuna_lider` varchar(50) DEFAULT NULL,
  `email_lider` varchar(100) DEFAULT NULL,
  `tel_lider` varchar(60) DEFAULT NULL,
  `cel_lider` varchar(60) DEFAULT NULL,
  `zona_lider` varchar(50) CHARACTER SET utf8 COLLATE utf8_esperanto_ci DEFAULT NULL,
  `puesto_lider` varchar(50) DEFAULT NULL,
  `nom_puesto` varchar(100) DEFAULT NULL,
  `dir_puesto` varchar(100) DEFAULT NULL,
  `mesa_lider` varchar(50) DEFAULT NULL,
  `proy_lider` varchar(50) DEFAULT NULL,
  `tran_lider` varchar(50) DEFAULT NULL,
  `fechnac_lider` varchar(50) DEFAULT NULL,
  `edad_lider` varchar(50) DEFAULT NULL,
  `fech_cumplelider` varchar(60) DEFAULT NULL,
  `cumple_lider` varchar(60) DEFAULT NULL,
  `sexo_lider` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id_lider`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lideres
-- ----------------------------
INSERT INTO `lideres` VALUES ('3', '16839817', 'Yesid Javier Piedrahita Correa', 'Carrera 3Asur # 6-119', 'Parques de Castilla', '0', 'yjpc79@gmail.com', '5152011', '3016744172', '33', '1', 'Colegio Benett', 'Kr106 Cl17 Esq. Avenida Cascajal', '08', '2', 'Particular', '1979-10-21', '39', '21/10', null, 'Masculino');

-- ----------------------------
-- Table structure for `tipo_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_usuario`;
CREATE TABLE `tipo_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tipo_usuario
-- ----------------------------
INSERT INTO `tipo_usuario` VALUES ('1', 'Administrador');
INSERT INTO `tipo_usuario` VALUES ('2', 'Digitador');
INSERT INTO `tipo_usuario` VALUES ('3', 'Lider');

-- ----------------------------
-- Table structure for `usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ced_usuario` varchar(60) DEFAULT NULL,
  `usuario` varchar(30) NOT NULL,
  `password_usu` text NOT NULL,
  `nombre` varchar(300) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `last_session` datetime DEFAULT NULL,
  `activacion` int(11) NOT NULL DEFAULT '0',
  `token` varchar(40) NOT NULL,
  `token_password` varchar(100) DEFAULT NULL,
  `password_request` int(11) DEFAULT '0',
  `id_tipo` int(11) NOT NULL,
  `dir_usuario` varchar(160) DEFAULT NULL,
  `tel_usuario` varchar(60) DEFAULT NULL,
  `cel_usuario` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES ('1', '16839817', 'yesid1979', '$2y$10$EsnukcZhXUt3AXxJ7Ac74.1CSWNwJXdS3MJ.sjNAwDonXAG1RCqSe', 'Yesid Javier Piedrahita Correa', 'yjpc79@gmail.com', '2019-08-01 20:53:06', '1', '4986035b30eacd1b4cac408bdaa7576e', '', '0', '1', 'Carrera 3Asur # 6-119 Parques de Castilla', '5152011', '3016744172');
INSERT INTO `usuarios` VALUES ('3', '16839290', 'daniel.piedrahita', '$2y$10$mnKrrgrRb4hlKXSWsjcp8.yZjNMiDpcEk7ukNlZtJ.zOxXEClkED2', 'Jose Daniel Piedrahita Correa', 'jdpc78@hotmail.com', '2019-07-28 20:46:47', '1', '', '', '0', '3', 'Calle 10c # 10-09', '5152011', '3016744172');
INSERT INTO `usuarios` VALUES ('5', '29706426', 'leidy.semanate', '$2y$10$qMAdlQaAQm6eKA8zfrI8V.HWTbVvZ5NuP5jha2lsKPjqJou9A7Lty', 'Leidy Johana Semanate Franco', 'leydijoha@gmail.com', '2019-07-31 21:26:15', '1', '', '', '0', '2', 'Carrera 3Asur # 6-119 Parques de Castilla', '5152011', '3014480498');

-- ----------------------------
-- Table structure for `votantes`
-- ----------------------------
DROP TABLE IF EXISTS `votantes`;
CREATE TABLE `votantes` (
  `id_votante` int(11) NOT NULL AUTO_INCREMENT,
  `ced_votante` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nom_votante` varchar(300) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dir_votante` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `barrio_votante` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `comuna_votante` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email_votante` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tel_votante` varchar(70) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cel_votante` varchar(70) COLLATE utf8_spanish_ci DEFAULT NULL,
  `zona_votante` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `puesto_votante` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nom_puestov` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dir_puestov` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `mesa_votante` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechnac_votante` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `edad_votante` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fech_cumplevotante` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cumple_votante` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sexo_votante` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `ced_lider` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_votante`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of votantes
-- ----------------------------
INSERT INTO `votantes` VALUES ('1', '29706426', 'Leidy Johana Semanate Franco', 'Carrera 3Asur # 1-119', 'Parques de Castilla', '0', 'leydijoha@gmail.com', '5152011', '3014480498', '33', '1', 'Colegio Benett', 'Kr106 Cl17 Esq. Avenida Cascajal', '08', '1983-03-20', '36', '20/03', null, 'Femenino', '16839817');
INSERT INTO `votantes` VALUES ('2', '16839290', 'Jose Daniel Piedrahita Correa', 'Calle 10c # 10c-06', 'Portal de Jamundi', '0', 'jdpc79@gmail.com', '5152011', '3184791348', '33', '1', 'Colegio Benett', 'Kr106 Cl17 Esq. Avenida Cascajal', '02', '1978-02-19', '41', '19/02', null, 'Masculino', '16839817');

-- ----------------------------
-- Table structure for `zonas`
-- ----------------------------
DROP TABLE IF EXISTS `zonas`;
CREATE TABLE `zonas` (
  `id_zona` int(11) NOT NULL AUTO_INCREMENT,
  `num_zona` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pues_zona` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `mun_zona` varchar(70) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nom_puesto` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `comuna_zona` int(11) DEFAULT NULL,
  `dir_zona` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `barr_zona` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado_zona` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_zona`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of zonas
-- ----------------------------
INSERT INTO `zonas` VALUES ('1', '1', '1', 'Cali', 'Escuela Jose Acevedo y Gomez', '1', 'Av 5 Oeste # 30-164', 'Vista Hermosa', 'Activo');
INSERT INTO `zonas` VALUES ('2', '1', '2', 'Cali', 'Institución Educativa Jose Holguin Garces', '1', 'Av 4A Oeste # 23-108', 'Terron Colorado', 'Activo');
INSERT INTO `zonas` VALUES ('3', '1', '3', 'Cali', 'Instituccion Educativa Jose Celestino Mutis', '1', 'Av 7 B Oeste # 18-02', 'Terron Colorado', 'Activo');
INSERT INTO `zonas` VALUES ('4', '1', '4', 'Cali', 'Coliseo Bajo Aguacatal', '1', 'Cl 8 Oeste # 14-04', 'Bajo Aguacatal', 'Activo');
INSERT INTO `zonas` VALUES ('5', '1', '5', 'Cali', 'Institución Educativa Cecilia Caballero', '1', 'Av 5 Oeste # 34-100', 'Vista Hermosa', 'Activo');
INSERT INTO `zonas` VALUES ('6', '1', '6', 'Cali', 'Sede Marice Sinisterra Terron', '1', 'Cl 30 Bis Oeste No 4A-00', 'Vista Hermosa', 'Activo');
INSERT INTO `zonas` VALUES ('7', '1', '7', 'Cali', 'Colegio Santa Isabel De Hungria', '1', 'Av 9 Oeste No 19C 1- 100', 'Bajo Aguacatal', 'Activo');
INSERT INTO `zonas` VALUES ('8', '2', '1', 'Cali', 'Institución Universitaria Antonio Jose Camacho', '2', 'Av 6N # 28-102', 'San Vicente', 'Activo');
INSERT INTO `zonas` VALUES ('9', '2', '2', 'Cali', 'Edificio Ferrocarriles Nacion', '2', 'Av 3N #23N-59', 'San Vicente', 'Activo');
INSERT INTO `zonas` VALUES ('10', '2', '3', 'Cali', 'C.A.M.', '2', 'Av 2N Cl 10 Y 11', 'Centenario', 'Activo');
INSERT INTO `zonas` VALUES ('11', '2', '4', 'Cali', 'Colegio Odontologico', '2', 'Cl 13N #3N-13', 'Granada', 'Activo');
INSERT INTO `zonas` VALUES ('12', '2', '5', 'Cali', 'Academia De Dibujo Profesional', '2', 'Cll 27 N #6Bn-50', 'Santa Monica', 'Activo');
INSERT INTO `zonas` VALUES ('13', '2', '6', 'Cali', 'Colegio Hispanoamericano', '2', 'Av 3 Cn #35N-55', 'Prados Del Norte', 'Activo');
INSERT INTO `zonas` VALUES ('14', '3', '1', 'Cali', 'Colegio Miguel Camacho Perea', '2', 'Av 3N #44N-100', 'Vipasa', 'Activo');
INSERT INTO `zonas` VALUES ('15', '3', '2', 'Cali', 'Institucion Educativa Santa Cecilia Sede 2', '2', 'Cll. 62 # 28   N  -00', 'Los Alamos', 'Activo');
INSERT INTO `zonas` VALUES ('16', '3', '3', 'Cali', 'Escuela Republica Del Brasil', '2', 'Cll 43N No 7N-03', 'La Campiña', 'Activo');
INSERT INTO `zonas` VALUES ('17', '3', '4', 'Cali', 'Escuela Brisas De Los Alamos', '2', 'Av 2 Bn Cl 72 An', 'Brisas De Los Alamos', 'Activo');
INSERT INTO `zonas` VALUES ('18', '3', '5', 'Cali', 'Club de Leones La Merced', '2', 'Cl 44 N #3D-40', 'Vipasa', 'Activo');
INSERT INTO `zonas` VALUES ('19', '4', '1', 'Cali', 'Asamblea Departamental', '3', 'Cl9 #8-60 Edif. San Luis', 'Santa Rosa', 'Activo');
INSERT INTO `zonas` VALUES ('20', '4', '2', 'Cali', 'Escuela Carlos A. Sardi', '3', 'Kr 5 # 02-69', 'San Antonio', 'Activo');
INSERT INTO `zonas` VALUES ('21', '4', '3', 'Cali', 'Colegio Santa Librada', '3', 'Cl 7 # 14A-06', 'San Juan Bosco', 'Activo');
INSERT INTO `zonas` VALUES ('22', '4', '4', 'Cali', 'Normal Superior Los Farallone', '3', 'Kr 22 # 2 Oeste-65', 'Libertadores', 'Activo');
INSERT INTO `zonas` VALUES ('23', '4', '5', 'Cali', 'Escuela Republica De Mexico', '3', 'Cl 20 # 5-63', 'San Nicolas', 'Activo');
INSERT INTO `zonas` VALUES ('24', '5', '1', 'Cali', 'Sede Manuela Beltran', '4', 'Carrera 2 No. 34 -23', 'Porvenir', 'Activo');
INSERT INTO `zonas` VALUES ('25', '5', '2', 'Cali', 'Escuela Jose Antonio Galan', '4', 'Cl 41N # 3N-11', 'Popular', 'Activo');
INSERT INTO `zonas` VALUES ('26', '5', '3', 'Cali', 'Escuela Rafael Zamorano', '4', 'Kr 2N # 45An-12', 'Guillermo Valencia', 'Activo');
INSERT INTO `zonas` VALUES ('27', '5', '4', 'Cali', 'Colegio Inem', '4', 'Kr 5N # 61-126', 'Flora Industrial', 'Activo');
INSERT INTO `zonas` VALUES ('28', '5', '5', 'Cali', 'Colegio Casd Sede Santo Tomas', '4', 'Cl 34 # 3N-15', 'Bueno Madrid', 'Activo');
INSERT INTO `zonas` VALUES ('29', '5', '6', 'Cali', 'Sede San Vicente De Paul', '4', 'Carrera 2B No. 45A - 20', 'Salomia', 'Activo');
INSERT INTO `zonas` VALUES ('30', '6', '1', 'Cali', 'SENA', '5', 'Cl 52 2B-15', 'Sena', 'Activo');
INSERT INTO `zonas` VALUES ('31', '6', '2', 'Cali', 'Escuela Celmira Bueno De Oreju', '5', 'Cll 62B No 1A9-250', 'Chiminangos II', 'Activo');
INSERT INTO `zonas` VALUES ('32', '6', '3', 'Cali', 'Escuela Mariano Ospina Perez', '5', 'Cll 67 Cr 2', 'Los Guayacanes', 'Activo');
INSERT INTO `zonas` VALUES ('33', '6', '4', 'Cali', 'Escuela Maria Panesso', '5', 'Cl 46C #3-00', 'Sena', 'Activo');
INSERT INTO `zonas` VALUES ('34', '6', '5', 'Cali', 'Escuela Mario Lloreda', '5', 'Kr 1D # 51-16', 'Sena', 'Activo');
INSERT INTO `zonas` VALUES ('35', '7', '1', 'Cali', 'Polideportivo Los Guaduales', '6', 'Kr 9 N # 71-35', 'Los Guaduales', 'Activo');
INSERT INTO `zonas` VALUES ('36', '7', '2', 'Cali', 'Escuela Las Americas', '6', 'Cl 82A Kr 3N', 'Floralia', 'Activo');
INSERT INTO `zonas` VALUES ('37', '7', '3', 'Cali', 'Escuela Pablo Emilio Caicedo', '6', 'Cra 5 Dg 7N-39', 'Paso Del Comercio', 'Activo');
INSERT INTO `zonas` VALUES ('38', '7', '4', 'Cali', 'Santa Isabel De Hungria Calimi', '6', 'Cll 73A No 1A2-165', 'Calimio Norte', 'Activo');
INSERT INTO `zonas` VALUES ('39', '7', '5', 'Cali', 'Sede Cecilia Muñoz Ricaurte', '6', 'Cl 78N #8N-02', 'Floralia', 'Activo');
INSERT INTO `zonas` VALUES ('40', '8', '1', 'Cali', 'Escuela San Jorge', '6', 'Cr 1I # 74-23', 'Petecuy Iii', 'Activo');
INSERT INTO `zonas` VALUES ('41', '8', '2', 'Cali', 'Escuela San Luis', '6', 'Cl 72 #1 B-03', 'San Luis ', 'Activo');
INSERT INTO `zonas` VALUES ('42', '8', '3', 'Cali', 'Escuela Tres De Julio', '6', 'Cl 70 C #1F-00', 'La Rivera I', 'Activo');
INSERT INTO `zonas` VALUES ('43', '8', '4', 'Cali', 'Escuela Atanasio Girardot', '6', 'Cl 70C No 1A 3-00', 'Alcazarez', 'Activo');
INSERT INTO `zonas` VALUES ('44', '8', '5', 'Cali', 'Unidad Deportiva Petecuy II', '6', 'Cl 74 #1C1-75', 'Petecuy II', 'Activo');
INSERT INTO `zonas` VALUES ('45', '9', '1', 'Cali', 'Colegio Santa Isabel  Hungria Alfons', '7', 'Cr 7H Bis No 76-25', 'Alfonso Lopez I', 'Activo');
INSERT INTO `zonas` VALUES ('46', '9', '2', 'Cali', 'Escuela Los Farallones', '7', 'Kr 7J #72-10', 'Alfonso Lopez II', 'Activo');
INSERT INTO `zonas` VALUES ('47', '9', '3', 'Cali', 'Colegio Vicente Borrero Costa', '7', 'Cll 76 N. Cr 7S', 'Alfonso Lopez I', 'Activo');
INSERT INTO `zonas` VALUES ('48', '9', '4', 'Cali', 'Escuela Manuel Maria Mallarino', '7', 'Cr 9A # 78-14', 'Puerto Mallarino', 'Activo');
INSERT INTO `zonas` VALUES ('49', '9', '5', 'Cali', 'Institucion Educativa Juan Bautista De La Salle', '7', 'Cll 74 No 9-19', 'Andres Sanin', 'Activo');
INSERT INTO `zonas` VALUES ('50', '10', '1', 'Cali', 'Institución Educativa Manuel Maria Mallarino', '7', 'Cr 7L Bis  No 63-01', 'Las Ceibas', 'Activo');
INSERT INTO `zonas` VALUES ('51', '10', '2', 'Cali', 'Escuela Carlos Holguin Sardi', '7', 'Kr 7 D Bis Cl 64', 'San Marino', 'Activo');
INSERT INTO `zonas` VALUES ('52', '10', '3', 'Cali', 'Escuela Ana Maria Vernaza', '7', 'Cl 72B #8B-37', 'Siete De Agosto', 'Activo');
INSERT INTO `zonas` VALUES ('53', '10', '4', 'Cali', 'Institución Educativa Siete De Agosto', '7', 'Cll 72 No 11C-27', 'Siete De Agosto', 'Activo');
INSERT INTO `zonas` VALUES ('54', '11', '1', 'Cali', 'Escuela Manuel Rebolledo', '8', 'Dg 23 #17F-00', 'Rafael Uribe Uribe', 'Activo');
INSERT INTO `zonas` VALUES ('55', '11', '2', 'Cali', 'Instituto Técnico Comercial Las Américas', '8', 'Cr 12 No 38-58', 'Las Americas', 'Activo');
INSERT INTO `zonas` VALUES ('56', '11', '3', 'Cali', 'Colegio Santa Fe', '8', 'Cl 34 #17 B-41', 'Santa Fe', 'Activo');
INSERT INTO `zonas` VALUES ('57', '11', '4', 'Cali', 'Institucion Educativa Benjamin Herrera', '8', 'Cll 26 No 12-34', 'Benjamin Herrera', 'Activo');
INSERT INTO `zonas` VALUES ('58', '12', '1', 'Cali', 'Colegio Leon De Greiff', '8', 'Calle 51 No. 12A-30', 'Villacolombia', 'Activo');
INSERT INTO `zonas` VALUES ('59', '12', '2', 'Cali', 'Institucion Educativa Alberto Carvajal Borrero', '8', 'Cra 14 Nº 58-00', 'El Trebol', 'Activo');
INSERT INTO `zonas` VALUES ('60', '12', '3', 'Cali', 'Colegio San Francisco De Asis', '8', 'Cl 52 Con Cra 15', 'Villacolombia', 'Activo');
INSERT INTO `zonas` VALUES ('61', '13', '1', 'Cali', 'Escuela Marco Fidel Suarez', '9', 'Kr 16 #6-61', 'Alameda', 'Activo');
INSERT INTO `zonas` VALUES ('62', '13', '2', 'Cali', 'Esc Nuestra Señora De Los Reme', '9', 'Cr 17D No 18-46', 'Belalcazar', 'Activo');
INSERT INTO `zonas` VALUES ('63', '13', '3', 'Cali', 'Escuela Republica De Argentina', '9', 'Cr 11D No 23-49', 'Obrero', 'Activo');
INSERT INTO `zonas` VALUES ('64', '13', '4', 'Cali', 'Escuela Olga Lucia Lloreda', '9', 'Kr 23A #13B-11', 'Junin', 'Activo');
INSERT INTO `zonas` VALUES ('65', '13', '5', 'Cali', 'Institucion Educativa Antonio Jose Camacho', '9', 'Cra 16 No 12-00', 'Guayaquil', 'Activo');
INSERT INTO `zonas` VALUES ('66', '13', '6', 'Cali', 'Colegio La Santisima Trinidad', '9', 'Kr 18 #15-41', 'Guayaquil', 'Activo');
INSERT INTO `zonas` VALUES ('67', '14', '1', 'Cali', 'Sd Republica De Costa Rica', '10', 'Cra 40 No 14C-00', 'Guabal', 'Activo');
INSERT INTO `zonas` VALUES ('68', '14', '2', 'Cali', 'Escuela Fernando Velasco', '10', 'Cl 23 #44A-16', 'San Judas', 'Activo');
INSERT INTO `zonas` VALUES ('69', '14', '3', 'Cali', 'Institucion Educativa Normal Superior Santiago', '10', 'Cr 33A No 12-60', 'Colseguros Andes', 'Activo');
INSERT INTO `zonas` VALUES ('70', '14', '4', 'Cali', 'Institucion Educativa Carlos Holguin Lloreda', '10', 'Cr 40 No 18-85', 'El Guabal', 'Activo');
INSERT INTO `zonas` VALUES ('71', '15', '1', 'Cali', 'Centro Parroquial San Juan Bau', '10', 'Cr 42 No 13B-35', 'El Guabal', 'Activo');
INSERT INTO `zonas` VALUES ('72', '15', '2', 'Cali', 'Escuela San Roque', '10', 'Kr 32A #15A-59', 'Cristobal Colon', 'Activo');
INSERT INTO `zonas` VALUES ('73', '15', '3', 'Cali', 'Colegio Rafael Navia Varon', '10', 'Cl 11 # 46-40', 'Departamental', 'Activo');
INSERT INTO `zonas` VALUES ('74', '15', '4', 'Cali', 'Escuela General Carlos Alban', '10', 'Cl 18 A #24 – 65', 'Santa Elena', 'Activo');
INSERT INTO `zonas` VALUES ('75', '16', '1', 'Cali', 'Agustin Nieto Caballero', '11', 'Cr 37 # 26C-51', 'El Jardin', 'Activo');
INSERT INTO `zonas` VALUES ('76', '16', '2', 'Cali', 'Institucion Educativa Ciudad Modelo', '11', 'Cr 40B No 31C-00', 'Ciudad Modelo', 'Activo');
INSERT INTO `zonas` VALUES ('77', '16', '3', 'Cali', 'Escuela  Marino Rengifo Salced', '11', 'Cll 26B No 44-11', 'Villa Del Sur', 'Activo');
INSERT INTO `zonas` VALUES ('78', '16', '4', 'Cali', 'Escuela Susana Vinasco De Quin', '11', 'Cll 31 Cr 33A', 'San Carlos', 'Activo');
INSERT INTO `zonas` VALUES ('79', '17', '1', 'Cali', 'Escuela Jose Vicente Concha', '11', 'Kr 30A #30A-37', 'Fortaleza', 'Activo');
INSERT INTO `zonas` VALUES ('80', '17', '2', 'Cali', 'Escuela Alfredo Vasquez Cobo', '11', 'Dg 23 # Trans 25-25', 'Veinte De Julio', 'Activo');
INSERT INTO `zonas` VALUES ('81', '17', '3', 'Cali', 'Institucion Educativa 10 De Mayo', '11', 'Cr 25A No 26A-13', 'Aguablanca', 'Activo');
INSERT INTO `zonas` VALUES ('82', '17', '4', 'Cali', 'Cdi Madre Alberta Fe Y Alegria', '11', 'Dg 30 #32B-49', 'La Gran Colombia', 'Activo');
INSERT INTO `zonas` VALUES ('83', '18', '1', 'Cali', 'Escuela Ciudad De Cali', '12', 'Cl 46 #28F-31', 'Doce De Octubre', 'Activo');
INSERT INTO `zonas` VALUES ('84', '18', '2', 'Cali', 'Institucion Educativa Marice Sinisterra', '12', 'Cll 39 No 25 A -43', 'El Rodeo', 'Activo');
INSERT INTO `zonas` VALUES ('85', '18', '3', 'Cali', 'Colegio Hernando Navia Varon', '12', 'Kr 26P #50-39', 'Nueva Floresta', 'Activo');
INSERT INTO `zonas` VALUES ('86', '18', '4', 'Cali', 'Escuela Fenalco Asturias', '12', 'Cl 44 #25A-12', 'Fenalco Kenedy', 'Activo');
INSERT INTO `zonas` VALUES ('87', '18', '5', 'Cali', 'Institucion Educativa Julio de Caicedo y Tellez', '12', 'Cll 59 No 24E-40', 'Nueva Floresta', 'Activo');
INSERT INTO `zonas` VALUES ('88', '18', '6', 'Cali', 'Colegio La Presentacion El Par', '12', 'Cr 28 B # 33E-39', 'El Paraiso', 'Activo');
INSERT INTO `zonas` VALUES ('89', '19', '1', 'Cali', 'Colegio Parroquial Señor De Los Milagros', '13', 'Cr 33 No 42C-11', 'El Vergel', 'Activo');
INSERT INTO `zonas` VALUES ('90', '19', '2', 'Cali', 'Escuela El Diamante', '13', 'Kr 31 #41-00', 'El Diamante', 'Activo');
INSERT INTO `zonas` VALUES ('91', '19', '3', 'Cali', 'Escuela Rodrigo Lloreda', '13', 'Kr 30 #44A-21', 'Poblado I', 'Activo');
INSERT INTO `zonas` VALUES ('92', '19', '4', 'Cali', 'Escuela Miguel Camacho Perea', '13', 'Cr 28 No 72F-09', 'Comuneros Ii', 'Activo');
INSERT INTO `zonas` VALUES ('93', '19', '5', 'Cali', 'Centro de Capacitacion Don Bos', '13', 'Cr 31 No 39-42', 'El Diamante', 'Activo');
INSERT INTO `zonas` VALUES ('94', '19', '6', 'Cali', 'Colegio Comfandi Calipso', '13', 'Cll. 70 # 28 D3 - 129', 'Calipso', 'Activo');
INSERT INTO `zonas` VALUES ('95', '20', '1', 'Cali', 'Escuela Santa Rosa', '13', 'Cl 72X No 28 3-35', 'Poblado Ii', 'Activo');
INSERT INTO `zonas` VALUES ('96', '20', '2', 'Cali', 'Colegio Los Lagos', '13', 'Cl 72U #26-H3-15', 'Los Lagos', 'Activo');
INSERT INTO `zonas` VALUES ('97', '20', '3', 'Cali', 'Escuela Bartolome Loboguerrero', '13', 'Cll 71 No 26E-25', 'Lleras Restrepo', 'Activo');
INSERT INTO `zonas` VALUES ('98', '20', '4', 'Cali', 'Sena Cdti', '13', 'Calle 72 K  26J-97', 'Villa Del Lago', 'Activo');
INSERT INTO `zonas` VALUES ('99', '20', '5', 'Cali', 'Colegio Jesus Villafañe Franco', '13', 'Cll 72 P Con Cr 26H2 Es', 'Marroquin Iii', 'Activo');
INSERT INTO `zonas` VALUES ('100', '20', '6', 'Cali', 'Colegio Enrique Olaya Herrera', '13', 'Cl 71 #25 A-15', 'Ulpiano Lloreda', 'Activo');
INSERT INTO `zonas` VALUES ('101', '21', '1', 'Cali', 'Escuela La Anunciacion', '14', 'Kr 26A #74-00', 'Alirio Mora Beltran', 'Activo');
INSERT INTO `zonas` VALUES ('102', '21', '2', 'Cali', 'Institucion Educativa Puerta D', '14', 'Cll 84 No 26B- 04', 'Puerta Del Sol I', 'Activo');
INSERT INTO `zonas` VALUES ('103', '21', '3', 'Cali', 'Escuela Raul Silva Holguin', '14', 'Dg 26K #T83-24', 'Marroquin Ii', 'Activo');
INSERT INTO `zonas` VALUES ('104', '21', '4', 'Cali', 'Institucion Educativa Monseñor Ramon Arcila', '14', 'Dg 26I3 No 80A-18', 'Marroquin Ii', 'Activo');
INSERT INTO `zonas` VALUES ('105', '22', '1', 'Cali', 'Colegio Fray Luis Amigo - Fe y Alegria', '14', 'Cll 116 No 26K1-15', 'Manuela Beltran', 'Activo');
INSERT INTO `zonas` VALUES ('106', '22', '2', 'Cali', 'Escuela Gabriela Mistral', '14', 'Kr 27D N° 95-06', 'Alfonso Bonilla Aragon', 'Activo');
INSERT INTO `zonas` VALUES ('107', '22', '3', 'Cali', 'Colegio Parroquial San Francisco J', '14', 'Cr 27 D No 107 B-16', 'Las Orquideas', 'Activo');
INSERT INTO `zonas` VALUES ('108', '22', '4', 'Cali', 'Institucion Educativa Elias Salazar Garcia', '14', 'Cll 108 No 26H1-11', 'Manuela Beltran', 'Activo');
INSERT INTO `zonas` VALUES ('109', '22', '5', 'Cali', 'Institucion Educativa Nuevo Latir', '14', 'Cll 76 No 28-20', 'Alfonso Bonilla Aragon', 'Activo');
INSERT INTO `zonas` VALUES ('110', '23', '1', 'Cali', 'Colegio Ciudad de Cordoba', '15', 'Cl 50 #50-00', 'Ciudad Cordoba', 'Activo');
INSERT INTO `zonas` VALUES ('111', '23', '2', 'Cali', 'Escuela Olaya Herrera', '15', 'Cl 51 #40A-08', 'El Vallado', 'Activo');
INSERT INTO `zonas` VALUES ('112', '23', '3', 'Cali', 'Escuela Jose Ramon Bejarano', '15', 'Kr 32A #49-00', 'Laureano Gomez', 'Activo');
INSERT INTO `zonas` VALUES ('113', '23', '4', 'Cali', 'Institucion Educativa Gabriel Garcia Marquez', '15', 'Cr 29B No 54-00', 'Comuneros I', 'Activo');
INSERT INTO `zonas` VALUES ('114', '23', '5', 'Cali', 'Escuela Niño Jesus De Atocha', '15', 'Cll 83 No 28 E3-05', 'Mojica', 'Activo');
INSERT INTO `zonas` VALUES ('115', '23', '6', 'Cali', 'Institucion Educativa Carlos Holguin Mallarino', '15', 'Cll 55A No 30B-50', 'Comuneros I', 'Activo');
INSERT INTO `zonas` VALUES ('116', '23', '7', 'Cali', 'Colegio Madre Siffredi-Fe Y Al', '15', 'Cr 39 E No 54-02', 'El Vallado', 'Activo');
INSERT INTO `zonas` VALUES ('117', '23', '8', 'Cali', 'Institucion Educativa Llano Verde', '15', 'Cra 47 Cll 57 Esq.', 'Llano Verde', 'Activo');
INSERT INTO `zonas` VALUES ('118', '24', '1', 'Cali', 'Institucion Educativa Rodrigo Lloreda- Intenalco', '16', 'Cll 38 A No 47A-45', 'Mariano Ramos', 'Activo');
INSERT INTO `zonas` VALUES ('119', '24', '2', 'Cali', 'Escuela Cristobal Colon', '16', 'Cl 44 #47A-16', 'Mariano Ramos', 'Activo');
INSERT INTO `zonas` VALUES ('120', '24', '3', 'Cali', 'Escuela Carlos Holmes Trujillo', '16', 'Cll 44 Cr 43', 'Republica De Israel', 'Activo');
INSERT INTO `zonas` VALUES ('121', '24', '4', 'Cali', 'Escuela Pablo Neruda', '16', 'Kr 39B #38-44', 'Antonio Nariño', 'Activo');
INSERT INTO `zonas` VALUES ('122', '24', '5', 'Cali', 'Sede Cristo Maestro', '16', 'Cl 44 Cra 41F', 'Union De Vivi Popular', 'Activo');
INSERT INTO `zonas` VALUES ('123', '24', '6', 'Cali', 'Santa Isabel De Hungria S. Ciu', '16', 'Cr 54 No 41-47', 'Ciudad 2000', 'Activo');
INSERT INTO `zonas` VALUES ('124', '24', '7', 'Cali', 'Institucion Educativa Libardo Madrid Valderrama', '16', 'Cra 41H # 39-73', 'Union De Vivi Popular', 'Activo');
INSERT INTO `zonas` VALUES ('125', '25', '1', 'Cali', 'Colegio Los Andes', '17', 'Cra 70 #12-55', 'Los Portales - Nuevo Rey', 'Activo');
INSERT INTO `zonas` VALUES ('126', '25', '2', 'Cali', 'Colegio Comfandi', '17', 'Calle 34  No. 83B-50', 'Ciudadela Comfandi', 'Activo');
INSERT INTO `zonas` VALUES ('127', '25', '3', 'Cali', 'Colegio Reyes Catolicos', '17', 'Cl 13 C No 70-72', 'Los Portales - Nuevo Rey', 'Activo');
INSERT INTO `zonas` VALUES ('128', '25', '4', 'Cali', 'Univalle Sede Melendez', '17', 'Calle 13 No. 100 - 00', 'Ciudad Universitaria', 'Activo');
INSERT INTO `zonas` VALUES ('129', '26', '1', 'Cali', 'Escuela Luis Carlos Rojas', '17', 'Cra 56 #13F-40', 'Primero De Mayo', 'Activo');
INSERT INTO `zonas` VALUES ('130', '26', '2', 'Cali', 'C.V.C', '17', 'Kr 56 #11-36', 'Santa Anita', 'Activo');
INSERT INTO `zonas` VALUES ('131', '26', '3', 'Cali', 'Centro Cultural Colombo Americ', '17', 'Cr 53 No 11 A -00', 'Santa Anita', 'Activo');
INSERT INTO `zonas` VALUES ('132', '26', '4', 'Cali', 'I.E.T.I.Comuna 17', '17', 'Cra 53 No. 18A-25', 'Cañaverales', 'Activo');
INSERT INTO `zonas` VALUES ('133', '27', '1', 'Cali', 'Escuela Alvaro Escobar Navia', '18', 'Cra 73D #1B-65', 'Lourdes', 'Activo');
INSERT INTO `zonas` VALUES ('134', '27', '2', 'Cali', 'Instituto Central De Comercio', '18', 'Carrera 73 No. 3C -65', 'Buenos Aires', 'Activo');
INSERT INTO `zonas` VALUES ('135', '27', '3', 'Cali', 'Escuela Juan Pablo II', '18', 'Cl 1 Oeste #78-23', 'Prados Del Sur', 'Activo');
INSERT INTO `zonas` VALUES ('136', '27', '4', 'Cali', 'Escuela Portete De Tarqui', '18', 'Cl 1 #73-00', 'Lourdes', 'Activo');
INSERT INTO `zonas` VALUES ('137', '27', '5', 'Cali', 'Universidad Cooperativa', '18', 'Kr 73 #2A-80', 'Buenos Aires', 'Activo');
INSERT INTO `zonas` VALUES ('138', '28', '1', 'Cali', 'Escuela La Esperanza', '18', 'Kr 94 #1A-71 Oeste', 'Alto Jordan', 'Activo');
INSERT INTO `zonas` VALUES ('139', '28', '2', 'Cali', 'Colegio Americano', '18', 'Cr 89 # 4 C-35', 'Melendez', 'Activo');
INSERT INTO `zonas` VALUES ('140', '28', '3', 'Cali', 'Escuela Magdalena Ortega De Nariño', '18', 'Cll 4 Oeste No 94-56', 'Polvorines', 'Activo');
INSERT INTO `zonas` VALUES ('141', '28', '4', 'Cali', 'Academia Jose Maria Cabal', '18', 'Calle 2C Oeste No. 83 - 20', 'La Academia', 'Activo');
INSERT INTO `zonas` VALUES ('142', '29', '1', 'Cali', 'Institucion Educativa  Eustaquio Palacios', '19', 'Cr 52 No 2-51', 'Lido', 'Activo');
INSERT INTO `zonas` VALUES ('143', '29', '2', 'Cali', 'Instituto Para Niños Ciegos Y', '19', 'Cr 38 # 5 B1-39', 'San Fernando Nuevo', 'Activo');
INSERT INTO `zonas` VALUES ('144', '29', '3', 'Cali', 'Colegio Santa Maria Stella Maris', '19', 'Cra 38B No 3-190', 'Santa Isabel', 'Activo');
INSERT INTO `zonas` VALUES ('145', '29', '4', 'Cali', 'Escuela 25 De Julio', '19', 'Cl 6 #59 A-51', 'Joaquin Borrero', 'Activo');
INSERT INTO `zonas` VALUES ('146', '29', '5', 'Cali', 'Colegio Politecnico', '19', 'Cra 62 #2-28', 'Pampalinda', 'Activo');
INSERT INTO `zonas` VALUES ('147', '30', '1', 'Cali', 'Univalle Sede San Fernando', '19', 'Cl 4B #36-00', 'San Fernando', 'Activo');
INSERT INTO `zonas` VALUES ('148', '30', '2', 'Cali', 'Unidad Deportiva Panamerica Cancha De Tejo', '19', 'Cl 9 Con Cra 38', 'Unidad Panamericana', 'Activo');
INSERT INTO `zonas` VALUES ('149', '30', '3', 'Cali', 'Liceo Departamental Femenino', '19', 'Cra 37A #8-38', 'Departamental', 'Activo');
INSERT INTO `zonas` VALUES ('150', '30', '4', 'Cali', 'Escuela Francisco Jose De Cald', '19', 'Cr 4 Oeste # 12A-59', 'Bellavista', 'Activo');
INSERT INTO `zonas` VALUES ('151', '30', '5', 'Cali', 'Escuela Camilo Torres', '19', 'Kr 24 #10A-98', 'Colseguros', 'Activo');
INSERT INTO `zonas` VALUES ('152', '30', '6', 'Cali', 'Escuela Gran Colombia', '19', 'Kr 24 #7-74', 'El Cedro', 'Activo');
INSERT INTO `zonas` VALUES ('153', '30', '7', 'Cali', 'Sede Los Cristales', '19', 'Cl 11 Oeste #24C-50', 'El Mortiñal', 'Activo');
INSERT INTO `zonas` VALUES ('154', '31', '1', 'Cali', 'Escuela Republica De Panama', '20', 'Dg 48 #12-06 Oeste', 'Brisas De Mayo', 'Activo');
INSERT INTO `zonas` VALUES ('155', '31', '2', 'Cali', 'Escuela Luis Lopez De Mesa', '20', 'Cl 3 Oeste #42 B-44', 'Siloe', 'Activo');
INSERT INTO `zonas` VALUES ('156', '', '3', 'Cali', 'Institucion Educativa Juana de Caicedo y Cuero', '20', 'Cll 1 Oeste # 50-85', 'Belisario Caicedo', 'Activo');
INSERT INTO `zonas` VALUES ('157', '31', '4', 'Cali', 'Sede Simon Bolivar', '20', 'Cll 1 Oeste No  42A-94', 'Siloe', 'Activo');
INSERT INTO `zonas` VALUES ('158', '31', '5', 'Cali', 'Escuela Celanese', '20', 'Cl 5 Oeste #38 B-28', 'Belen', 'Activo');
INSERT INTO `zonas` VALUES ('159', '31', '6', 'Cali', 'Escuela Sofia Camargo', '20', 'Kr 51 Cl 12 A Oeste', 'Lleras Camargo', 'Activo');
INSERT INTO `zonas` VALUES ('160', '32', '1', 'Cali', 'Escuela Antonio Maceo Pizamos', '21', 'Cr 28G # 122 D- 00', 'Pizamos 1', 'Activo');
INSERT INTO `zonas` VALUES ('161', '32', '2', 'Cali', 'Santa Isabel de Hungria Calimio', '21', 'Cll 123 # 26 M3-01', 'Calimio Dezepaz', 'Activo');
INSERT INTO `zonas` VALUES ('162', '32', '3', 'Cali', 'Santa Isabel De Hungria San Fe', '21', 'Cll 119 Con Cr 23 Esquina', 'Dezepaz', 'Activo');
INSERT INTO `zonas` VALUES ('163', '32', '4', 'Cali', 'Colegio Fundacion Compartir', '21', 'Kr-25A #89 A-16', 'Compartir', 'Activo');
INSERT INTO `zonas` VALUES ('164', '32', '5', 'Cali', 'Fundacion Colegio Nariño', '21', 'Kr 24 F #82-90', 'Vallegrande', 'Activo');
INSERT INTO `zonas` VALUES ('165', '32', '6', 'Cali', 'Colegio Potrerogrande', '21', 'Cra 28D Entre Cls 123 Y 124', 'Potrerogrande', 'Activo');
INSERT INTO `zonas` VALUES ('166', '33', '1', 'Cali', 'Colegio Benett', '22', 'Kr106 Cl17 Esq. Avenida Cascajal', 'Ciudad Jardin', 'Activo');
INSERT INTO `zonas` VALUES ('167', '33', '2', 'Cali', 'Universidad de San Buenaventura', '22', 'Av 10 De Mayo La Umbria', 'Ciudad Jardin', 'Activo');
INSERT INTO `zonas` VALUES ('168', '33', '3', 'Cali', 'Universidad Icesi', '22', 'Call. 18 # 122 - 135 Pance', 'Ciudad Jardin', 'Activo');
INSERT INTO `zonas` VALUES ('169', '90', '1', 'Cali', 'Coliseo Del Pueblo', '19', 'Cra 52 Entre Calles 2 Y 3', 'Coliseo del Pueblo', 'Activo');
INSERT INTO `zonas` VALUES ('170', '98', '1', 'Cali', 'Carcel Villanueva', '12', 'Trans 25 #31-116', 'Villanueva ', 'Activo');
INSERT INTO `zonas` VALUES ('171', '99', '99', 'Cali', 'Felidia', null, 'I.E.Felidia-Sede Rep.De Cuba', '23 Felidia', 'Activo');
INSERT INTO `zonas` VALUES ('172', '99', '17', 'Cali', 'La Buitrera', null, 'I.E. Maria Garcia Toledo-Km 3', '25 La Buitrera', 'Activo');
INSERT INTO `zonas` VALUES ('173', '99', '19', 'Cali', 'La Sirena', null, 'I.E. Santa Luisa', '25 La Buitrera', 'Activo');
INSERT INTO `zonas` VALUES ('174', '99', '21', 'Cali', 'La Castilla', null, 'I.E Sagrado Corazon De Jesus', '26 La Castilla', 'Activo');
INSERT INTO `zonas` VALUES ('175', '99', '25', 'Cali', 'La Elvira', null, 'Escuela Boyaca', '27 La Elvira', 'Activo');
INSERT INTO `zonas` VALUES ('176', '99', '29', 'Cali', 'La Leonera', null, 'Sede Cultural La Leonera', '28 La Leonera', 'Activo');
INSERT INTO `zonas` VALUES ('177', '99', '33', 'Cali', 'La Paz', null, 'I.E Saavedra Galindo La Paz', '29 La Paz', 'Activo');
INSERT INTO `zonas` VALUES ('178', '99', '37', 'Cali', 'Golondrinas', null, 'I.E Golondrinas', '30 Golondrinas', 'Activo');
INSERT INTO `zonas` VALUES ('179', '99', '41', 'Cali', 'Los Andes', null, 'Vda Ventiaderos-Esc. Tierra De H', '31 Los Andes', 'Activo');
INSERT INTO `zonas` VALUES ('180', '99', '45', 'Cali', 'Villa Carmelo', null, 'I.E.Cacique Calarca-Vda La Fonda', '32 (Villa Carmelo)', 'Activo');
INSERT INTO `zonas` VALUES ('181', '99', '47', 'Cali', 'Montebello', null, 'Ie Montebello Antonio Ricaurte', '33 Montebello', 'Activo');
INSERT INTO `zonas` VALUES ('182', '99', '49', 'Cali', 'Navarro', null, 'I.E. Juan Bautista De La Salle', '34 Navarro', 'Activo');
INSERT INTO `zonas` VALUES ('183', '99', '53', 'Cali', 'Pance', null, 'I.E. Pance-Vda La Voragine', '35 Pance', 'Activo');
INSERT INTO `zonas` VALUES ('184', '99', '57', 'Cali', 'Pichinde', null, 'I.E. Pichinde Sede Holguin Garces', '36 Pichinde', 'Activo');
INSERT INTO `zonas` VALUES ('185', '99', '65', 'Cali', 'El Saladito', null, 'Esc. Luis Fernando Lloreda', '37 El Saladito', 'Activo');
INSERT INTO `zonas` VALUES ('186', '99', 'A1', 'Cali', 'Hormiguero A', null, 'I.E. Antonio Villavicencio', '24 Hormiguero', 'Activo');
