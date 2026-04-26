<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-pencil-square"></i>
        <h1>Editar Candidato</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=candidato/index">Candidatos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>
</div>



<div class="container-fluid">
    <div id="resp"></div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0"><i class="bi bi-pencil-square"></i> Actualizar Datos</h5>
        </div>
        <div class="card-body">
            <form id="form1" name="form1" enctype="multipart/form-data" autocomplete="off">
                 <input type="hidden" name="txtId" value="<?php echo $candidato['id_candidato']; ?>">

                <h5 class="mb-3 text-muted border-bottom pb-2">Datos del Partido</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNomPartido" name="txtNomPartido" value="<?php echo $candidato['nom_partido']; ?>" required>
                            <label for="txtNomPartido">Nombre Partido *</label>
                        </div>
                    </div>
                     <div class="col-md-6">
                         <label for="logo_partido" class="form-label">Logo Partido (Dejar vacío para mantener actual)</label>
                         <input class="form-control" type="file" id="logo_partido" name="logo_partido" accept="image/*">
                         <?php if(!empty($candidato['logo_partido'])): ?>
                            <small class="text-muted">Actual: <?php echo $candidato['logo_partido']; ?></small>
                         <?php endif; ?>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Datos del Candidato</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNumCandidato" name="txtNumCandidato" value="<?php echo $candidato['num_candidato']; ?>" required maxlength="10">
                            <label for="txtNumCandidato">Número Tarjetón *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNomCandidato" name="txtNomCandidato" value="<?php echo $candidato['nom_candidato']; ?>" required>
                            <label for="txtNomCandidato">Nombre Candidato *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="txtAspirante" name="txtAspirante" required>
                                <option value="ALCALDIA" <?php echo $candidato['aspirante_a'] == 'ALCALDIA' ? 'selected' : ''; ?>>ALCALDIA</option>
                                <option value="CONCEJO" <?php echo $candidato['aspirante_a'] == 'CONCEJO' ? 'selected' : ''; ?>>CONCEJO</option>
                                <option value="GOBERNACION" <?php echo $candidato['aspirante_a'] == 'GOBERNACION' ? 'selected' : ''; ?>>GOBERNACION</option>
                                <option value="ASAMBLEA" <?php echo $candidato['aspirante_a'] == 'ASAMBLEA' ? 'selected' : ''; ?>>ASAMBLEA</option>
                                <option value="JAL" <?php echo $candidato['aspirante_a'] == 'JAL' ? 'selected' : ''; ?>>JAL</option>
                                <option value="SENADO" <?php echo $candidato['aspirante_a'] == 'SENADO' ? 'selected' : ''; ?>>SENADO</option>
                                <option value="CAMARA" <?php echo $candidato['aspirante_a'] == 'CAMARA' ? 'selected' : ''; ?>>CÁMARA</option>
                                <option value="PRESIDENCIA" <?php echo $candidato['aspirante_a'] == 'PRESIDENCIA' ? 'selected' : ''; ?>>PRESIDENCIA</option>
                                <option value="JAC" <?php echo $candidato['aspirante_a'] == 'JAC' ? 'selected' : ''; ?>>JAC</option>
                            </select>
                            <label for="txtAspirante">Aspira a *</label>
                        </div>
                    </div>
                     <div class="col-md-6">
                         <label for="foto_candidato" class="form-label">Foto Candidato (Dejar vacío para mantener actual)</label>
                         <input class="form-control" type="file" id="foto_candidato" name="foto_candidato" accept="image/*">
                          <?php if(!empty($candidato['foto_candidato'])): ?>
                            <small class="text-muted">Actual: <?php echo $candidato['foto_candidato']; ?></small>
                         <?php endif; ?>
                    </div>
                     <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="selEstado" name="selEstado" required>
                                <option value="Activo" <?php echo $candidato['estado_candidato'] == 'Activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="Inactivo" <?php echo $candidato['estado_candidato'] == 'Inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                            <label for="selEstado">Estado</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=candidato/index" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" id="btn_update" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat"></i> Actualizar
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
        e.preventDefault();

        if($("#txtNomPartido").val() === "" || $("#txtNomCandidato").val() === "") {
             Swal.fire({ icon: 'warning', title: 'Campos vacíos', text: 'Complete los campos obligatorios.' });
             return;
        }

        var formData = new FormData(this);

        $.ajax({
            url: "index.php?url=candidato/update",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: response.message });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error.' });
            }
        });
    });
});
</script>
