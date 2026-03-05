<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-zoom-in text-primary"></i>
        <h1>Detalle de Votos</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=registrovoto/resultados">Resultados en Vivo</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detalle</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">

    <!-- Cabecera candidato -->
    <div class="card shadow-sm mb-4 border-start border-primary border-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="mb-1 fw-bold"><i class="bi bi-person-circle text-primary"></i> <?php echo htmlspecialchars($nomCandidato); ?></h4>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($aspirante); ?></span>
                    <?php if($dpto): ?><span class="badge bg-secondary ms-1"><?php echo htmlspecialchars($dpto); ?></span><?php endif; ?>
                    <?php if($muni): ?><span class="badge bg-info text-dark ms-1"><?php echo htmlspecialchars($muni); ?></span><?php endif; ?>
                </div>
                <div class="col-auto d-flex gap-2 flex-wrap">
                    <?php
                        $qsDetalle = http_build_query([
                            'id_candidato' => $id_candidato,
                            'aspirante'    => $aspirante,
                            'dpto'         => $dpto,
                            'muni'         => $muni,
                        ]);
                    ?>
                    <a href="formatos/votos_detallado_pdf.php?<?php echo $qsDetalle; ?>" target="_blank"
                       class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF Detalle
                    </a>
                    <a href="formatos/votos_detallado_excel.php?<?php echo $qsDetalle; ?>" target="_blank"
                       class="btn btn-outline-success btn-sm">
                        <i class="bi bi-file-earmark-excel-fill"></i> Excel Detalle
                    </a>
                    <a href="index.php?url=registrovoto/resultados" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Volver al Ranking
                    </a>
                </div>
            </div>
        </div>
    </div>


    <?php if(empty($detalles)): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                <h4>No hay votos registrados para este candidato con los filtros seleccionados.</h4>
            </div>
        </div>
    <?php else:
        // Agrupar por zona-puesto
        $grupos = [];
        $totalGeneral = 0;
        foreach($detalles as $row) {
            $key = $row['dpto'] . ' / ' . $row['muni'] . ' / Zona ' . $row['zona'] . ' / Puesto ' . $row['puesto'];
            if(!isset($grupos[$key])) {
                $grupos[$key] = [
                    'nom_puesto' => $row['nom_puesto'],
                    'zona'       => $row['zona'],
                    'puesto'     => $row['puesto'],
                    'muni'       => $row['muni'],
                    'dpto'       => $row['dpto'],
                    'mesas'      => [],
                    'subtotal'   => 0
                ];
            }
            $grupos[$key]['mesas'][] = ['mesa' => $row['mesa'], 'votos' => $row['votos']];
            $grupos[$key]['subtotal'] += intval($row['votos']);
            $totalGeneral += intval($row['votos']);
        }
    ?>

    <!-- Total general -->
    <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm">
        <i class="bi bi-check-circle-fill fs-3 me-3"></i>
        <div>
            <h5 class="mb-0 fw-bold">Total General de Votos: <span class="text-success"><?php echo number_format($totalGeneral); ?></span></h5>
            <small class="text-muted">Suma de todas las zonas y puestos registrados</small>
        </div>
    </div>

    <?php foreach($grupos as $key => $grupo): ?>
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                <strong><?php echo htmlspecialchars($key); ?></strong>
                <?php if($grupo['nom_puesto']): ?>
                    &nbsp;<span class="text-muted">— <?php echo htmlspecialchars($grupo['nom_puesto']); ?></span>
                <?php endif; ?>
            </div>
            <span class="badge bg-primary fs-6 px-3 py-2">Sub-total: <?php echo number_format($grupo['subtotal']); ?> votos</span>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <?php foreach($grupo['mesas'] as $m): ?>
                <div class="col-md-1 col-sm-2 col-3">
                    <div class="text-center p-2 border rounded bg-white shadow-sm">
                        <div class="text-muted small fw-bold">Mesa <?php echo str_pad($m['mesa'], 2, '0', STR_PAD_LEFT); ?></div>
                        <div class="fs-5 fw-bold text-primary"><?php echo number_format($m['votos']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

</div>

<?php include 'includes/footer_dashboard.php'; ?>
