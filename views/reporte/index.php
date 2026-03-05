<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <h1>Reportes Generales</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reportes</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-pills mb-4" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="lideres-tab" data-bs-toggle="tab" data-bs-target="#lideres" type="button" role="tab" aria-controls="lideres" aria-selected="true">
                        <i class="bi bi-person-badge"></i> Líderes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="votantes-tab" data-bs-toggle="tab" data-bs-target="#votantes" type="button" role="tab" aria-controls="votantes" aria-selected="false">
                        <i class="bi bi-person-check-fill"></i> Votantes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="simpatizantes-tab" data-bs-toggle="tab" data-bs-target="#simpatizantes" type="button" role="tab" aria-controls="simpatizantes" aria-selected="false">
                        <i class="bi bi-emoji-smile"></i> Simpatizantes
                    </button>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="reportTabsContent">
                <!-- Líderes Tab -->
                <div class="tab-pane fade show active" id="lideres" role="tabpanel" aria-labelledby="lideres-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Listado de Líderes</h5>
                        <div class="btn-group">
                            <a href="formatos/lideres.php" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tableLideres" class="table table-hover table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cédula</th>
                                    <th>Nombres</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Comuna</th>
                                    <th>Barrio</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Votantes Tab -->
                <div class="tab-pane fade" id="votantes" role="tabpanel" aria-labelledby="votantes-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Listado de Votantes</h5>
                        <div class="btn-group">
                             <!-- Enlaces a reportes excel existentes si los hay o genéricos -->
                             <button type="button" class="btn btn-sm btn-outline-success" onclick="exportarExcel('votantes')">
                                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tableVotantes" class="table table-hover table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cédula</th>
                                    <th>Nombres</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Comuna</th>
                                    <th>Barrio</th>
                                    <th>Líder</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Simpatizantes Tab -->
                <div class="tab-pane fade" id="simpatizantes" role="tabpanel" aria-labelledby="simpatizantes-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Listado de Simpatizantes</h5>
                        <div class="btn-group">
                             <button type="button" class="btn btn-sm btn-outline-success" onclick="exportarExcel('simpatizantes')">
                                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tableSimpatizantes" class="table table-hover table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cédula</th>
                                    <th>Nombres</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Comuna</th>
                                    <th>Barrio</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    var lang = {
        "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
    };

    // DataTable Líderes
    $('#tableLideres').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "index.php?url=reporte/ajaxLideres",
            "type": "POST"
        },
        "language": lang,
        "columns": [
            { "data": "id_lider" },
            { "data": "ced_lider" },
            { "data": "nom_lider" },
            { "data": "cel_lider" },
            { "data": "email_lider" },
            { "data": "comuna_lider" },
            { "data": "barrio_lider" }
        ],
        "order": [[0, "desc"]]
    });

    // DataTable Votantes
    $('#tableVotantes').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "index.php?url=reporte/ajaxVotantes",
            "type": "POST"
        },
        "language": lang,
        "columns": [
            { "data": "id_votante" },
            { "data": "ced_votante" },
            { "data": "nom_votante" },
            { "data": "cel_votante" },
            { "data": "email_votante" },
            { "data": "comuna_votante" },
            { "data": "barrio_votante" },
            { "data": "lider" }
        ],
        "order": [[0, "desc"]]
    });

    // DataTable Simpatizantes
    $('#tableSimpatizantes').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "index.php?url=reporte/ajaxSimpatizantes",
            "type": "POST"
        },
        "language": lang,
        "columns": [
            { "data": "id_simpatizante" },
            { "data": "ced_simpatizante" },
            { "data": "nom_simpatizante" },
            { "data": "cel_simpatizante" },
            { "data": "email_simpatizante" },
            { "data": "comuna_simpatizante" },
            { "data": "barrio_simpatizante" }
        ],
        "order": [[0, "desc"]]
    });
});

function exportarExcel(tipo) {
    if(tipo == 'votantes') {
        window.location.href = 'formatos/reporte_general_excel.php?tipo=votantes';
    } else if(tipo == 'simpatizantes') {
        window.location.href = 'formatos/reporte_general_excel.php?tipo=simpatizantes';
    }
}
</script>
