<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi <?php echo ($tab == 'permisos') ? 'bi-shield-lock' : 'bi-person-gear'; ?>"></i>
        <h1><?php echo ($tab == 'permisos') ? 'Gestionar Permisos' : 'Editar Usuario'; ?></h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?url=usuario/index">Usuarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo ($tab == 'permisos') ? 'Permisos' : 'Editar'; ?>
            </li>
        </ol>
    </nav>
</div>



<div class="container-fluid">
    <div id="resp"></div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <?php if($tab == 'permisos'): ?>
                    <i class="bi bi-shield-lock"></i> Gestionar Permisos: <?php echo $usuario['nombre']; ?>
                <?php else: ?>
                    <i class="bi bi-person-gear"></i> Editar Usuario: <?php echo $usuario['nombre']; ?>
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body">
            <form id="form1" name="form1" autocomplete="off">
                <!-- Hidden ID -->
                <input type="hidden" name="txtId" value="<?php echo $usuario['id']; ?>">

                <?php if($tab != 'permisos'): ?>
                <!-- SECCIÓN DE DATOS PERSONALES -->
                <h5 class="mb-3 text-muted border-bottom pb-2">Información Personal</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCed_usuario" name="txtCed_usuario" value="<?php echo $usuario['ced_usuario']; ?>" required maxlength="30">
                            <label for="txtCed_usuario">No. de Cédula *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtNom_usuario" name="txtNom_usuario" value="<?php echo $usuario['nombre']; ?>" required maxlength="100">
                            <label for="txtNom_usuario">Nombres y Apellidos *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtDir_usuario" name="txtDir_usuario" value="<?php echo $usuario['dir_usuario']; ?>">
                            <label for="txtDir_usuario">Dirección</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtTel_usuario" name="txtTel_usuario" value="<?php echo $usuario['tel_usuario']; ?>">
                            <label for="txtTel_usuario">Teléfono Fijo</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtCel_usuario" name="txtCel_usuario" value="<?php echo $usuario['cel_usuario']; ?>">
                            <label for="txtCel_usuario">Celular</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-muted border-bottom pb-2">Datos de la Cuenta</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="txtUsuario" name="txtUsuario" value="<?php echo $usuario['usuario']; ?>" required maxlength="25">
                            <label for="txtUsuario">Usuario *</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Dejar vacía para mantener">
                            <label for="txtPassword">Nueva Contraseña (Opcional)</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="<?php echo $usuario['correo']; ?>" required>
                            <label for="txtEmail">Email *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="Cbotipo" name="Cbotipo" required>
                                <option value="" disabled>Seleccione una opción</option>
                                <?php foreach($tipos as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>" <?php if($usuario['id_tipo'] == $tipo['id']) echo 'selected'; ?>>
                                        <?php echo $tipo['tipo']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="Cbotipo">Tipo de Usuario *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="CboEstado" name="CboEstado" required>
                                <option value="" disabled>Seleccione una opción</option>
                                <option value="1" <?php if($usuario['activacion'] == '1') echo 'selected'; ?>>Activo</option>
                                <option value="0" <?php if($usuario['activacion'] == '0') echo 'selected'; ?>>Inactivo</option>
                            </select>
                            <label for="CboEstado">Estado *</label>
                        </div>
                    </div>

                    <!-- Campos exclusivos para Coordinador de Puesto -->
                    <div id="div_coordinador" style="display: <?php echo ($usuario['id_tipo'] == '4') ? 'block' : 'none'; ?>;" class="row g-3 mt-1">
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
                <?php endif; ?>

                <?php if($tab == 'permisos'): ?>
                <!-- Hidden inputs to maintain user data while editing permissions -->
                <input type="hidden" name="tab" value="permisos">
                <input type="hidden" id="txtCed_usuario" name="txtCed_usuario" value="<?php echo $usuario['ced_usuario']; ?>">
                <input type="hidden" id="txtNom_usuario" name="txtNom_usuario" value="<?php echo $usuario['nombre']; ?>">
                <input type="hidden" id="txtDir_usuario" name="txtDir_usuario" value="<?php echo $usuario['dir_usuario']; ?>">
                <input type="hidden" id="txtTel_usuario" name="txtTel_usuario" value="<?php echo $usuario['tel_usuario']; ?>">
                <input type="hidden" id="txtCel_usuario" name="txtCel_usuario" value="<?php echo $usuario['cel_usuario']; ?>">
                <input type="hidden" id="txtUsuario" name="txtUsuario" value="<?php echo $usuario['usuario']; ?>">
                <input type="hidden" id="txtEmail" name="txtEmail" value="<?php echo $usuario['correo']; ?>">
                <input type="hidden" id="Cbotipo" name="Cbotipo" value="<?php echo $usuario['id_tipo']; ?>">
                <input type="hidden" id="CboEstado" name="CboEstado" value="<?php echo $usuario['activacion']; ?>">

                <!-- SECCIÓN DE PERMISOS (SOLO SE MUESTRA SI TAB=PERMISOS) -->
                <div class="alert alert-warning py-2 mt-2">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Importante:</strong> Si no marcas ningún permiso para un módulo, el usuario usará los permisos por defecto de su rol (<?php echo $usuario['id_tipo'] == 1 ? 'Administrador' : ($usuario['id_tipo'] == 2 ? 'Digitador' : 'Líder'); ?>).
                </div>
                
                <?php 
                // 1. Cargar permisos del ROL para mostrar como "sugeridos"
                $permisosRolRaw = $this->permisoModel->getPermisosByTipo($usuario['id_tipo']);
                $permisosRol = [];
                foreach($permisosRolRaw as $pr) {
                    $permisosRol[$pr['id_modulo']] = $pr;
                }

                // 2. Cargar permisos personalizados del USUARIO
                $permisosActuales = [];
                $tienePersonalizados = false;
                if(isset($permisosUsuario) && is_array($permisosUsuario) && count($permisosUsuario) > 0) {
                    $tienePersonalizados = true;
                    foreach($permisosUsuario as $p) {
                        $permisosActuales[$p['id_modulo']] = $p;
                    }
                }
                ?>
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Módulo</th>
                                <th class="text-center">Ver</th>
                                <th class="text-center">Crear</th>
                                <th class="text-center">Editar</th>
                                <th class="text-center">Eliminar</th>
                                <th class="text-center">Ver Todo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($listaModulosPermisos) && is_array($listaModulosPermisos)): ?>
                            <?php foreach($listaModulosPermisos as $modulo): 
                                $id_m = $modulo['id_modulo'];
                                
                                // Determinar estado de cada checkbox
                                $ver = 0; $crear = 0; $editar = 0; $eliminar = 0;
                                
                                if ($tienePersonalizados) {
                                    // Si hay personalizados, mandan ellos
                                    $ver = isset($permisosActuales[$id_m]) ? $permisosActuales[$id_m]['puede_ver'] : 0;
                                    $crear = isset($permisosActuales[$id_m]) ? $permisosActuales[$id_m]['puede_crear'] : 0;
                                    $editar = isset($permisosActuales[$id_m]) ? $permisosActuales[$id_m]['puede_editar'] : 0;
                                    $eliminar = isset($permisosActuales[$id_m]) ? $permisosActuales[$id_m]['puede_eliminar'] : 0;
                                    $ver_todo = isset($permisosActuales[$id_m]) ? $permisosActuales[$id_m]['puede_ver_todo'] : 0;
                                } else {
                                    // Si no hay personalizados, mostramos los del ROL por defecto
                                    $ver = isset($permisosRol[$id_m]) ? $permisosRol[$id_m]['puede_ver'] : 0;
                                    $crear = isset($permisosRol[$id_m]) ? $permisosRol[$id_m]['puede_crear'] : 0;
                                    $editar = isset($permisosRol[$id_m]) ? $permisosRol[$id_m]['puede_editar'] : 0;
                                    $eliminar = isset($permisosRol[$id_m]) ? $permisosRol[$id_m]['puede_eliminar'] : 0;
                                    $ver_todo = isset($permisosRol[$id_m]) ? $permisosRol[$id_m]['puede_ver_todo'] : 0;
                                }
                            ?>
                            <tr>
                                <td class="fw-bold"><i class="bi <?php echo $modulo['icono']; ?> text-primary"></i> <?php echo $modulo['nombre']; ?></td>
                                <td class="text-center"><input type="checkbox" class="form-check-input" name="permisos[<?php echo $id_m; ?>][ver]" value="1" <?php echo ($ver) ? 'checked' : ''; ?>></td>
                                <td class="text-center"><input type="checkbox" class="form-check-input" name="permisos[<?php echo $id_m; ?>][crear]" value="1" <?php echo ($crear) ? 'checked' : ''; ?>></td>
                                <td class="text-center"><input type="checkbox" class="form-check-input" name="permisos[<?php echo $id_m; ?>][editar]" value="1" <?php echo ($editar) ? 'checked' : ''; ?>></td>
                                <td class="text-center"><input type="checkbox" class="form-check-input" name="permisos[<?php echo $id_m; ?>][eliminar]" value="1" <?php echo ($eliminar) ? 'checked' : ''; ?>></td>
                                <td class="text-center">
                                    <?php if($modulo['nombre'] == 'Votantes'): ?>
                                    <input type="checkbox" class="form-check-input" name="permisos[<?php echo $id_m; ?>][ver_todo]" value="1" <?php echo ($ver_todo) ? 'checked' : ''; ?>>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <div class="mt-4 text-end">
                    <a href="index.php?url=usuario/index" class="btn btn-secondary">Cancelar</a>
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
    // Valores actuales (si es coordinador)
    var dptoActual = "<?php echo isset($usuario['dpto_asignado']) ? $usuario['dpto_asignado'] : ''; ?>";
    var muniActual = "<?php echo isset($usuario['muni_assigned']) ? $usuario['muni_assigned'] : (isset($usuario['muni_asignado']) ? $usuario['muni_asignado'] : ''); ?>";
    var zonaActual = "<?php echo isset($usuario['zona_asignada']) ? $usuario['zona_asignada'] : ''; ?>";
    var puestoActual = "<?php echo isset($usuario['puesto_asignado']) ? $usuario['puesto_asignado'] : ''; ?>";

    if ($('#Cbotipo').val() == "4") {
        cargarDptos(dptoActual, muniActual, zonaActual, puestoActual);
    }

    // Mostrar/Ocultar campos de coordinador
    $('#Cbotipo').change(function() {
        if ($(this).val() == "4") {
            $('#div_coordinador').fadeIn();
            cargarDptos('VALLE', 'CALI', '', '');
        } else {
            $('#div_coordinador').hide();
            $('#txtDptoAsignado').val('');
            $('#txtMuniAsignado').html('<option value="">Seleccione Municipio</option>');
            $('#CboZonaAsignada').html('<option value="">Seleccione Zona</option>');
            $('#CboPuestoAsignado').html('<option value="">Seleccione Puesto</option>');
        }
    });

    function cargarDptos(selectedDpto, selectedMuni, selectedZona, selectedPuesto) {
        $.ajax({
            url: "index.php?url=ajaxgeo/getdepartamentos",
            method: "POST",
            data: { current: selectedDpto },
            success: function(response) {
                $('#txtDptoAsignado').html(response);
                if (selectedDpto) {
                    cargarMunis(selectedDpto, selectedMuni, selectedZona, selectedPuesto);
                }
            }
        });
    }

    function cargarMunis(dpto, selectedMuni, selectedZona, selectedPuesto) {
        $.ajax({
            url: "index.php?url=ajaxgeo/getmunicipios",
            method: "POST",
            data: { departamento: dpto, current: selectedMuni },
            success: function(response) {
                $('#txtMuniAsignado').html(response);
                if (selectedMuni) {
                    cargarZonas(dpto, selectedMuni, selectedZona, selectedPuesto);
                }
            }
        });
    }

    function cargarZonas(dpto, muni, selectedZona, selectedPuesto) {
        $.ajax({
            url: "index.php?url=ajaxgeo/getzonas",
            method: "POST",
            data: { departamento: dpto, municipio: muni, current: selectedZona },
            success: function(response) {
                $('#CboZonaAsignada').html(response);
                if (selectedZona) {
                    cargarPuestos(dpto, muni, selectedZona, selectedPuesto);
                }
            }
        });
    }

    function cargarPuestos(dpto, muni, zona, selectedPuesto) {
        $.ajax({
            url: "index.php?url=ajaxgeo/getpuestos",
            method: "POST",
            data: { departamento: dpto, municipio: muni, zona: zona, current: selectedPuesto },
            success: function(response) {
                $('#CboPuestoAsignado').html(response);
            }
        });
    }

    $('#txtDptoAsignado').change(function() {
        cargarMunis($(this).val(), '', '', '');
    });

    $('#txtMuniAsignado').change(function() {
        cargarZonas($('#txtDptoAsignado').val(), $(this).val(), '', '');
    });

    $('#CboZonaAsignada').change(function() {
        cargarPuestos($('#txtDptoAsignado').val(), $('#txtMuniAsignado').val(), $(this).val(), '');
    });

    $('#btn_update').click(function() {
        if($("#txtNom_usuario").val() === "" || $("#txtUsuario").val() === "") {
             Swal.fire({ icon: 'warning', title: 'Campos vacíos', text: 'Complete los campos obligatorios.' });
             return;
        }

        $.ajax({
            url: "index.php?url=usuario/update",
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
            error: function(xhr, status, error) {
                console.log('Error:', xhr.responseText);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error: ' + error + '. Ver consola para detalles.' });
            }
        });
    });
});
</script>
