<?php
// Vista: views/simpatizantes/listar.php
include 'includes/header_dashboard.php';
?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-emoji-smile"></i>
        <h1>Listado de Simpatizantes</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Listado</li>
            <li class="breadcrumb-item active" aria-current="page">Simpatizantes</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="index.php?url=simpatizante/create" class="btn btn-primary me-2 shadow-sm">
        <i class="bi bi-plus-lg"></i> Nuevo Simpatizante
    </a>
    <button class="btn btn-outline-danger shadow-sm" id="btn_delete">
        <i class="bi bi-trash"></i> Eliminar
    </button>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table id="lookup" class="table table-hover table-bordered" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Barrio</th>
                        <th>Comuna</th>
                        <th>Celular</th>
                        <th class="text-center">Acciones</th>
                        <th class="text-center"><input type="checkbox" id="checkTodos" /></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

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
            url :"index.php?url=simpatizante/ajaxListar", 
            type: "post",
            error: function(){ 
                $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se encontraron datos en el servidor</th></tr></tbody>');
            }
        },
        "columnDefs": [{ "orderable": false, "targets": [7,8] }]
    });

    $('#btn_delete').on('click', function() {
        var id = [];
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
                        url: 'index.php?url=simpatizante/delete',
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
