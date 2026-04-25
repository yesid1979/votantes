<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-diagram-3"></i>
        <h1>Listado de Comisiones JAC</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Listado</li>
            <li class="breadcrumb-item active" aria-current="page">Comisiones JAC</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-end mb-3 container-fluid">
    <button type="button" class="btn btn-primary me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="bi bi-plus-lg"></i> Nueva Comisión
    </button>
    <button class="btn btn-outline-danger shadow-sm" id="btn_eliminar_masivo">
        <i class="bi bi-trash"></i> Eliminar
    </button>
</div>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaComisiones" class="table table-hover table-bordered" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th width="50">ID</th>
                            <th>Nombre de la Comisión</th>
                            <th width="120" class="text-center">Acciones</th>
                            <th width="30" class="text-center"><input type="checkbox" id="checkTodos" class="form-check-input"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($comisiones as $com): ?>
                        <tr>
                            <td><?php echo $com['id_comision']; ?></td>
                            <td><?php echo $com['nombre_comision']; ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info text-white btn-editar" 
                                        data-id="<?php echo $com['id_comision']; ?>" 
                                        data-nombre="<?php echo $com['nombre_comision']; ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="item_id[]" class="delete_item form-check-input" value="<?php echo $com['id_comision']; ?>">
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
        <h5 class="modal-title">Nueva Comisión</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formCrear">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="nombre_comision" placeholder="Nombre" required>
                <label>Nombre de la Comisión</label>
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
        <h5 class="modal-title">Editar Comisión</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditar">
            <input type="hidden" name="id_comision" id="edit_id">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="nombre_comision" id="edit_nombre" placeholder="Nombre" required>
                <label>Nombre de la Comisión</label>
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
    var table = $('#tablaComisiones').DataTable({
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sSearch":         "Buscar:",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "columnDefs": [ { "orderable": false, "targets": [2,3] } ]
    });

    $("#checkTodos").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $('#btn_guardar').click(function() {
        $.ajax({
            url: "index.php?url=comision/store",
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
        $('#edit_nombre').val($(this).data('nombre'));
        $('#modalEditar').modal('show');
    });

    $('#btn_actualizar').click(function() {
        $.ajax({
            url: "index.php?url=comision/update",
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

    $('#btn_eliminar_masivo').click(function() {
        var id = [];
        $('.delete_item:checked').each(function(i) {
            id[i] = $(this).val();
        });

        if(id.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Atención', text: 'Seleccione al menos un registro.' });
            return;
        }

        Swal.fire({
            title: '¿Eliminar seleccionados?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Para mantenerlo simple, eliminamos de a uno o podrías ajustar el controller para recibir array
                // Por ahora usamos el loop para mantener compatibilidad con tu delete actual
                id.forEach(function(currentId) {
                    $.ajax({
                        url: "index.php?url=comision/delete",
                        method: "POST",
                        data: { id: currentId },
                        dataType: "json",
                        success: function(r) {
                             // Recargar al final del bucle
                        }
                    });
                });
                setTimeout(function(){ location.reload(); }, 1000);
            }
        });
    });
});
</script>
