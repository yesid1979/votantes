<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-person-plus"></i>
        <h1>Agregar Líder</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=lider/index">Líderes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar</li>
        </ol>
    </nav>
</div>



<div class="container-fluid">
    <div id="resp"></div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus"></i> Nuevo Líder</h5>
        </div>
        <div class="card-body">
            <form id="form1" name="form1" autocomplete="off">
                <h5 class="mb-3 text-muted border-bottom pb-2">Información Personal</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCed_lider" name="txtCed_lider" placeholder="Cédula" required maxlength="30">
                            <label for="txtCed_lider">No. de Cédula *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_lider" name="txtNom_lider" placeholder="Nombres" required maxlength="100">
                            <label for="txtNom_lider">Nombres y Apellidos *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_lider" name="txtDir_lider" placeholder="Dirección">
                            <label for="txtDir_lider">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtBarrio_lider" name="txtBarrio_lider" placeholder="Barrio">
                            <label for="txtBarrio_lider">Barrio</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtComuna_lider" name="txtComuna_lider" placeholder="Comuna">
                            <label for="txtComuna_lider">Comuna</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Datos de Contacto</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="txtEmail_lider" name="txtEmail_lider" placeholder="Email">
                            <label for="txtEmail_lider">Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCel_lider" name="txtCel_lider" placeholder="Celular">
                            <label for="txtCel_lider">Celular</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Información de Votación</h5>
                 <div class="row g-3">
                     <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboNum_dpto" name="CboNum_dpto" required>
                                <option value="" selected disabled>Cargando...</option>
                            </select>
                            <label for="CboNum_dpto">Departamento *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboNum_muni" name="CboNum_muni" required>
                                <option value="" selected disabled>Seleccione primero Departamento</option>
                            </select>
                            <label for="CboNum_muni">Municipio *</label>
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboNum_zona" name="CboNum_zona" required>
                                <option value="" selected disabled>Seleccione primero Municipio</option>
                            </select>
                            <label for="CboNum_zona">No. de Zona</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboNum_puesto" name="CboNum_puesto" required>
                                <option value="" selected disabled>Seleccione primero Zona</option>
                            </select>
                            <label for="CboNum_puesto">No. de Puesto</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                         <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_puesto" name="txtNom_puesto" placeholder="Nombre Puesto" readonly>
                            <label for="txtNom_puesto">Nombre Puesto</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                         <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_puesto" name="txtDir_puesto" placeholder="Dirección Puesto" readonly>
                            <label for="txtDir_puesto">Dirección Puesto</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNum_mesa" name="txtNum_mesa" placeholder="Mesa">
                            <label for="txtNum_mesa">No. Mesa</label>
                        </div>
                    </div>
                 </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=lider/index" class="btn btn-secondary">Cancelar</a>
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
    // Load Departamentos on ready
    $.ajax({
        url: "index.php?url=ajaxgeo/getDepartamentos",
        method: "POST",
        success: function(html) {
            $('#CboNum_dpto').html(html);
            $('#CboNum_dpto').trigger('change');
        }
    });

    // Dpto Change -> Cargar Municipios
    $('#CboNum_dpto').change(function() {
        var dpto = $(this).val();
        if(dpto) {
            $.ajax({
                url: "index.php?url=ajaxgeo/getMunicipios",
                method: "POST",
                data: { departamento: dpto },
                success: function(html) {
                    $('#CboNum_muni').html(html);
                    $('#CboNum_muni').trigger('change');
                }
            });
        }
    });

    // Muni Change -> Cargar Zonas
    $('#CboNum_muni').change(function() {
        var dpto = $('#CboNum_dpto').val();
        var muni = $(this).val();
        if(dpto && muni) {
            $.ajax({
                url: "index.php?url=ajaxgeo/getZonas",
                method: "POST",
                data: { departamento: dpto, municipio: muni },
                success: function(html) {
                    $('#CboNum_zona').html(html);
                    $('#CboNum_zona').trigger('change');
                }
            });
        }
    });

    // Zona Change -> Cargar Puestos
    $('#CboNum_zona').change(function() {
        var dpto = $('#CboNum_dpto').val();
        var muni = $('#CboNum_muni').val();
        var zona = $(this).val();
        if(dpto && muni && zona) {
            $.ajax({
                url: "index.php?url=ajaxgeo/getPuestos",
                method: "POST",
                data: { departamento: dpto, municipio: muni, zona: zona },
                success: function(html) {
                    $('#CboNum_puesto').html(html);
                    $('#txtNom_puesto').val("");
                    $('#txtDir_puesto').val("");
                }
            });
        }
    });

    // Puesto Change -> Cargar Info Puesto
    $('#CboNum_puesto').change(function() {
        var dpto = $('#CboNum_dpto').val();
        var muni = $('#CboNum_muni').val();
        var zona = $('#CboNum_zona').val();
        var puesto = $(this).val();
        if(dpto && muni && zona && puesto) {
             $.ajax({
                url: "index.php?url=ajaxgeo/getPuestoInfo",
                method: "POST",
                dataType: "json",
                data: { departamento: dpto, municipio: muni, zona: zona, puesto: puesto },
                success: function(data) {
                    $('#txtNom_puesto').val(data.nom_puesto);
                    $('#txtDir_puesto').val(data.dir_puesto);
                }
            });
        }
    });

    $('#btn_grabar').click(function() {
        if($("#txtCed_lider").val() === "" || $("#txtNom_lider").val() === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos vacíos',
                text: 'Por favor, complete los campos obligatorios (*).'
            });
            return;
        }

        $.ajax({
            url: "index.php?url=lider/store",
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
