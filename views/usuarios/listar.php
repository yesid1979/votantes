<?php
// Vista: views/usuarios/listar.php
// Variables disponibles: $id_tipouser (desde controller)

include 'includes/header_dashboard.php';
?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-people"></i>
        <h1>Listado de Usuarios</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Seguridad</li>
            <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="index.php?url=usuario/create" class="btn btn-primary me-2 shadow-sm">
        <i class="bi bi-plus-lg"></i> Nuevo Usuario
    </a>
    <button class="btn btn-outline-danger shadow-sm" id="btn_modal" data-bs-toggle="modal" data-bs-target="#deleteModal">
        <i class="bi bi-trash"></i> Eliminar
    </button>
</div>

<div id="resp"></div>

<!-- Table Card -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table id="lookup" class="table table-hover table-bordered" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cédula</th>
                        <th>Usuario</th>
                        <th>Nombres</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                        <th class="text-center"><input type="checkbox" id="checkTodos" /></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Seguro que deseas borrar los registros seleccionados?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btn_delete">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer Includes (Scripts) -->
<?php include 'includes/footer_dashboard.php'; ?>

<!-- Custom Script for this page -->
<script>
$(document).ready(function() {
    var dataTable = $('#lookup').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "autoWidth": false,
        "ajax":{
            url :"index.php?url=usuario/ajaxListar", // MVC Route
            type: "post",
            error: function(){ 
                $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se encontraron datos en el servidor</th></tr></tbody>');
            }
        },
        "columnDefs": [{ "orderable": false, "targets": [7,8] }]
    });

    // Delete Logic
    // Delete Logic
    $('#btn_delete').on('click', function() {
        var id = array();
        $('.delete_student:checked').each(function(i) {
            id[i] = $(this).val();		
        });
        
        if(id.length === 0) {
            Swal.fire({ title: 'Atención', text: 'Por favor seleccione al menos un registro', icon: 'warning' });
        } else {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'index.php?url=usuario/delete',
                        method: 'POST',
                        data: {id: id},
                        dataType: 'json',
                        success: function(response) {
                            if(response.status === 'success') {
                                Swal.fire('Eliminado!', response.message, 'success');
                                dataTable.ajax.reload();
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function() {
                             Swal.fire('Error!', 'Ocurrió un error en el servidor.', 'error');
                        }
                    });
                }
            });
        }
    });
    
    $("#checkTodos").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
});
</script>
