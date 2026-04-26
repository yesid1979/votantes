<?php include 'includes/header_dashboard.php'; ?>

<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-pencil-square"></i>
        <h1>Editar Afiliado JAC</h1>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=jac/index">JAC</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-person"></i> Modificar Datos</h5>
        </div>
        <div class="card-body">
            <form id="formJacEdit" autocomplete="off">
                <input type="hidden" name="id_afiliado" value="<?php echo $afiliado['id_afiliado']; ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="<?php echo $afiliado['fecha_inscripcion']; ?>" required>
                            <label for="txtFecha">Fecha de Inscripción *</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCedula" name="txtCedula" value="<?php echo $afiliado['ced_afiliado']; ?>" placeholder="Cédula" required>
                            <label for="txtCedula">No. de Cédula *</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre" value="<?php echo $afiliado['nom_afiliado']; ?>" placeholder="Nombres" required>
                            <label for="txtNombre">Nombres y Apellidos *</label>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDireccion" name="txtDireccion" value="<?php echo $afiliado['dir_afiliado']; ?>" placeholder="Dirección">
                            <label for="txtDireccion">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtTelefono" name="txtTelefono" value="<?php echo $afiliado['tel_afiliado']; ?>" placeholder="Teléfono">
                            <label for="txtTelefono">Teléfono</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtOcupacion" name="txtOcupacion" value="<?php echo $afiliado['ocupacion_afiliado']; ?>" placeholder="Ocupación">
                            <label for="txtOcupacion">Ocupación</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboComision" name="CboComision">
                                <option value="">-- Ninguna / Sin Comisión --</option>
                                <?php foreach($comisiones as $com): ?>
                                    <option value="<?php echo $com['id_comision']; ?>" <?php echo ($afiliado['id_comision'] == $com['id_comision']) ? 'selected' : ''; ?>>
                                        <?php echo $com['nombre_comision']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="CboComision">Comisión de Trabajo</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="txtFechnac" name="txtFechnac" value="<?php echo $afiliado['fechnac_afiliado']; ?>" required>
                            <label for="txtFechnac">Fecha de Nacimiento *</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtEdad" name="txtEdad" value="<?php echo $afiliado['edad_afiliado']; ?>" placeholder="Edad" readonly>
                            <label for="txtEdad">Edad</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboLider" name="CboLider">
                                <option value="">-- Ninguno --</option>
                                <?php foreach($lideres as $lider): ?>
                                    <option value="<?php echo $lider['ced_lider']; ?>" <?php echo ($afiliado['ced_lider'] == $lider['ced_lider']) ? 'selected' : ''; ?>>
                                        <?php echo $lider['nom_lider']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="CboLider">Líder que Refiere</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=jac/index" class="btn btn-secondary">Cancelar</a>
                    <button type="button" id="btn_actualizar" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    $('#txtFechnac').change(function() {
        var fechaNac = new Date($(this).val());
        var hoy = new Date();
        var edad = hoy.getFullYear() - fechaNac.getFullYear();
        var m = hoy.getMonth() - fechaNac.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
            edad--;
        }
        $('#txtEdad').val(edad);
    });

    $('#btn_actualizar').click(function() {
        $.ajax({
            url: "index.php?url=jac/update",
            method: "POST",
            data: $('#formJacEdit').serialize(),
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: response.message })
                    .then(() => { window.location.href = "index.php?url=jac/index"; });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                }
            }
        });
    });
});
</script>
