<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-shield-check"></i>
        <h1>Gestión de Permisos por Rol</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Seguridad</li>
            <li class="breadcrumb-item active" aria-current="page">Permisos por Rol</li>
        </ol>
    </nav>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0"><i class="bi bi-shield-check"></i> Configurar Permisos Base por Rol</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> <strong>¿Cómo funciona?</strong><br>
            - Aquí defines los permisos base que tendrán todos los usuarios de cada rol.<br>
            - Para asignar permisos específicos a un usuario, edita el usuario desde <a href="index.php?url=usuario/index">Usuarios</a>.
        </div>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="selectTipo" class="form-label">Seleccionar Rol:</label>
                <select id="selectTipo" class="form-select">
                    <option value="">-- Seleccione un rol --</option>
                    <?php foreach($tipos as $tipo): ?>
                        <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['tipo']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div id="permisosRolContainer" style="display: none;">
            <form id="formPermisosRol">
                <input type="hidden" name="id_tipo" id="id_tipo" value="">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Módulo</th>
                                <th class="text-center" style="width: 100px;">Ver</th>
                                <th class="text-center" style="width: 100px;">Crear</th>
                                <th class="text-center" style="width: 100px;">Editar</th>
                                <th class="text-center" style="width: 100px;">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="permisosRolBody">
                            <?php foreach($modulos as $modulo): ?>
                            <tr>
                                <td>
                                    <i class="bi <?php echo isset($modulo['icono']) ? $modulo['icono'] : 'bi-app'; ?>"></i> 
                                    <?php echo $modulo['nombre']; ?>
                                    <?php if(isset($modulo['grupo']) && $modulo['grupo']): ?>
                                        <span class="badge bg-secondary"><?php echo ucfirst(str_replace('_', ' ', $modulo['grupo'])); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input permiso-rol" 
                                           name="permisos[<?php echo $modulo['id_modulo']; ?>][ver]" 
                                           data-modulo="<?php echo $modulo['id_modulo']; ?>" value="1">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input permiso-rol" 
                                           name="permisos[<?php echo $modulo['id_modulo']; ?>][crear]" 
                                           data-modulo="<?php echo $modulo['id_modulo']; ?>" value="1">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input permiso-rol" 
                                           name="permisos[<?php echo $modulo['id_modulo']; ?>][editar]" 
                                           data-modulo="<?php echo $modulo['id_modulo']; ?>" value="1">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input permiso-rol" 
                                           name="permisos[<?php echo $modulo['id_modulo']; ?>][eliminar]" 
                                           data-modulo="<?php echo $modulo['id_modulo']; ?>" value="1">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-end">
                    <button type="button" id="btnGuardarRol" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Permisos del Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    $('#selectTipo').on('change', function() {
        var id_tipo = $(this).val();
        if(id_tipo) {
            $('#id_tipo').val(id_tipo);
            $('.permiso-rol').prop('checked', false);
            
            $.ajax({
                url: 'index.php?url=permiso/getPermisos&id_tipo=' + id_tipo,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        response.data.forEach(function(permiso) {
                            var modulo = permiso.id_modulo;
                            if(permiso.puede_ver == 1) $('input[name="permisos['+modulo+'][ver]"]').prop('checked', true);
                            if(permiso.puede_crear == 1) $('input[name="permisos['+modulo+'][crear]"]').prop('checked', true);
                            if(permiso.puede_editar == 1) $('input[name="permisos['+modulo+'][editar]"]').prop('checked', true);
                            if(permiso.puede_eliminar == 1) $('input[name="permisos['+modulo+'][eliminar]"]').prop('checked', true);
                        });
                    }
                    $('#permisosRolContainer').show();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudieron cargar los permisos.', 'error');
                }
            });
        } else {
            $('#permisosRolContainer').hide();
        }
    });

    $('#btnGuardarRol').on('click', function() {
        var id_tipo = $('#id_tipo').val();
        if(!id_tipo) {
            Swal.fire('Atención', 'Seleccione un rol.', 'warning');
            return;
        }

        $.ajax({
            url: 'index.php?url=permiso/guardarRol',
            method: 'POST',
            data: $('#formPermisosRol').serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire('Éxito', response.message, 'success');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Ocurrió un error al guardar.', 'error');
            }
        });
    });
});
</script>
