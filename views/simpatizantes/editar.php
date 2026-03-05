<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-pencil-square"></i>
        <h1>Editar Simpatizante</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=simpatizante/index">Simpatizantes</a></li>
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
            <form id="form1" name="form1" autocomplete="off">
                <input type="hidden" name="id" value="<?php echo $simpatizante['id_simpatizante']; ?>">

                <h5 class="mb-3 text-muted border-bottom pb-2">Información Personal</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCed_votante" name="txtCed_votante" value="<?php echo $simpatizante['ced_simpatizante']; ?>" readonly>
                            <label for="txtCed_votante">No. de Cédula</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_votante" name="txtNom_votante" value="<?php echo $simpatizante['nom_simpatizante']; ?>" required maxlength="100">
                            <label for="txtNom_votante">Nombres y Apellidos *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_votante" name="txtDir_votante" value="<?php echo $simpatizante['dir_simpatizante']; ?>" placeholder="Dirección">
                            <label for="txtDir_votante">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtBarrio" name="txtBarrio" value="<?php echo $simpatizante['barrio_simpatizante']; ?>" placeholder="Barrio">
                            <label for="txtBarrio">Barrio</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtComuna" name="txtComuna" value="<?php echo $simpatizante['comuna_simpatizante']; ?>" placeholder="Comuna">
                            <label for="txtComuna">Comuna</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="<?php echo $simpatizante['email_simpatizante']; ?>" placeholder="Email">
                            <label for="txtEmail">Email</label>
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtTel_votante" name="txtTel_votante" value="<?php echo $simpatizante['tel_simpatizante']; ?>" placeholder="Teléfono">
                            <label for="txtTel_votante">Teléfono Fijo</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCel_votante" name="txtCel_votante" value="<?php echo $simpatizante['cel_simpatizante']; ?>" placeholder="Celular">
                            <label for="txtCel_votante">Celular</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Datos Demográficos</h5>
                <div class="row g-3">
                     <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="txtFechNac" name="txtFechNac" value="<?php echo $simpatizante['fechnac_simpatizante']; ?>">
                            <label for="txtFechNac">Fecha Nacimiento</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtEdad" name="txtEdad" value="<?php echo $simpatizante['edad_simpatizante']; ?>" readonly>
                            <label for="txtEdad">Edad</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" id="CboSexo" name="CboSexo">
                                <option value="" disabled>Seleccione</option>
                                <option value="Femenino" <?php if($simpatizante['sexo_simpatizante'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
                                <option value="Masculino" <?php if($simpatizante['sexo_simpatizante'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
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
                                <option value="" selected disabled>Seleccione Municipio</option>
                            </select>
                            <label for="CboNum_muni">Municipio *</label>
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboNum_zona" name="CboNum_zona" required>
                                <option value="" selected disabled>Seleccione Zona</option>
                            </select>
                            <label for="CboNum_zona">No. de Zona *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboNum_puesto" name="CboNum_puesto" required>
                                <option value="" selected disabled>Seleccione Puesto</option>
                            </select>
                            <label for="CboNum_puesto">No. de Puesto *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                         <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_puesto" name="txtNom_puesto" value="<?php echo $simpatizante['nom_puestov']; ?>" readonly>
                            <label for="txtNom_puesto">Nombre Puesto</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                         <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_puesto" name="txtDir_puesto" value="<?php echo $simpatizante['dir_puestov']; ?>" readonly>
                            <label for="txtDir_puesto">Dirección Puesto</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNum_mesa" name="txtNum_mesa" value="<?php echo $simpatizante['mesa_simpatizante']; ?>" placeholder="Mesa">
                            <label for="txtNum_mesa">No. Mesa</label>
                        </div>
                    </div>
                 </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=simpatizante/index" class="btn btn-secondary">Cancelar</a>
                    <button type="button" id="btn_update" class="btn btn-primary">
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
     // Calculo Edad
    $('#txtFechNac').change(function() {
        var dob = new Date($(this).val());
        var today = new Date();
        var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
        $('#txtEdad').val(age);
    });

    // Manejo de dependencias (Dpto, Muni, Zona, Puesto)
    var currentCargaDpto = "";
    var currentCargaMuni = "";
    var currentCargaZona = "<?php echo $simpatizante['zona_simpatizante']; ?>";
    var currentCargaPuesto = "<?php echo $simpatizante['puesto_simpatizante']; ?>";

    // Request reverse geo
    $.ajax({
        url: 'index.php?url=ajaxGeo/getReverseGeo',
        method: 'POST',
        data: { zona: currentCargaZona, puesto: currentCargaPuesto },
        dataType: 'json',
        success: function(data) {
            if(data.status === 'success') {
                currentCargaDpto = data.dpto;
                currentCargaMuni = data.muni;
                loadDepartamentos(currentCargaDpto);
            } else {
                loadDepartamentos("VALLE");
            }
        },
        error: function() {
            loadDepartamentos("VALLE");
        }
    });

    function loadDepartamentos(current) {
        $.ajax({
            url: "index.php?url=ajaxGeo/getDepartamentos",
            method: "POST",
            data: { current: current },
            success: function(html) {
                $('#CboNum_dpto').html(html);
                if (current) {
                    $('#CboNum_dpto').trigger('change', [true]);
                }
            }
        });
    }

    $('#CboNum_dpto').on('change', function(e, isInitial) {
        var dpto = $(this).val();
        if(dpto) {
            var curr = isInitial ? currentCargaMuni : "";
            $.ajax({
                url: "index.php?url=ajaxGeo/getMunicipios",
                method: "POST",
                data: { departamento: dpto, current: curr },
                success: function(html) {
                    $('#CboNum_muni').html(html);
                    if (isInitial && curr) {
                        $('#CboNum_muni').trigger('change', [true]);
                    } else if (!isInitial) {
                        $('#CboNum_muni').trigger('change');
                    }
                }
            });
        }
    });

    $('#CboNum_muni').on('change', function(e, isInitial) {
        var dpto = $('#CboNum_dpto').val();
        var muni = $(this).val();
        if(dpto && muni) {
            var curr = isInitial ? currentCargaZona : "";
            $.ajax({
                url: "index.php?url=ajaxGeo/getZonas",
                method: "POST",
                data: { departamento: dpto, municipio: muni, current: curr },
                success: function(html) {
                    $('#CboNum_zona').html(html);
                    if (isInitial && curr) {
                        $('#CboNum_zona').trigger('change', [true]);
                    } else if (!isInitial) {
                        $('#CboNum_zona').trigger('change');
                    }
                }
            });
        }
    });

    $('#CboNum_zona').on('change', function(e, isInitial) {
        var dpto = $('#CboNum_dpto').val();
        var muni = $('#CboNum_muni').val();
        var zona = $(this).val();
        if(dpto && muni && zona) {
            var curr = isInitial ? currentCargaPuesto : "";
            $.ajax({
                url: "index.php?url=ajaxGeo/getPuestos",
                method: "POST",
                data: { departamento: dpto, municipio: muni, zona: zona, current: curr },
                success: function(html) {
                    $('#CboNum_puesto').html(html);
                    if (isInitial && curr) {
                        $('#CboNum_puesto').trigger('change', [true]);
                    } else if (!isInitial) {
                        $('#CboNum_puesto').trigger('change');
                        $('#txtNom_puesto').val("");
                        $('#txtDir_puesto').val("");
                    }
                }
            });
        }
    });

    $('#CboNum_puesto').change(function(e, isInitial) {
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


    $('#btn_update').click(function() {
        if($("#txtNom_votante").val() === "") {
             Swal.fire({ icon: 'warning', title: 'Campos vacíos', text: 'Complete los campos obligatorios.' });
             return;
        }

        $.ajax({
            url: "index.php?url=simpatizante/update",
            method: "POST",
            data: $('#form1').serialize(),
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
