<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-house-door"></i>
        <h1>Listado de Afiliados JAC</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Listado</li>
            <li class="breadcrumb-item active" aria-current="page">Afiliados JAC</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-end mb-3 container-fluid">
    <a href="index.php?url=jac/create" class="btn btn-primary me-2 shadow-sm">
        <i class="bi bi-plus-lg"></i> Nuevo Afiliado
    </a>
    <button class="btn btn-outline-danger shadow-sm" id="btn_eliminar_masivo">
        <i class="bi bi-trash"></i> Eliminar
    </button>
</div>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaJac" class="table table-hover table-bordered" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Comisión</th>
                            <th>Teléfono</th>
                            <th>Líder</th>
                            <th class="text-center">Acciones</th>
                            <th width="30" class="text-center"><input type="checkbox" id="checkTodos" class="form-check-input"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<script>
$(document).ready(function() {
    var table = $('#tablaJac').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "index.php?url=jac/ajaxListar",
            "type": "POST"
        },
        "columns": [
            { "data": 0 },
            { "data": 1 },
            { "data": 2 },
            { "data": 3 },
            { "data": 4 },
            { "data": 5 },
            { "data": 6, "orderable": false },
            { "data": 7, "orderable": false }
        ],
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
        "columnDefs": [ { "orderable": false, "targets": [6,7] } ]
    });

    $("#checkTodos").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $('#btn_eliminar_masivo').click(function() {
        var id = [];
        $('.delete_item:checked').each(function(i) {
            id[i] = $(this).val();
        });

        if(id.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Atención', text: 'Seleccione al menos un registro.' });
        } else {
            Swal.fire({
                title: '¿Está seguro?',
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
                        url: "index.php?url=jac/delete",
                        method: 'POST',
                        data: { id: id },
                        success: function(data) {
                            table.ajax.reload();
                            Swal.fire('Eliminado', 'Los registros han sido eliminados.', 'success');
                        }
                    });
                }
            });
        }
    });
});
</script>
