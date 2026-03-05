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

$aspirante = isset($_GET['aspirante']) ? $_GET['aspirante'] : 'ALCALDIA';
$dpto      = isset($_GET['dpto'])      ? $_GET['dpto']      : '';
$muni      = isset($_GET['muni'])      ? $_GET['muni']      : '';

$conexionObj = new Conexion();
$db = $conexionObj->get_conexion();

$sql = "SELECT r.id_candidato, SUM(r.votos) AS total_votos,
               c.nom_candidato, c.nom_partido
        FROM registro_votos r
        LEFT JOIN candidatos c ON c.id_candidato = r.id_candidato
        WHERE r.aspirante = :aspirante";
$params = [':aspirante' => $aspirante];
if (!empty($dpto)) { $sql .= " AND r.dpto = :dpto"; $params[':dpto'] = $dpto; }
if (!empty($muni)) { $sql .= " AND r.muni = :muni"; $params[':muni'] = $muni; }
$sql .= " GROUP BY r.id_candidato ORDER BY total_votos DESC";

$stmt = $db->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalGeneral = 0;
$totalValidos = 0;
foreach ($datos as $d) {
    $v = intval($d['total_votos']);
    $totalGeneral += $v;
    if (!in_array($d['id_candidato'], ['NULOS', 'NO_MARCADOS'])) $totalValidos += $v;
}

$titulo = "Resultados Consolidados — " . strtoupper($aspirante);
$subtitulo = '';
if ($dpto) $subtitulo .= "Departamento: $dpto";
if ($muni) $subtitulo .= ($subtitulo ? ' | ' : '') . "Municipio: $muni";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($titulo); ?></title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', Arial, sans-serif; font-size: 12px; color: #222; background: #fff; }

  .page-header {
    background: linear-gradient(135deg, #1a3c5e 0%, #2e6da4 100%);
    color: #fff; padding: 20px 30px; margin-bottom: 20px;
  }
  .page-header h1 { font-size: 20px; font-weight: 700; }
  .page-header p  { font-size: 11px; opacity: 0.85; margin-top: 4px; }

  .stats-row { display: flex; gap: 16px; margin: 0 30px 20px; }
  .stat-box {
    flex: 1; text-align: center; padding: 14px;
    border-radius: 8px; border: 2px solid;
  }
  .stat-box.total  { border-color: #1a3c5e; background: #eaf0f8; }
  .stat-box.validos{ border-color: #198754; background: #d1e7dd; }
  .stat-box .label { font-size: 11px; color: #555; font-weight: 600; text-transform: uppercase; }
  .stat-box .value { font-size: 26px; font-weight: 700; margin-top: 4px; }
  .stat-box.total  .value { color: #1a3c5e; }
  .stat-box.validos .value { color: #198754; }

  table { width: calc(100% - 60px); margin: 0 30px 30px; border-collapse: collapse; }
  thead th {
    background: #1a3c5e; color: #fff; padding: 10px 8px;
    text-align: center; font-size: 11px; font-weight: 600;
    letter-spacing: 0.5px; text-transform: uppercase;
  }
  tbody tr:first-child td { background: #fff9c4 !important; font-weight: 700; }
  tbody tr:nth-child(even) td { background: #f0f4f8; }
  tbody tr:nth-child(odd)  td { background: #ffffff; }
  tbody td {
    padding: 9px 8px; border-bottom: 1px solid #dde3ea;
    vertical-align: middle;
  }
  .td-rank   { text-align: center; font-weight: 700; color: #666; width: 40px; }
  .td-name   { font-weight: 600; }
  .td-party  { color: #555; }
  .td-number { text-align: right; font-weight: 700; color: #1a3c5e; }
  .td-pct    { text-align: center; }
  .td-bar    { width: 120px; }

  .bar-wrap  { background: #e9ecef; border-radius: 4px; height: 12px; overflow: hidden; }
  .bar-fill  { background: #2e6da4; height: 12px; border-radius: 4px; }
  .bar-fill.winner { background: #198754; }
  .bar-fill.special{ background: #adb5bd; }

  .trophy { color: #f0a500; }

  .footer {
    text-align: center; font-size: 10px; color: #888;
    border-top: 1px solid #dde3ea; padding: 12px 30px; margin-top: 10px;
  }

  @media print {
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .no-print { display: none; }
    table { page-break-inside: auto; }
    tr    { page-break-inside: avoid; }
  }
</style>
</head>
<body>

<div class="no-print" style="padding:12px 30px; background:#f8f9fa; border-bottom:1px solid #dee2e6; display:flex; gap:10px; align-items:center;">
  <button onclick="window.print()" style="background:#1a3c5e;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:13px;cursor:pointer;">
    🖨️ Imprimir / Guardar PDF
  </button>
  <button onclick="window.close()" style="background:#6c757d;color:#fff;border:none;padding:8px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
    ✕ Cerrar
  </button>
  <span style="color:#666;font-size:12px;">Usa Ctrl+P o el botón de arriba para guardar como PDF</span>
</div>

<div class="page-header">
  <h1>📊 <?php echo htmlspecialchars($titulo); ?></h1>
  <p>
    <?php if ($subtitulo) echo htmlspecialchars($subtitulo) . ' &nbsp;|&nbsp; '; ?>
    Generado el: <?php echo date('d/m/Y H:i:s'); ?>
  </p>
</div>

<div class="stats-row">
  <div class="stat-box total">
    <div class="label">Total Votos</div>
    <div class="value"><?php echo number_format($totalGeneral); ?></div>
  </div>
  <div class="stat-box validos">
    <div class="label">Total Votos Válidos</div>
    <div class="value"><?php echo number_format($totalValidos); ?></div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th style="width:40px;">#</th>
      <th style="text-align:left;">Candidato</th>
      <th style="text-align:left;">Partido</th>
      <th style="width:90px;">Votos</th>
      <th style="width:70px;">% Total</th>
      <th style="width:70px;">% Válidos</th>
      <th style="width:130px;">Gráfico</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($datos as $idx => $d):
        $nom = $d['nom_candidato'];
        if ($d['id_candidato'] === 'EN_BLANCO')   $nom = 'Votos en Blanco';
        if ($d['id_candidato'] === 'NULOS')        $nom = 'Votos Nulos';
        if ($d['id_candidato'] === 'NO_MARCADOS')  $nom = 'Tarjetones No Marcados';

        $votos   = intval($d['total_votos']);
        $pctTot  = $totalGeneral > 0 ? round($votos / $totalGeneral * 100, 1) : 0;
        $pctVal  = $totalValidos > 0 ? round($votos / $totalValidos * 100, 1) : 0;
        $isSpec  = in_array($d['id_candidato'], ['EN_BLANCO','NULOS','NO_MARCADOS']);
        $barClass = $isSpec ? 'special' : ($idx === 0 ? 'winner' : '');
        $pctMax   = $totalGeneral > 0 ? ($votos / $totalGeneral * 100) : 0;
    ?>
    <tr>
      <td class="td-rank">
        <?php if ($idx === 0 && !$isSpec): ?><span class="trophy">🏆</span><?php else: echo $idx+1; endif; ?>
      </td>
      <td class="td-name"><?php echo htmlspecialchars($nom); ?></td>
      <td class="td-party"><?php echo htmlspecialchars($d['nom_partido'] ?: '—'); ?></td>
      <td class="td-number"><?php echo number_format($votos); ?></td>
      <td class="td-pct"><?php echo $pctTot; ?>%</td>
      <td class="td-pct"><?php echo $pctVal; ?>%</td>
      <td class="td-bar">
        <div class="bar-wrap">
          <div class="bar-fill <?php echo $barClass; ?>" style="width:<?php echo min(100, $pctMax); ?>%;"></div>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="footer">
  Sistema de Registro de Votos &nbsp;·&nbsp; <?php echo date('Y'); ?> &nbsp;·&nbsp;
  Este documento es de carácter informativo y fue generado el <?php echo date('d/m/Y \a \l\a\s H:i:s'); ?>
</div>

</body>
</html>
