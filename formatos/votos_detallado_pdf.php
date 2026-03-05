<?php
date_default_timezone_set('America/Bogota');
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Restricción de acceso dinámica
require_once('../funcs/permisos_helper.php');
if (!tienePermiso($_SESSION['tipo_usuario'], 'Resultados', 'ver')) {
    header("Location: ../index.php?url=dashboard/index");
    exit;
}

require_once('../funcs/class.conexion.php');

$id_candidato = isset($_GET['id_candidato']) ? $_GET['id_candidato'] : '';
$aspirante    = isset($_GET['aspirante'])    ? $_GET['aspirante']    : 'ALCALDIA';
$dpto         = isset($_GET['dpto'])         ? $_GET['dpto']         : '';
$muni         = isset($_GET['muni'])         ? $_GET['muni']         : '';

if (empty($id_candidato)) die('Parámetros incompletos.');

$conexionObj = new Conexion();
$db = $conexionObj->get_conexion();

// Nombre candidato
$nomCandidato = $id_candidato;
if ($id_candidato === 'EN_BLANCO')       { $nomCandidato = 'Votos en Blanco'; }
elseif ($id_candidato === 'NULOS')       { $nomCandidato = 'Votos Nulos'; }
elseif ($id_candidato === 'NO_MARCADOS') { $nomCandidato = 'Tarjetones No Marcados'; }
else {
    $sc = $db->prepare("SELECT nom_candidato, nom_partido FROM candidatos WHERE id_candidato = :id LIMIT 1");
    $sc->bindValue(':id', $id_candidato);
    $sc->execute();
    $cRow = $sc->fetch(PDO::FETCH_ASSOC);
    if ($cRow) $nomCandidato = $cRow['nom_candidato'] . ' — ' . $cRow['nom_partido'];
}

// Consulta detallada
$sql = "SELECT r.zona, r.puesto, r.mesa, r.votos, r.dpto, r.muni,
               z.nom_puesto
        FROM registro_votos r
        LEFT JOIN zonas z ON z.num_zona  = r.zona
                         AND z.pues_zona = r.puesto
                         AND z.dpto_zona = r.dpto
                         AND z.mun_zona  = r.muni
        WHERE r.id_candidato = :id_candidato AND r.aspirante = :aspirante";
$params = array(':id_candidato' => $id_candidato, ':aspirante' => $aspirante);
if (!empty($dpto)) { $sql .= " AND r.dpto = :dpto"; $params[':dpto'] = $dpto; }
if (!empty($muni)) { $sql .= " AND r.muni = :muni"; $params[':muni'] = $muni; }
$sql .= " ORDER BY r.dpto ASC, r.muni ASC, r.zona ASC, r.puesto ASC, r.mesa ASC";

$stmt = $db->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->execute();
$filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por zona/puesto
$grupos = array();
$totalGeneral = 0;
foreach ($filas as $row) {
    $gKey = $row['dpto'] . '|' . $row['muni'] . '|' . $row['zona'] . '|' . $row['puesto'];
    if (!isset($grupos[$gKey])) {
        $grupos[$gKey] = array(
            'dpto'       => $row['dpto'),
            'muni'       => $row['muni'],
            'zona'       => $row['zona'],
            'puesto'     => $row['puesto'],
            'nom_puesto' => $row['nom_puesto'],
            'mesas'      => [],
            'subtotal'   => 0,
        ];
    }
    $grupos[$gKey]['mesas'][] = array('mesa' => $row['mesa'), 'votos' => intval($row['votos'])];
    $grupos[$gKey]['subtotal'] += intval($row['votos']);
    $totalGeneral += intval($row['votos']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle de Votos — <?php echo htmlspecialchars($nomCandidato); ?></title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', Arial, sans-serif; font-size: 11px; color: #222; background: #fff; }

  .page-header {
    background: linear-gradient(135deg, #1a3c5e 0%, #2e6da4 100%);
    color: #fff; padding: 18px 30px; margin-bottom: 16px;
  }
  .page-header h1 { font-size: 17px; font-weight: 700; }
  .page-header p  { font-size: 10px; opacity: 0.85; margin-top: 4px; }

  .total-banner {
    margin: 0 30px 18px; background: #1a3c5e; color: #fff;
    padding: 10px 20px; border-radius: 8px;
    display: flex; justify-content: space-between; align-items: center;
  }
  .total-banner .label { font-size: 12px; opacity: 0.8; }
  .total-banner .value { font-size: 22px; font-weight: 700; }

  .grupo { margin: 0 30px 14px; break-inside: avoid; }
  .grupo-header {
    background: #2e6da4; color: #fff;
    padding: 7px 12px; border-radius: 6px 6px 0 0;
    display: flex; justify-content: space-between; align-items: center;
  }
  .grupo-header .info  { font-size: 11px; font-weight: 600; }
  .grupo-header .sub   { font-size: 12px; font-weight: 700; background: #1a3c5e;
                         padding: 2px 10px; border-radius: 20px; }

  .mesas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
    gap: 6px; padding: 10px 12px;
    background: #f8fafc; border: 1px solid #2e6da4; border-top: none;
    border-radius: 0 0 6px 6px;
  }
  .mesa-card {
    text-align: center; padding: 8px 4px;
    background: #fff; border: 1px solid #dde3ea; border-radius: 6px;
  }
  .mesa-card .m-label { font-size: 9px; color: #888; font-weight: 600; text-transform: uppercase; }
  .mesa-card .m-value { font-size: 16px; font-weight: 700; color: #1a3c5e; margin-top: 2px; }

  .footer {
    text-align: center; font-size: 9px; color: #888;
    border-top: 1px solid #dde3ea; padding: 10px 30px; margin-top: 10px;
  }

  @media print {
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .no-print { display: none; }
    .grupo { page-break-inside: avoid; }
  }
</style>
</head>
<body>

<div class="no-print" style="padding:10px 30px; background:#f8f9fa; border-bottom:1px solid #dee2e6; display:flex; gap:10px; align-items:center;">
  <button onclick="window.print()" style="background:#1a3c5e;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:13px;cursor:pointer;">
    🖨️ Imprimir / Guardar PDF
  </button>
  <button onclick="window.close()" style="background:#6c757d;color:#fff;border:none;padding:8px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
    ✕ Cerrar
  </button>
  <span style="color:#666;font-size:12px;">Usa Ctrl+P o el botón de arriba para guardar como PDF</span>
</div>

<div class="page-header">
  <h1>📋 Detalle de Votos por Mesa — <?php echo htmlspecialchars($nomCandidato); ?></h1>
  <p>
    Aspirante: <?php echo htmlspecialchars(strtoupper($aspirante)); ?>
    <?php if ($dpto) echo " &nbsp;|&nbsp; Departamento: " . htmlspecialchars($dpto); ?>
    <?php if ($muni) echo " &nbsp;|&nbsp; Municipio: " . htmlspecialchars($muni); ?>
    &nbsp;|&nbsp; Generado el: <?php echo date('d/m/Y H:i:s'); ?>
  </p>
</div>

<div class="total-banner">
  <div>
    <div class="label">TOTAL GENERAL DE VOTOS</div>
    <div class="value"><?php echo number_format($totalGeneral); ?></div>
  </div>
  <div style="opacity:0.6; font-size:11px;">
    <?php echo count($grupos); ?> zona(s) / puesto(s) registrados
  </div>
</div>

<?php if (empty($grupos)): ?>
<div style="margin:30px; text-align:center; color:#888; padding:40px 0;">
  <p style="font-size:16px;">No hay votos registrados para este candidato con los filtros seleccionados.</p>
</div>
<?php else: ?>
  <?php foreach ($grupos as $g): ?>
  <div class="grupo">
    <div class="grupo-header">
      <div class="info">
        📍 <?php echo htmlspecialchars($g['dpto']); ?> / <?php echo htmlspecialchars($g['muni']); ?>
        &nbsp;&nbsp; Zona <?php echo htmlspecialchars($g['zona']); ?> — Puesto <?php echo htmlspecialchars($g['puesto']); ?>
        <?php if ($g['nom_puesto']): ?>
        &nbsp;·&nbsp; <?php echo htmlspecialchars($g['nom_puesto']); ?>
        <?php endif; ?>
      </div>
      <div class="sub">Subtotal: <?php echo number_format($g['subtotal']); ?> votos</div>
    </div>
    <div class="mesas-grid">
      <?php foreach ($g['mesas'] as $m): ?>
      <div class="mesa-card">
        <div class="m-label">Mesa <?php echo str_pad($m['mesa'], 2, '0', STR_PAD_LEFT); ?></div>
        <div class="m-value"><?php echo number_format($m['votos']); ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<div class="footer">
  Sistema de Registro de Votos &nbsp;·&nbsp; <?php echo date('Y'); ?> &nbsp;·&nbsp;
  Documento generado el <?php echo date('d/m/Y \a \l\a\s H:i:s'); ?> &nbsp;·&nbsp; Uso interno
</div>

</body>
</html>
