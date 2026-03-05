<?php
function getDashboardStats($conexion, $tipo_usuario = null, $cedula = null, $puede_ver_todos = false) {
    $stats = array(
        'usuarios' => 0,
        'lideres' => 0,
        'zonas' => 0,
        'votantes' => 0,
        'simpatizantes' => 0
    );

    try {
        // Usuarios
        $sql = "SELECT count(id) as cantidad FROM usuarios";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['usuarios'] = $row['cantidad'];

        // Lideres
        $sql = "SELECT count(id_lider) as cantidad FROM lideres";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['lideres'] = $row['cantidad'];

        // Zonas
        $sql = "SELECT count(id_zona) as cantidad FROM zonas";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['zonas'] = $row['cantidad'];

        // Votantes - Filtered by leader if applicable and doesn't have ver_todos
        $sql = "SELECT count(id_votante) as cantidad FROM votantes";
        if ($tipo_usuario == 3 && !$puede_ver_todos) {
            $sql .= " WHERE ced_lider = :cedula";
        }
        $stmt = $conexion->prepare($sql);
        if ($tipo_usuario == 3 && !$puede_ver_todos) { $stmt->bindValue(':cedula', $cedula); }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['votantes'] = $row['cantidad'];

        // Simpatizantes
        $sql = "SELECT count(id_simpatizante) as cantidad FROM simpatizante";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['simpatizantes'] = $row['cantidad'];

        // --- CHART DATA ---

        // Votantes por Zona
        $sqlZona = "SELECT zona_votante as label, COUNT(*) as value FROM votantes ";
        if ($tipo_usuario == 3 && !$puede_ver_todos) {
            $sqlZona .= " WHERE ced_lider = :cedula ";
        }
        $sqlZona .= " GROUP BY zona_votante ORDER BY value DESC LIMIT 10";
        $stmtZona = $conexion->prepare($sqlZona);
        if ($tipo_usuario == 3 && !$puede_ver_todos) { $stmtZona->bindValue(':cedula', $cedula); }
        $stmtZona->execute();
        $stats['chart_zonas'] = $stmtZona->fetchAll(PDO::FETCH_ASSOC);

        // Votantes por Líder
        $sqlLider = "SELECT b.nom_lider as label, COUNT(a.id_votante) as value 
                     FROM votantes a 
                     INNER JOIN lideres b ON a.ced_lider = b.ced_lider ";
        if ($tipo_usuario == 3 && !$puede_ver_todos) {
            $sqlLider .= " WHERE a.ced_lider = :cedula ";
        }
        $sqlLider .= " GROUP BY b.nom_lider ORDER BY value DESC LIMIT 10";
        $stmtLider = $conexion->prepare($sqlLider);
        if ($tipo_usuario == 3 && !$puede_ver_todos) { $stmtLider->bindValue(':cedula', $cedula); }
        $stmtLider->execute();
        $stats['chart_lideres'] = $stmtLider->fetchAll(PDO::FETCH_ASSOC);

        // Simpatizantes por Sexo
        $sqlSexo = "SELECT sexo_simpatizante as label, COUNT(*) as value FROM simpatizante GROUP BY sexo_simpatizante";
        $stmtSexo = $conexion->prepare($sqlSexo);
        $stmtSexo->execute();
        $stats['chart_sexo'] = $stmtSexo->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Manejo silencioso o log de error
    }

    return $stats;
}
?>
