<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-geo-fill"></i>
        <h1>Zonas - <?php echo htmlspecialchars($muni); ?> - <?php echo htmlspecialchars($aspirante); ?></h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=registrovoto/index">Registro de Votos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Zonas</li>
        </ol>
    </nav>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table id="lookup" class="table table-hover table-bordered" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th># Zona</th>
                        <th># Puesto</th>
                        <th>Nombre Puesto</th>
                        <th>Comuna</th>
                        <th>Barrio</th>
                        <th class="text-center">Votar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($zonas)): ?>
                        <?php foreach($zonas as $zona): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($zona['num_zona']); ?></td>
                                <td><?php echo htmlspecialchars($zona['pues_zona']); ?></td>
                                <td><?php echo htmlspecialchars($zona['nom_puesto']); ?></td>
                                <td><?php echo htmlspecialchars($zona['comuna_zona']); ?></td>
                                <td><?php echo htmlspecialchars($zona['barr_zona']); ?></td>
                                <td class="text-center">
                                    <a href="index.php?url=registrovoto/votar&zona=<?php echo urlencode($zona['num_zona']); ?>&puesto=<?php echo urlencode($zona['pues_zona']); ?>&aspirante=<?php echo urlencode($aspirante); ?>&dpto=<?php echo urlencode($dpto); ?>&muni=<?php echo urlencode($muni); ?>" class="btn btn-sm btn-success text-white" title="Registrar Votos">
                                        <i class="bi bi-check-circle-fill"></i> Ingresar Aquí
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron zonas para esta ubicación.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    $('#lookup').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "responsive": true,
        "autoWidth": false
    });
});
</script>
