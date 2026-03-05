<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-box-arrow-in-right"></i>
        <h1>Registro de Votos</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Registro de Votos</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-gear"></i> Configuración Inicial</h5>
        </div>
        <div class="card-body">
            <form id="formRegistroVotos" autocomplete="off">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="txtDepartamento" name="txtDepartamento" required <?php echo ($asignacion) ? 'disabled' : ''; ?>>
                                <option value="" selected disabled>Cargando...</option>
                                <?php if($asignacion): ?>
                                    <option value="<?php echo $asignacion['dpto_asignado']; ?>" selected><?php echo $asignacion['dpto_asignado']; ?></option>
                                <?php endif; ?>
                            </select>
                            <label for="txtDepartamento">Departamento *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="txtMunicipio" name="txtMunicipio" required <?php echo ($asignacion) ? 'disabled' : ''; ?>>
                                <option value="" selected disabled>Seleccione Municipio</option>
                                <?php if($asignacion): ?>
                                    <option value="<?php echo $asignacion['muni_asignado']; ?>" selected><?php echo $asignacion['muni_asignado']; ?></option>
                                <?php endif; ?>
                            </select>
                            <label for="txtMunicipio">Municipio *</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h5 class="mb-3 text-muted border-bottom pb-2">Seleccione a qué aspira (Haga clic):</h5>
                    <div class="row g-2">
                        <div class="col-md-3 col-sm-6"><button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold btn-aspirante" data-aspirante="ALCALDIA"><i class="bi bi-person-vcard me-2"></i>ALCALDIA</button></div>
                        <div class="col-md-3 col-sm-6"><button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold btn-aspirante" data-aspirante="CONCEJO"><i class="bi bi-person-vcard me-2"></i>CONCEJO</button></div>
                        <div class="col-md-3 col-sm-6"><button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold btn-aspirante" data-aspirante="GOBERNACION"><i class="bi bi-person-vcard me-2"></i>GOBERNACION</button></div>
                        <div class="col-md-3 col-sm-6"><button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold btn-aspirante" data-aspirante="ASAMBLEA"><i class="bi bi-person-vcard me-2"></i>ASAMBLEA</button></div>
                        <div class="col-md-3 col-sm-6"><button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold btn-aspirante" data-aspirante="JAL"><i class="bi bi-person-vcard me-2"></i>JAL</button></div>
                        <div class="col-md-3 col-sm-6"><button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold btn-aspirante" data-aspirante="SENADO"><i class="bi bi-person-vcard me-2"></i>SENADO</button></div>
                        <div class="col-md-3 col-sm-6"><button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold btn-aspirante" data-aspirante="CAMARA"><i class="bi bi-person-vcard me-2"></i>CÁMARA</button></div>
                        <div class="col-md-3 col-sm-6"><button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold btn-aspirante" data-aspirante="PRESIDENCIA"><i class="bi bi-person-vcard me-2"></i>PRESIDENCIA</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    var esCoordinador = <?php echo ($asignacion) ? 'true' : 'false'; ?>;
    var zonaAsignada = "<?php echo ($asignacion) ? $asignacion['zona_asignada'] : ''; ?>";
    var puestoAsignado = "<?php echo ($asignacion) ? $asignacion['puesto_asignado'] : ''; ?>";

    if (!esCoordinador) {
        // Cargar departamentos al iniciar solo si no es coordinador
        $.ajax({
            url: 'index.php?url=ajaxgeo/getdepartamentos',
            method: 'POST',
            success: function(response) {
                $('#txtDepartamento').html(response);
                $('#txtDepartamento').trigger('change');
            }
        });

        // Cargar municipios al cambiar departamento
        $('#txtDepartamento').change(function() {
            var dpto = $(this).val();
            if(!dpto) return;
            $.ajax({
                url: 'index.php?url=ajaxgeo/getmunicipios',
                method: 'POST',
                data: { departamento: dpto },
                success: function(response) {
                    $('#txtMunicipio').html(response);
                }
            });
        });
    }

    $('.btn-aspirante').click(function() {
        var aspirante = $(this).data('aspirante');
        var dpto = $("#txtDepartamento").val();
        var muni = $("#txtMunicipio").val();
        
        if(!dpto || !muni) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos vacíos',
                text: 'Por favor, seleccione primero el Departamento y el Municipio.'
            });
            return;
        }

        if (esCoordinador) {
            // Ir directamente a votar
            window.location.href = "index.php?url=registrovoto/votar&dpto=" + encodeURIComponent(dpto) + "&muni=" + encodeURIComponent(muni) + "&aspirante=" + encodeURIComponent(aspirante) + "&zona=" + encodeURIComponent(zonaAsignada) + "&puesto=" + encodeURIComponent(puestoAsignado);
        } else {
            // Redirigir a la pantalla de zonas con los datos seleccionados
            window.location.href = "index.php?url=registrovoto/zonas&dpto=" + encodeURIComponent(dpto) + "&muni=" + encodeURIComponent(muni) + "&aspirante=" + encodeURIComponent(aspirante);
        }
    });
});
</script>
