<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-person-plus"></i>
        <h1>Agregar Simpatizante</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=simpatizante/index">Simpatizantes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar</li>
        </ol>
    </nav>
</div>



<div class="container-fluid">
    <div id="resp"></div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus"></i> Nuevo Simpatizante</h5>
        </div>
        <div class="card-body">
            <form id="form1" name="form1" autocomplete="off">
                <h5 class="mb-3 text-muted border-bottom pb-2">Información Personal</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCed_votante" name="txtCed_votante" placeholder="Cédula" required maxlength="30">
                            <label for="txtCed_votante">No. de Cédula *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_votante" name="txtNom_votante" placeholder="Nombres" required maxlength="100">
                            <label for="txtNom_votante">Nombres y Apellidos *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_votante" name="txtDir_votante" placeholder="Dirección">
                            <label for="txtDir_votante">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtBarrio" name="txtBarrio" placeholder="Barrio">
                            <label for="txtBarrio">Barrio</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtComuna" name="txtComuna" placeholder="Comuna">
                            <label for="txtComuna">Comuna</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Email">
                            <label for="txtEmail">Email</label>
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtTel_votante" name="txtTel_votante" placeholder="Teléfono">
                            <label for="txtTel_votante">Teléfono Fijo</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCel_votante" name="txtCel_votante" placeholder="Celular">
                            <label for="txtCel_votante">Celular</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Datos Demográficos</h5>
                <div class="row g-3">
                     <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="txtFechNac" name="txtFechNac" placeholder="Fecha Nacimiento">
                            <label for="txtFechNac">Fecha Nacimiento</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtEdad" name="txtEdad" placeholder="Edad" readonly>
                            <label for="txtEdad">Edad</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" id="CboSexo" name="CboSexo">
                                <option value="" selected>Seleccione</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                            </select>
                            <label for="CboSexo">Sexo</label>
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
                            <label for="CboNum_zona">No. de Zona *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboNum_puesto" name="CboNum_puesto" required>
                                <option value="" selected disabled>Seleccione primero Zona</option>
                            </select>
                            <label for="CboNum_puesto">No. de Puesto *</label>
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
                    <a href="index.php?url=simpatizante/index" class="btn btn-secondary">Cancelar</a>
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
    // Calculo Edad
    $('#txtFechNac').change(function() {
        var dob = new Date($(this).val());
        var today = new Date();
        var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
        $('#txtEdad').val(age);
    });

    // Load Departamentos on ready
    $.ajax({
        url: "index.php?url=ajaxGeo/getDepartamentos",
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
                url: "index.php?url=ajaxGeo/getMunicipios",
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
                url: "index.php?url=ajaxGeo/getZonas",
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
                url: "index.php?url=ajaxGeo/getPuestos",
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
                url: "index.php?url=ajaxGeo/getPuestoInfo",
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
        if($("#txtCed_votante").val() === "" || $("#txtNom_votante").val() === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos vacíos',
                text: 'Por favor, complete los campos obligatorios (*).'
            });
            return;
        }

        $.ajax({
            url: "index.php?url=simpatizante/store",
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
