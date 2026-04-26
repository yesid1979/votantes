<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-person-plus"></i>
        <h1>Agregar Candidato</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=candidato/index">Candidatos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar</li>
        </ol>
    </nav>
</div>



<div class="container-fluid">
    <div id="resp"></div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus"></i> Nuevo Candidato</h5>
        </div>
        <div class="card-body">
            <form id="form1" name="form1" enctype="multipart/form-data" autocomplete="off">
                
                <h5 class="mb-3 text-muted border-bottom pb-2">Datos del Partido</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNomPartido" name="txtNomPartido" placeholder="Nombre Partido" required>
                            <label for="txtNomPartido">Nombre Partido *</label>
                        </div>
                    </div>
                     <div class="col-md-6">
                         <label for="logo_partido" class="form-label">Logo Partido</label>
                         <input class="form-control" type="file" id="logo_partido" name="logo_partido" accept="image/*">
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Datos del Candidato</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNumCandidato" name="txtNumCandidato" placeholder="Número" required maxlength="10">
                            <label for="txtNumCandidato">Número Tarjetón *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNomCandidato" name="txtNomCandidato" placeholder="Nombre Candidato" required>
                            <label for="txtNomCandidato">Nombre Candidato *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="txtAspirante" name="txtAspirante" required>
                                <option value="" selected disabled>Seleccione...</option>
                                <option value="ALCALDIA">ALCALDIA</option>
                                <option value="CONCEJO">CONCEJO</option>
                                <option value="GOBERNACION">GOBERNACION</option>
                                <option value="ASAMBLEA">ASAMBLEA</option>
                                <option value="JAL">JAL</option>
                                <option value="SENADO">SENADO</option>
                                <option value="CAMARA">CÁMARA</option>
                                <option value="PRESIDENCIA">PRESIDENCIA</option>
                                <option value="JAC">JAC</option>
                            </select>
                            <label for="txtAspirante">Aspira a *</label>
                        </div>
                    </div>
                     <div class="col-md-6">
                         <label for="foto_candidato" class="form-label">Foto Candidato</label>
                         <input class="form-control" type="file" id="foto_candidato" name="foto_candidato" accept="image/*">
                    </div>
                     <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="selEstado" name="selEstado" required>
                                <option value="Activo" selected>Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                            <label for="selEstado">Estado</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=candidato/index" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" id="btn_grabar" class="btn btn-primary">
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
    $('#form1').submit(function(e) {
        e.preventDefault(); // Stop normal submission for AJAX

        if($("#txtNomPartido").val() === "" || $("#txtNomCandidato").val() === "") {
             Swal.fire({ icon: 'warning', title: 'Campos vacíos', text: 'Complete los campos obligatorios.' });
             return;
        }
        
        var formData = new FormData(this);

        $.ajax({
            url: "index.php?url=candidato/store",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
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
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error al procesar la solicitud.' });
            }
        });
    });
});
</script>
