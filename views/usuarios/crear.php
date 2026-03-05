<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-person-plus"></i>
        <h1>Agregar Usuario</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=usuario/index">Usuarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar</li>
        </ol>
    </nav>
</div>



<div class="container-fluid">
    <div id="resp"></div> <!-- Response Container -->
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus"></i> Nuevo Usuario</h5>
        </div>
        <div class="card-body">
            <form id="form1" name="form1" autocomplete="off">
                <h5 class="mb-3 text-muted border-bottom pb-2">Información Personal</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCed_usuario" name="txtCed_usuario" placeholder="No. Cédula" required maxlength="30">
                            <label for="txtCed_usuario">No. de Cédula *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_usuario" name="txtNom_usuario" placeholder="Nombres" required maxlength="100">
                            <label for="txtNom_usuario">Nombres y Apellidos *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_usuario" name="txtDir_usuario" placeholder="Dirección">
                            <label for="txtDir_usuario">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtTel_usuario" name="txtTel_usuario" placeholder="Teléfono">
                            <label for="txtTel_usuario">Teléfono Fijo</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCel_usuario" name="txtCel_usuario" placeholder="Celular">
                            <label for="txtCel_usuario">Celular</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Datos de la Cuenta</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtUsuario" name="txtUsuario" placeholder="Usuario" required maxlength="25">
                            <label for="txtUsuario">Usuario *</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Contraseña" required maxlength="25">
                            <label for="txtPassword">Contraseña *</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Email" required>
                            <label for="txtEmail">Email *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="Cbotipo" name="Cbotipo" required>
                                <option value="" selected disabled>Seleccione una opción</option>
                                <?php foreach($tipos as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['tipo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="Cbotipo">Tipo de Usuario *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboEstado" name="CboEstado" required>
                                <option value="" selected disabled>Seleccione una opción</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            <label for="CboEstado">Estado *</label>
                        </div>
                    </div>

                    <!-- Campos exclusivos para Coordinador de Puesto -->
                    <div id="div_coordinador" style="display: none;" class="row g-3 mt-1">
                        <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Asignación de Puesto (Solo Coordinadores)</h5>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="txtDptoAsignado" name="dpto_asignada">
                                    <option value="" disabled>Seleccione Departamento</option>
                                    <!-- Se cargará por JS -->
                                </select>
                                <label for="txtDptoAsignado">Departamento Asignado</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="txtMuniAsignado" name="muni_asignada">
                                    <option value="" disabled>Seleccione Municipio</option>
                                </select>
                                <label for="txtMuniAsignado">Municipio Asignado</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="CboZonaAsignada" name="zona_asignada">
                                    <option value="">Seleccione Zona</option>
                                </select>
                                <label for="CboZonaAsignada">Zona Asignada</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="CboPuestoAsignado" name="puesto_asignado">
                                    <option value="">Seleccione Puesto</option>
                                </select>
                                <label for="CboPuestoAsignado">Puesto Asignado</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=usuario/index" class="btn btn-secondary">Cancelar</a>
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
    // Mostrar/Ocultar campos de coordinador
    $('#Cbotipo').change(function() {
        if ($(this).val() == "4") { // ID 4 = Coordinador de puesto
            $('#div_coordinador').fadeIn();
            cargarDptos();
        } else {
            $('#div_coordinador').hide();
            $('#txtDptoAsignado').val('');
            $('#txtMuniAsignado').html('<option value="">Seleccione Municipio</option>');
            $('#CboZonaAsignada').html('<option value="">Seleccione Zona</option>');
            $('#CboPuestoAsignado').html('<option value="">Seleccione Puesto</option>');
        }
    });

    function cargarDptos() {
        $.ajax({
            url: "index.php?url=ajaxgeo/getdepartamentos",
            method: "POST",
            data: { current: 'VALLE' },
            success: function(response) {
                $('#txtDptoAsignado').html(response);
                cargarMunis('VALLE');
            }
        });
    }

    function cargarMunis(dpto) {
        $.ajax({
            url: "index.php?url=ajaxgeo/getmunicipios",
            method: "POST",
            data: { departamento: dpto, current: 'CALI' },
            success: function(response) {
                $('#txtMuniAsignado').html(response);
                cargarZonas(dpto, 'CALI');
            }
        });
    }

    function cargarZonas(dpto, muni) {
        $.ajax({
            url: "index.php?url=ajaxgeo/getzonas", 
            method: "POST",
            data: { departamento: dpto, municipio: muni },
            success: function(response) {
                $('#CboZonaAsignada').html(response);
            }
        });
    }

    $('#txtDptoAsignado').change(function() {
        cargarMunis($(this).val());
    });

    $('#txtMuniAsignado').change(function() {
        cargarZonas($('#txtDptoAsignado').val(), $(this).val());
    });

    $('#CboZonaAsignada').change(function() {
        var zona = $(this).val();
        var dpto = $('#txtDptoAsignado').val();
        var muni = $('#txtMuniAsignado').val();
        if (zona) {
            $.ajax({
                url: "index.php?url=ajaxgeo/getpuestos",
                method: "POST",
                data: { departamento: dpto, municipio: muni, zona: zona },
                success: function(response) {
                    $('#CboPuestoAsignado').html(response);
                }
            });
        }
    });

    $('#btn_grabar').click(function() {
        if($("#txtCed_usuario").val() === "" || $("#txtNom_usuario").val() === "" || $("#txtUsuario").val() === "") {
            Swal.fire({ icon: 'warning', title: 'Campos vacíos', text: 'Por favor, complete los campos obligatorios (*).' });
            return;
        }

        $.ajax({
            url: "index.php?url=usuario/store",
            method: "POST",
            data: $('#form1').serialize(),
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: response.message })
                    .then((result) => {
                         window.location.href = "index.php?url=usuario/index";
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al procesar la solicitud.' });
            }
        });
    });
});
</script>
