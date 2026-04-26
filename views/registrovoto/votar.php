<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-ui-checks-grid"></i>
        <h1>Registrar Votos - <?php echo htmlspecialchars($infoPuesto ? $infoPuesto['nom_puesto'] : "Puesto $puesto"); ?></h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=registrovoto/index">Registro de Votos</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=registrovoto/zonas&dpto=<?php echo urlencode($dpto); ?>&muni=<?php echo urlencode($muni); ?>&aspirante=<?php echo urlencode($aspirante); ?>">Zonas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Votar</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="bi bi-box-seam"></i> Mesas del Puesto: Zona <?php echo htmlspecialchars($zona); ?> - Puesto <?php echo htmlspecialchars($puesto); ?></h5>
            <span class="badge bg-light text-dark shadow-sm px-3 py-2"><i class="bi bi-hash"></i> Total Mesas: <?php echo $total_mesa; ?></span>
        </div>
        <div class="card-body">
            
            <form id="formVotar" autocomplete="off">
                <!-- Información Oculta -->
                <input type="hidden" name="dpto" value="<?php echo htmlspecialchars($dpto); ?>">
                <input type="hidden" name="muni" value="<?php echo htmlspecialchars($muni); ?>">
                <input type="hidden" name="aspirante" value="<?php echo htmlspecialchars($aspirante); ?>">
                <input type="hidden" name="zona" value="<?php echo htmlspecialchars($zona); ?>">
                <input type="hidden" name="puesto" value="<?php echo htmlspecialchars($puesto); ?>">
                
                <div class="row mb-4">
                    <div class="col-md-8 offset-md-2">
                        <label for="cboCandidato" class="form-label fw-bold"><i class="bi bi-person-fill"></i> Seleccione el Candidato o Tipo de Voto (<?php echo htmlspecialchars($aspirante); ?>):</label>
                        <select class="form-select form-select-lg shadow-sm" id="cboCandidato" name="cboCandidato" required>
                            <option value="" selected disabled>-- Seleccione Candidato / Opción --</option>
                            <?php foreach($candidatos as $cand): ?>
                                <option value="<?php echo htmlspecialchars($cand['id_candidato']); ?>"
                                        data-partido="<?php echo htmlspecialchars($cand['nom_partido']); ?>"
                                        data-num="<?php echo htmlspecialchars(isset($cand['num_candidato']) ? $cand['num_candidato'] : ''); ?>">
                                    <?php
                                        $num = !empty($cand['num_candidato']) ? '[' . $cand['num_candidato'] . '] ' : '';
                                        echo htmlspecialchars($num . $cand['nom_candidato'] . ' — ' . $cand['nom_partido']);
                                    ?>
                                </option>
                            <?php endforeach; ?>
                            <optgroup label="Otras Opciones de Votación">
                                <option value="EN_BLANCO" class="text-secondary fw-bold">Votos en Blanco</option>
                                <option value="NULOS" class="text-danger fw-bold">Votos Nulos</option>
                                <option value="NO_MARCADOS" class="text-warning fw-bold">Tarjetones No Marcados</option>
                            </optgroup>
                        </select>
                        <!-- Info candidato seleccionado -->
                        <div id="infoCandidato" class="mt-2" style="display:none;">
                            <div class="alert alert-primary py-2 mb-0 d-flex align-items-center gap-3">
                                <span class="badge bg-warning text-dark fs-5 px-3 py-2" id="badgeNumTarjeton" style="min-width:60px; font-size:1.2rem!important;"></span>
                                <div>
                                    <strong id="infoNomCandidato"></strong><br>
                                    <small class="text-muted" id="infoPartido"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="text-muted">

                <h5 class="mb-3 text-muted fw-bold"><i class="bi bi-list-ol"></i> <?php echo ($aspirante == 'JAC') ? 'Digite el total de votos obtenidos:' : 'Digite los votos por mesa:'; ?></h5>
                <div class="row g-3">
                    <?php 
                    if ($aspirante == 'JAC'): 
                    ?>
                        <div class="col-md-4 offset-md-4">
                            <div class="form-floating shadow-sm border border-success rounded">
                                <input type="number" class="form-control text-center fs-2 fw-bold text-success input-votos" 
                                       id="mesa_1" name="mesas[1]" placeholder="0" min="0">
                                <label for="mesa_1" class="text-success fw-bold">TOTAL VOTOS PLANCHA / CANDIDATO</label>
                            </div>
                        </div>
                    <?php 
                    // Si total_mesa es 0 y NO es JAC, mostramos un aviso
                    elseif ($total_mesa == 0): 
                    ?>
                        <div class="col-12 text-center py-4">
                            <div class="alert alert-warning d-inline-block">
                                <i class="bi bi-exclamation-triangle-fill"></i> Este puesto no tiene configurada la cantidad de mesas. Por favor edite la zona y asigne el número de mesas (total_mesa).
                            </div>
                        </div>
                    <?php 
                    else:
                        for($i = 1; $i <= $total_mesa; $i++): 
                    ?>
                        <div class="col-md-2 col-sm-4 col-6">
                            <div class="form-floating shadow-sm">
                                <input type="number" class="form-control text-center fs-5 fw-bold text-primary input-votos" 
                                       id="mesa_<?php echo $i; ?>" name="mesas[<?php echo $i; ?>]" placeholder="0" min="0">
                                <label for="mesa_<?php echo $i; ?>" class="text-secondary fw-bold">Mesa <?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></label>
                            </div>
                        </div>
                    <?php 
                        endfor; 
                    endif; 
                    ?>
                </div>

                <?php if ($total_mesa > 0 || $aspirante == 'JAC'): ?>
                <div class="mt-4 p-3 bg-light rounded border border-2 border-primary d-flex justify-content-between align-items-center">
                    <div class="fs-4 fw-bold">Total Votos Ingresados: <span id="lblTotalVotos" class="text-success">0</span></div>
                    <div>
                        <a href="index.php?url=registrovoto/zonas&dpto=<?php echo urlencode($dpto); ?>&muni=<?php echo urlencode($muni); ?>&aspirante=<?php echo urlencode($aspirante); ?>" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Regresar
                        </a>
                        <button type="button" id="btn_guardar_votos" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Guardar Votos
                        </button>
                    </div>
                </div>
                <?php else: ?>
                    <div class="mt-4 text-center">
                        <a href="index.php?url=zona/index" class="btn btn-secondary">
                            <i class="bi bi-geo-alt"></i> Ir a administrar Zonas
                        </a>
                    </div>
                <?php endif; ?>
            </form>

        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    
    // Mostrar badge tarjetón al seleccionar candidato
    $('#cboCandidato').change(function() {
        var selected = $(this).find('option:selected');
        var num     = selected.data('num');
        var nom     = selected.text();
        var partido = selected.data('partido');
        var val     = $(this).val();

        if (val && val !== 'EN_BLANCO' && val !== 'NULOS' && val !== 'NO_MARCADOS' && num) {
            $('#badgeNumTarjeton').html('<i class="bi bi-ticket-perforated-fill"></i> ' + num);
            $('#infoNomCandidato').text(nom);
            $('#infoPartido').text(partido ? 'Partido: ' + partido : '');
            $('#infoCandidato').show();
        } else if (val === 'EN_BLANCO' || val === 'NULOS' || val === 'NO_MARCADOS') {
            $('#badgeNumTarjeton').html('<i class="bi bi-info-circle"></i> —');
            $('#infoNomCandidato').text(nom);
            $('#infoPartido').text('');
            $('#infoCandidato').show();
        } else {
            $('#infoCandidato').hide();
        }
    });

    // Calcular el total de votos a medida que escriben
    $('.input-votos').on('input', function() {
        var total = 0;
        $('.input-votos').each(function() {
            var val = parseInt($(this).val());
            if(!isNaN(val)) {
                total += val;
            }
        });
        $('#lblTotalVotos').text(total);
    });

    $('#btn_guardar_votos').click(function() {
        if(!$("#cboCandidato").val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Seleccione un candidato',
                text: 'Debe seleccionar el candidato al que desea asignarle los votos.'
            });
            return;
        }

        var total = parseInt($('#lblTotalVotos').text());
        if(total === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin Votos',
                text: 'No ha ingresado ningún voto en ninguna mesa.'
            });
            return;
        }

        Swal.fire({
            title: '¿Guardar Votos?',
            html: 'Se guardará un total de <b>' + total + ' votos</b> para el candidato o opción seleccionada.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                
                $.ajax({
                    url: 'index.php?url=registrovoto/store',
                    method: 'POST',
                    data: $('#formVotar').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Votos Guardados',
                                text: response.message
                            }).then(() => {
                                // Limpiar campos manteniendo el candidato o todo completo si se prefiere 
                                // (dejaremos el candidato limpio para que registren a otro)
                                $('.input-votos').val('');
                                $('#lblTotalVotos').text('0');
                                $('#cboCandidato').val('');
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Ocurrió un error de comunicación con el servidor.', 'error');
                    }
                });
            }
        });
    });
});
</script>
