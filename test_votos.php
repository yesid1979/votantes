<?php
require 'funcs/class.conexion.php';
$c = new Conexion();
$db = $c->get_conexion();
$tables = [];

echo "Creating table registro_votos...\n";
$sql = "CREATE TABLE IF NOT EXISTS registro_votos (
    id_voto INT AUTO_INCREMENT PRIMARY KEY,
    dpto VARCHAR(100),
    muni VARCHAR(100),
    zona VARCHAR(50),
    puesto VARCHAR(50),
    mesa INT,
    aspirante VARCHAR(100),
    id_candidato VARCHAR(100), 
    votos INT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT
)";
$db->exec($sql);
echo "Table created.\n";

?>
