<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-person-circle"></i>
        <h1>Mi Perfil</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Cuenta</li>
            <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <div id="resp"></div>

    <div class="row g-4">
        <!-- Personal Information Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-person-lines-fill"></i> Información Personal</h5>
                </div>
                <div class="card-body">
                    <form id="formPersonal" autocomplete="off">
                        <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="cedula_up" name="cedula_up" value="<?php echo $usuario['ced_usuario']; ?>" required readonly>
                                    <label for="cedula_up">Cédula (No editable)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nombre_up" name="nombre_up" value="<?php echo $usuario['nombre']; ?>" required maxlength="50">
                                    <label for="nombre_up">Nombre Completo</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email_up" name="email_up" value="<?php echo $usuario['correo']; ?>" required maxlength="50">
                                    <label for="email_up">Correo Electrónico</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="telefono_up" name="telefono_up" value="<?php echo $usuario['tel_usuario']; ?>" maxlength="15">
                                    <label for="telefono_up">Teléfono Fijo</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="cel_up" name="cel_up" value="<?php echo $usuario['cel_usuario']; ?>" maxlength="15">
                                    <label for="cel_up">Celular</label>
                                </div>
                            </div>
                             <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Dirección" id="direccion_up" name="direccion_up" style="height: 100px"><?php echo $usuario['dir_usuario']; ?></textarea>
                                    <label for="direccion_up">Dirección</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account & Security Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-shield-lock"></i> Datos de Cuenta</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Usuario</label>
                        <p class="fw-bold fs-5"><?php echo $usuario['usuario']; ?></p>
                    </div>
                     <div class="mb-3">
                        <label class="form-label text-muted">Tipo de Rol</label>
                        <p><span class="badge bg-secondary"><?php echo $usuario['tipo']; ?></span></p>
                    </div>
                     <div class="mb-3">
                        <label class="form-label text-muted">Último Acceso</label>
                        <p class="small"><?php echo $usuario['last_session']; ?></p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                 <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-key"></i> Contraseña</h5>
                </div>
                <div class="card-body">
                     <form id="formPassword" autocomplete="off">
                        <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                        
                        <div class="form-floating mb-3">
                             <input type="password" class="form-control" id="newPassword1_up" name="newPassword1_up" placeholder="Nueva Contraseña" required>
                             <label for="newPassword1_up">Nueva Contraseña</label>
                        </div>
                        <div class="form-floating mb-3">
                             <input type="password" class="form-control" id="newPassword2_up" name="newPassword2_up" placeholder="Repetir Contraseña" required>
                             <label for="newPassword2_up">Repetir Contraseña</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-arrow-repeat"></i> Cambiar Contraseña
                            </button>
                        </div>
                     </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mostrar Votantes si es Líder -->
    <?php if(isset($usuario['id_tipo']) && $usuario['id_tipo'] == 3): ?>
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="bi bi-people"></i> Mis Votantes Asignados</h5>
                </div>
                <div class="card-body">
                    <input type="hidden" id="mi_cedula_lider" value="<?php echo $usuario['ced_usuario']; ?>">
                    <div class="table-responsive">
                        <table id="lookup_mis_votantes" class="table table-hover table-bordered" style="width:100%">
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
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    // Update Personal Info
    $('#formPersonal').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: "index.php?url=usuario/updatePerfil",
            method: "POST",
            data: $('#formPersonal').serialize(),
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: response.message });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al procesar la solicitud.' });
            }
        });
    });

    // Update Password
    $('#formPassword').on('submit', function(event) {
        event.preventDefault();
        
        var p1 = $('#newPassword1_up').val();
        var p2 = $('#newPassword2_up').val();
        
        if(p1.length < 4) {
             Swal.fire({ icon: 'warning', title: 'Contraseña corta', text: 'La contraseña debe tener al menos 4 caracteres.' });
             return;
        }

        if(p1 !== p2) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Las nuevas contraseñas no coinciden.' });
            return;
        }

        $.ajax({
            url: "index.php?url=usuario/changePassword",
            method: "POST",
            data: $('#formPassword').serialize(),
            dataType: "json",
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: response.message });
                    $('#formPassword')[0].reset();
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al procesar la solicitud.' });
            }
        });
    });

    <?php if(isset($usuario['id_tipo']) && $usuario['id_tipo'] == 3): ?>
    var miCedula = $("#mi_cedula_lider").val();
    var dataTableMisVotantes = $('#lookup_mis_votantes').DataTable({
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
                d.ced_lider_filter = miCedula;
            },
            error: function(){ 
                $("#lookup_mis_votantes").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se encontraron datos en el servidor</th></tr></tbody>');
            }
        },
        "columnDefs": [{ "orderable": false, "targets": [9,10] }]
    });
    <?php endif; ?>

});
</script>
