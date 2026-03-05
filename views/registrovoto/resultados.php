<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-bar-chart-fill text-primary"></i>
        <h1>Resultados en Vivo</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Resultados en Vivo</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <!-- Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title text-muted mb-3"><i class="bi bi-funnel"></i> Filtros de Resultados</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filtroAspirante" class="form-label">Aspira a</label>
                    <select class="form-select" id="filtroAspirante">
                        <option value="ALCALDIA" selected>ALCALDIA</option>
                        <option value="CONCEJO">CONCEJO</option>
                        <option value="GOBERNACION">GOBERNACION</option>
                        <option value="ASAMBLEA">ASAMBLEA</option>
                        <option value="JAL">JAL</option>
                        <option value="SENADO">SENADO</option>
                        <option value="CAMARA">CÁMARA</option>
                        <option value="PRESIDENCIA">PRESIDENCIA</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroDpto" class="form-label">Departamento</label>
                    <select class="form-select" id="filtroDpto">
                        <option value="">-- Todos --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroMuni" class="form-label">Municipio</label>
                    <select class="form-select" id="filtroMuni">
                        <option value="">-- Todos --</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button id="btnBuscar" class="btn btn-primary w-100"><i class="bi bi-search"></i> Ver Resultados</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Quick View -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white shadow-sm border-0 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h5 class="card-title opacity-75">Total Votos Contabilizados</h5>
                    <h1 class="display-3 fw-bold mb-0" id="statTotal">0</h1>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white shadow-sm border-0 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h5 class="card-title opacity-75">Total Votos Válidos</h5>
                    <h1 class="display-3 fw-bold mb-0" id="statValidos">0</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0"><i class="bi bi-trophy text-warning"></i> Ranking de Candidatos</h5>
                <span class="badge bg-danger rounded-pill pulse-animation"><i class="bi bi-record-circle"></i> EN VIVO</span>
            </div>
            <div class="d-flex gap-2" id="btnDescargas" style="display:none!important;">
                <a id="btnPdfConsolidado" href="#" target="_blank"
                   class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                </a>
                <a id="btnExcelConsolidado" href="#" target="_blank"
                   class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-earmark-excel-fill"></i> Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div id="contenedorResultados" class="row g-4">
                <!-- Se llenará con Javascript -->
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-hourglass-split display-4 mb-3 d-block"></i>
                    <h4>Presione "Ver Resultados" para cargar los datos</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<style>
.progress-huge {
    height: 30px;
    border-radius: 15px;
    background-color: #e9ecef;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
}
.progress-huge .progress-bar {
    font-size: 1rem;
    font-weight: bold;
    line-height: 30px;
    border-radius: 15px;
}
.pulse-animation {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
.candidato-card {
    transition: transform 0.2s;
}
.candidato-card:hover {
    transform: translateY(-5px);
}
</style>

<script>
$(document).ready(function() {
    
    // Cargar departamentos
    $.ajax({
        url: 'index.php?url=ajaxgeo/getdepartamentos',
        method: 'POST',
        success: function(response) {
            $('#filtroDpto').html('<option value="">-- Todos --</option>' + response);
            // Hacer trigger para que cargue los municipios de manera inicial si hay un valor, aunque por defecto está "-- Todos --"
            $('#filtroDpto').trigger('change');
        }
    });

    // Cargar municipios al cambiar departamento
    $('#filtroDpto').change(function() {
        var dpto = $(this).val();
        if(!dpto) {
            $('#filtroMuni').html('<option value="">-- Todos --</option>');
            return;
        }
        $.ajax({
            url: 'index.php?url=ajaxgeo/getmunicipios',
            method: 'POST',
            data: { departamento: dpto },
            success: function(response) {
                $('#filtroMuni').html('<option value="">-- Todos --</option>' + response);
            }
        });
    });

    // Lógica para buscar resultados
    function cargarResultados() {
        var aspirante = $('#filtroAspirante').val();
        var dpto = $('#filtroDpto').val();
        var muni = $('#filtroMuni').val();
        
        $('#contenedorResultados').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div><h5 class="mt-3">Calculando resultados...</h5></div>');
        
        $.ajax({
            url: 'index.php?url=registrovoto/ajaxResultados',
            method: 'POST',
            data: { aspirante: aspirante, dpto: dpto, muni: muni },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    $('#statTotal').text(res.stats.total_general.toLocaleString());
                    $('#statValidos').text(res.stats.total_validos.toLocaleString());
                    
                    if (res.data.length === 0) {
                        $('#contenedorResultados').html('<div class="col-12 text-center py-5 text-muted"><i class="bi bi-inbox display-4 mb-3 d-block"></i><h4>No hay votos registrados para estos filtros aún.</h4></div>');
                        return;
                    }
                    
                    var html = '';
                    var totalG = res.stats.total_general;
                    
                    $.each(res.data, function(index, cand) {
                        var percent = totalG > 0 ? ((cand.total_votos / totalG) * 100).toFixed(1) : 0;
                        var foto = cand.id_candidato === 'EN_BLANCO' ? 'images/partidos/default.jpg' : 
                                  (cand.id_candidato === 'NULOS' ? 'images/partidos/default.jpg' : 
                                  (cand.id_candidato === 'NO_MARCADOS' ? 'images/partidos/default.jpg' : 
                                  (cand.foto_candidato ? 'images/partidos/' + cand.foto_candidato : 'images/profiles/default.png')));
                        
                        var colorBar = 'bg-primary';
                        if (index === 0) colorBar = 'bg-success';
                        else if (index === 1) colorBar = 'bg-info';
                        else if (cand.id_candidato === 'EN_BLANCO') colorBar = 'bg-secondary';
                        else if (cand.id_candidato === 'NULOS') colorBar = 'bg-danger';
                        else if (cand.id_candidato === 'NO_MARCADOS') colorBar = 'bg-warning text-dark';
                        
                        var badgeTop = '';
                        if (index === 0 && cand.id_candidato !== 'EN_BLANCO' && cand.id_candidato !== 'NULOS' && cand.id_candidato !== 'NO_MARCADOS') {
                            badgeTop = '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"><i class="bi bi-trophy-fill"></i> N° 1</span>';
                        }
                        
                        html += `
                        <div class="col-12 mt-3">
                            <div class="card shadow-sm border-0 candidato-card position-relative">
                                ${badgeTop}
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto text-center">
                                            <h4 class="text-muted mb-0">#${index+1}</h4>
                                        </div>
                                        <div class="col-auto text-center" style="width: 80px;">
                                           <img src="${foto}" class="img-fluid rounded-circle shadow-sm" style="width: 60px; height: 60px; object-fit: cover;" onerror="this.src='images/profiles/default.png'">
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="mb-1 fw-bold text-dark">${cand.nom_candidato}</h5>
                                            <small class="text-muted"><i class="bi bi-flag"></i> ${cand.nom_partido}</small>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Porcentaje Votos</span>
                                                <span class="fw-bold">${percent}%</span>
                                            </div>
                                            <div class="progress progress-huge">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated ${colorBar}" role="progressbar" style="width: ${percent}%;" aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <h2 class="mb-0 fw-bold text-primary">${parseInt(cand.total_votos).toLocaleString()}</h2>
                                            <small class="text-muted">Votos Reales</small>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <a href="index.php?url=registrovoto/detalle&id_candidato=${encodeURIComponent(cand.id_candidato)}&aspirante=${encodeURIComponent(aspirante)}&dpto=${encodeURIComponent(dpto)}&muni=${encodeURIComponent(muni)}" 
                                               class="btn btn-outline-primary btn-sm" title="Ver detalle por Zona/Puesto">
                                                <i class="bi bi-zoom-in"></i><br><small>Detalle</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                    
                    $('#contenedorResultados').html(html);
                } else {
                    $('#contenedorResultados').html('<div class="col-12 text-center text-danger py-4">Error cargando resultados.</div>');
                }
            },
            error: function() {
                $('#contenedorResultados').html('<div class="col-12 text-center text-danger py-4">Error de conexión con el servidor.</div>');
            }
        });
    }

    $('#btnBuscar').click(cargarResultados);

    // Actualizar links de descarga
    function actualizarLinksDescarga() {
        var asp  = $('#filtroAspirante').val();
        var dpto = $('#filtroDpto').val();
        var muni = $('#filtroMuni').val();
        var qs   = 'aspirante=' + encodeURIComponent(asp)
                 + '&dpto='      + encodeURIComponent(dpto)
                 + '&muni='      + encodeURIComponent(muni);
        $('#btnPdfConsolidado').attr('href',   'formatos/votos_consolidado_pdf.php?'   + qs);
        $('#btnExcelConsolidado').attr('href',  'formatos/votos_consolidado_excel.php?' + qs);
        $('#btnDescargas').show();
    }

    $('#btnBuscar').click(actualizarLinksDescarga);

    // Auto-cargar la primera vez con Alcaldía
    setTimeout(function() { cargarResultados(); actualizarLinksDescarga(); }, 500);
    
    // Refrescar automáticamente cada 15 segundos
    setInterval(function(){
        if ($('#contenedorResultados').find('.card').length > 0) {
            cargarResultados();
        }
    }, 15000);
});
</script>
