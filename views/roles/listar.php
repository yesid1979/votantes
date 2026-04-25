<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-person-gear"></i>
        <h1>Listado de Roles / Tipos de Usuario</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Seguridad</li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-end mb-3 container-fluid">
    <button type="button" class="btn btn-primary shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="bi bi-plus-lg"></i> Nuevo Rol
    </button>
</div>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaRoles" class="table table-hover table-bordered" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th width="50">ID</th>
                            <th>Nombre del Rol</th>
                            <th width="150" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($roles as $rol): ?>
                        <tr>
                            <td><?php echo $rol['id']; ?></td>
                            <td><?php echo $rol['tipo']; ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info text-white btn-editar" 
                                        data-id="<?php echo $rol['id']; ?>" 
                                        data-tipo="<?php echo $rol['tipo']; ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <?php if($rol['id'] != 1): ?>
                                <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                        data-id="<?php echo $rol['id']; ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Nuevo Rol</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formCrear">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="tipo" placeholder="Nombre Rol" required>
                <label>Nombre del Rol</label>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Editar Rol</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditar">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="tipo" id="edit_tipo" placeholder="Nombre Rol" required>
                <label>Nombre del Rol</label>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_actualizar">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    $('#tablaRoles').DataTable({
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sSearch":         "Buscar:",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });

    $('#btn_guardar').click(function() {
        $.ajax({
            url: "index.php?url=rol/store",
            method: "POST",
            data: $('#formCrear').serialize(),
            dataType: "json",
            success: function(r) {
                if(r.status === 'success') {
                    location.reload();
                } else {
                    Swal.fire('Error', r.message, 'error');
                }
            }
        });
    });

    $('.btn-editar').click(function() {
        $('#edit_id').val($(this).data('id'));
        $('#edit_tipo').val($(this).data('tipo'));
        $('#modalEditar').modal('show');
    });

    $('#btn_actualizar').click(function() {
        $.ajax({
            url: "index.php?url=rol/update",
            method: "POST",
            data: $('#formEditar').serialize(),
            dataType: "json",
            success: function(r) {
                if(r.status === 'success') {
                    location.reload();
                } else {
                    Swal.fire('Error', r.message, 'error');
                }
            }
        });
    });

    $('.btn-eliminar').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar Rol?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "index.php?url=rol/delete",
                    method: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function(r) {
                        if(r.status === 'success') {
                            location.reload();
                        } else {
                            Swal.fire('Error', r.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>
