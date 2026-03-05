<?php
class Conexion 
{
	public function get_conexion()
	{
      // CONFIGURACIÓN PARA TU NAVICAT / SERVIDOR LOCAL
      $servidor = "localhost";
      $db = "votantes";
      $user = "root";  // Usuario por defecto de XAMPP
      $pass = "root";      // Contraseña por defecto de XAMPP (vacía)
      
      try
      {
        $conexion = new PDO(
            "mysql:host=$servidor;dbname=$db",
            $user,
            $pass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\'')
        );
        // Habilitar excepciones para ver errores de SQL si ocurren
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
      }
      catch(PDOException $e)
      {
        die("Error de conexión local: " . $e->getMessage());
      }
	}
}
?>