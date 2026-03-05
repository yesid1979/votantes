<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-pencil-square"></i>
        <h1>Editar Líder</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=lider/index">Líderes</a></li>
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
                <!-- Hidden ID -->
                <input type="hidden" name="txtId" value="<?php echo $lider['id_lider']; ?>">

                <h5 class="mb-3 text-muted border-bottom pb-2">Información Personal</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCed_lider" name="txtCed_lider" value="<?php echo $lider['ced_lider']; ?>" readonly>
                            <label for="txtCed_lider">No. de Cédula</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_lider" name="txtNom_lider" value="<?php echo $lider['nom_lider']; ?>" required maxlength="100">
                            <label for="txtNom_lider">Nombres y Apellidos *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_lider" name="txtDir_lider" value="<?php echo $lider['dir_lider']; ?>" placeholder="Dirección">
                            <label for="txtDir_lider">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtBarrio_lider" name="txtBarrio_lider" value="<?php echo $lider['barrio_lider']; ?>" placeholder="Barrio">
                            <label for="txtBarrio_lider">Barrio</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtComuna_lider" name="txtComuna_lider" value="<?php echo $lider['comuna_lider']; ?>" placeholder="Comuna">
                            <label for="txtComuna_lider">Comuna</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Datos de Contacto</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="txtEmail_lider" name="txtEmail_lider" value="<?php echo $lider['email_lider']; ?>" placeholder="Email">
                            <label for="txtEmail_lider">Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCel_lider" name="txtCel_lider" value="<?php echo $lider['cel_lider']; ?>" placeholder="Celular">
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
                            <label for="CboNum_zona">No. de Zona</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboNum_puesto" name="CboNum_puesto" required>
                                <option value="" selected disabled>Seleccione Puesto</option>
                            </select>
                            <label for="CboNum_puesto">No. de Puesto</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                         <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_puesto" name="txtNom_puesto" value="<?php echo isset($lider['nom_puestol']) ? $lider['nom_puestol'] : ''; // Assuming this might be needed but not in DB yet, actually we fetch it dynamically usually, but keeping readonly for display ?>" readonly placeholder="Nombre Puesto">
                            <label for="txtNom_puesto">Nombre Puesto (Auto)</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                         <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_puesto" name="txtDir_puesto" value="<?php echo isset($lider['dir_puestol']) ? $lider['dir_puestol'] : ''; ?>" readonly placeholder="Dirección Puesto">
                            <label for="txtDir_puesto">Dirección Puesto (Auto)</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNum_mesa" name="txtNum_mesa" value="<?php echo $lider['mesa_lider']; ?>" placeholder="Mesa">
                            <label for="txtNum_mesa">No. Mesa</label>
                        </div>
                    </div>
                 </div>

                <div class="mt-4 text-end">
                    <a href="index.php?url=lider/index" class="btn btn-secondary">Cancelar</a>
                    <button type="button" id="btn_update" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Votantes Asignados Card -->
<div class="container-fluid mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0"><i class="bi bi-people"></i> Votantes Asignados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="lookup_votantes" class="table table-hover table-bordered" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Barrio</th>
                            <th>Comuna</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Líder</th>
                            <th class="text-center">Acciones</th>
                            <th class="text-center">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    // Manejo de dependencias (Dpto, Muni, Zona, Puesto)
    var currentCargaDpto = "";
    var currentCargaMuni = "";
    var currentCargaZona = "<?php echo $lider['zona_lider']; ?>";
    var currentCargaPuesto = "<?php echo $lider['puesto_lider']; ?>";

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
        if($("#txtNom_lider").val() === "") {
             Swal.fire({ icon: 'warning', title: 'Campos vacíos', text: 'Complete los campos obligatorios.' });
             return;
        }

        $.ajax({
            url: "index.php?url=lider/update",
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

    var cedulaLider = $("#txtCed_lider").val();
    var dataTableVotantes = $('#lookup_votantes').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "autoWidth": false,
        "ajax":{
            url :"index.php?url=votante/ajaxListar", 
            type: "post",
            data: function ( d ) {
                d.ced_lider_filter = cedulaLider;
            },
            error: function(){ 
                $("#lookup_votantes").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se encontraron datos en el servidor</th></tr></tbody>');
            }
        },
        "columnDefs": [{ "orderable": false, "targets": [9,10] }]
    });

});
</script>
