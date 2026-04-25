<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-person-plus"></i>
        <h1>Agregar Votante</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=votante/index">Votantes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar</li>
        </ol>
    </nav>
</div>



<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus"></i> Nuevo Votante</h5>
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
                            <input type="text" class="form-control" id="txtBarrio_votante" name="txtBarrio_votante" placeholder="Barrio">
                            <label for="txtBarrio_votante">Barrio</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtComuna_votante" name="txtComuna_votante" placeholder="Comuna">
                            <label for="txtComuna_votante">Comuna</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Contacto y Líder</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="txtEmail_votante" name="txtEmail_votante" placeholder="Email">
                            <label for="txtEmail_votante">Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCel_votante" name="txtCel_votante" placeholder="Celular">
                            <label for="txtCel_votante">Celular</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <select class="form-select" id="CboLider" name="CboLider" required>
                                <option value="" disabled <?php echo !isset($_SESSION['cedula']) ? 'selected' : ''; ?>>Seleccione un Líder</option>
                                <?php foreach($lideres as $lider): ?>
                                    <option value="<?php echo $lider['ced_lider']; ?>" <?php echo (isset($_SESSION['cedula']) && $lider['ced_lider'] == $_SESSION['cedula']) ? 'selected' : ''; ?>>
                                        <?php echo $lider['nom_lider']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="CboLider">Líder Asignado *</label>
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
                    <a href="index.php?url=votante/index" class="btn btn-secondary">Cancelar</a>
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
        if($("#txtCed_votante").val() === "" || $("#txtNom_votante").val() === "" || $("#CboLider").val() === null) {
            Swal.fire({ icon: 'warning', title: 'Campos vacíos', text: 'Por favor, complete los campos obligatorios (*).' });
            return;
        }

        $.ajax({
            url: "index.php?url=votante/store",
            method: "POST",
            data: $('#form1').serialize(),
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: response.message })
                    .then((result) => {
                         $('#form1')[0].reset();
                    });
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
