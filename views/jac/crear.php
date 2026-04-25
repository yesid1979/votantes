<?php include 'includes/header_dashboard.php'; ?>

<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-house-door"></i>
        <h1>Nuevo Afiliado JAC</h1>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=jac/index">JAC</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus"></i> Formulario de Inscripción</h5>
        </div>
        <div class="card-body">
            <form id="formJac" autocomplete="off">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="<?php echo date('Y-m-d'); ?>" required>
                            <label for="txtFecha">Fecha de Inscripción *</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCedula" name="txtCedula" placeholder="Cédula" required>
                            <label for="txtCedula">No. de Cédula *</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre" placeholder="Nombres" required>
                            <label for="txtNombre">Nombres y Apellidos *</label>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDireccion" name="txtDireccion" placeholder="Dirección">
                            <label for="txtDireccion">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtTelefono" name="txtTelefono" placeholder="Teléfono">
                            <label for="txtTelefono">Teléfono</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtOcupacion" name="txtOcupacion" placeholder="Ocupación">
                            <label for="txtOcupacion">Ocupación</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboComision" name="CboComision">
                                <option value="">-- Ninguna / Sin Comisión --</option>
                                <?php foreach($comisiones as $com): ?>
                                    <option value="<?php echo $com['id_comision']; ?>"><?php echo $com['nombre_comision']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="CboComision">Comisión de Trabajo</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="txtFechnac" name="txtFechnac" required>
                            <label for="txtFechnac">Fecha de Nacimiento *</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtEdad" name="txtEdad" placeholder="Edad" readonly>
                            <label for="txtEdad">Edad</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboLider" name="CboLider" required>
                                <option value="" selected disabled>Seleccione Líder</option>
                                <?php foreach($lideres as $lider): ?>
                                    <option value="<?php echo $lider['ced_lider']; ?>"><?php echo $lider['nom_lider']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="CboLider">Líder que Refiere *</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=jac/index" class="btn btn-secondary">Cancelar</a>
                    <button type="button" id="btn_guardar" class="btn btn-primary">
                        <i class="bi bi-save"></i> Registrar Afiliado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    // Calcular edad automáticamente
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

    $('#btn_guardar').click(function() {
        if($("#txtCedula").val() === "" || $("#txtNombre").val() === "" || $("#CboComision").val() === null) {
            Swal.fire({ icon: 'warning', title: 'Atención', text: 'Por favor complete los campos obligatorios.' });
            return;
        }

        $.ajax({
            url: "index.php?url=jac/store",
            method: "POST",
            data: $('#formJac').serialize(),
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
