<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-geo-alt"></i>
        <h1>Agregar Zona</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=zona/index">Zonas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar</li>
        </ol>
    </nav>
</div>



<div class="container-fluid">
    <div id="resp"></div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-geo-alt"></i> Nueva Zona</h5>
        </div>
        <div class="card-body">
            <form id="form1" name="form1" autocomplete="off">
                <h5 class="mb-3 text-muted border-bottom pb-2">Información de la Zona</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNum_zona" name="txtNum_zona" placeholder="# Zona" required>
                            <label for="txtNum_zona"># Zona *</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtPues_zona" name="txtPues_zona" placeholder="# Puesto" required>
                            <label for="txtPues_zona"># Puesto *</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtComuna_zona" name="txtComuna_zona" placeholder="Comuna">
                            <label for="txtComuna_zona">Comuna</label>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-floating">
                            <select class="form-select" id="CboEstado" name="CboEstado" required>
                                <option value="" selected disabled>Seleccione</option>
                                <option value="Activa">Activa</option>
                                <option value="Inactiva">Inactiva</option>
                            </select>
                            <label for="CboEstado">Estado *</label>
                        </div>
                    </div>
                </div>

                 <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Detalles de Ubicación</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_puesto" name="txtNom_puesto" placeholder="Nombre Puesto" required>
                            <label for="txtNom_puesto">Nombre Puesto *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtMun_zona" name="txtMun_zona" placeholder="Municipio" required>
                            <label for="txtMun_zona">Municipio *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_zona" name="txtDir_zona" placeholder="Dirección">
                            <label for="txtDir_zona">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtBarr_zona" name="txtBarr_zona" placeholder="Barrio">
                            <label for="txtBarr_zona">Barrio</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=zona/index" class="btn btn-secondary">Cancelar</a>
                    <button type="button" id="btn_grabar" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    $('#btn_grabar').click(function() {
        if($("#txtNom_puesto").val() === "" || $("#txtNum_zona").val() === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos vacíos',
                text: 'Por favor, complete los campos obligatorios (*).'
            });
            return;
        }

        $.ajax({
            url: "index.php?url=zona/store",
            method: "POST",
            data: $('#form1').serialize(),
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: response.message })
                    .then((result) => { $('#form1')[0].reset(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error en la solicitud.' });
            }
        });
    });
});
</script>
