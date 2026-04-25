<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-speedometer2"></i>
        <h1>Dashboard General</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
</div>

<!-- Stats Grid -->
<div class="row g-4">
    
    <?php if(tienePermiso($id_tipouser, 'Usuarios', 'ver')): ?>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <div class="stats-info">
                <p>Usuarios</p>
                <h3><?php echo $stats['usuarios']; ?></h3>
                <a href="index.php?url=usuario/index" class="text-decoration-none small mt-2 d-block">Ver detalles &rarr;</a>
            </div>
            <div class="stats-icon">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(tienePermiso($id_tipouser, 'Zonas', 'ver')): ?>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card" style="border-left-color: #17a2b8;">
            <div class="stats-info">
                <p>Zonas</p>
                <h3><?php echo $stats['zonas']; ?></h3>
                <a href="index.php?url=zona/index" class="text-decoration-none small mt-2 d-block" style="color: #17a2b8;">Ver detalles &rarr;</a>
            </div>
            <div class="stats-icon" style="color: #17a2b8;">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(tienePermiso($id_tipouser, 'Simpatizantes', 'ver')): ?>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card" style="border-left-color: #28a745;">
            <div class="stats-info">
                <p>Simpatizante cercanos</p>
                <h3><?php echo $stats['simpatizantes']; ?></h3>
                <a href="index.php?url=simpatizante/index" class="text-decoration-none small mt-2 d-block" style="color: #28a745;">Ver detalles &rarr;</a>
            </div>
            <div class="stats-icon" style="color: #28a745;">
                <i class="bi bi-emoji-smile-fill"></i>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(tienePermiso($id_tipouser, 'Votantes', 'ver')): ?>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card" style="border-left-color: #ffc107;">
            <div class="stats-info">
                <p>Cuerpo electoral</p>
                <h3><?php echo $stats['votantes']; ?></h3>
                <a href="index.php?url=votante/index" class="text-decoration-none small mt-2 d-block" style="color: #ffc107;">Ver detalles &rarr;</a>
            </div>
            <div class="stats-icon" style="color: #ffc107;">
                <i class="bi bi-person-check-fill"></i>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(tienePermiso($id_tipouser, 'Líderes', 'ver')): ?>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card" style="border-left-color: #dc3545;">
            <div class="stats-info">
                <p>Líder amigo</p>
                <h3><?php echo $stats['lideres']; ?></h3>
                <a href="index.php?url=lider/index" class="text-decoration-none small mt-2 d-block" style="color: #dc3545;">Ver detalles &rarr;</a>
            </div>
            <div class="stats-icon" style="color: #dc3545;">
                <i class="bi bi-person-badge-fill"></i>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Charts Section -->
<div class="row g-4 mt-2">
    <!-- Votantes por Zona (Bar Chart) -->
    <div class="col-xl-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-bar-chart-fill me-2"></i>Votantes por Zona (Top 10)</h6>
            </div>
            <div class="card-body">
                <canvas id="chartZonas"></canvas>
            </div>
        </div>
    </div>

    <!-- Simpatizantes por Sexo (Pie Chart) -->
    <div class="col-xl-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-pie-chart-fill me-2"></i>Simpatizantes por Sexo</h6>
            </div>
            <div class="card-body d-flex align-items-center">
                <canvas id="chartSexo"></canvas>
            </div>
        </div>
    </div>

    <!-- Votantes por Líder (Pie Chart) -->
    <div class="col-xl-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-person-fill-check me-2"></i>Votantes por Líder (Top 10)</h6>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="chartLideres"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Colors Palette
    const colors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
        '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
    ];

    // Chart Zonas
    const ctxZonas = document.getElementById('chartZonas').getContext('2d');
    new Chart(ctxZonas, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($stats['chart_zonas'], 'label')); ?>,
            datasets: [{
                label: 'Votantes',
                data: <?php echo json_encode(array_column($stats['chart_zonas'], 'value')); ?>,
                backgroundColor: '#4e73df',
                hoverBackgroundColor: '#2e59d9',
                borderColor: '#4e73df',
                borderRadius: 5
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { drawBorder: false } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // Chart Sexo
    const ctxSexo = document.getElementById('chartSexo').getContext('2d');
    new Chart(ctxSexo, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($stats['chart_sexo'], 'label')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($stats['chart_sexo'], 'value')); ?>,
                backgroundColor: ['#36b9cc', '#e74a3b', '#1cc88a'],
                hoverBackgroundColor: ['#2c9faf', '#be2617', '#17a673'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Chart Lideres
    const ctxLideres = document.getElementById('chartLideres').getContext('2d');
    new Chart(ctxLideres, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($stats['chart_lideres'], 'label')); ?>,
            datasets: [{
                label: 'Votantes',
                data: <?php echo json_encode(array_column($stats['chart_lideres'], 'value')); ?>,
                backgroundColor: colors,
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                x: { beginAtZero: true },
                y: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>