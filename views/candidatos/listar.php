<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-person-vcard"></i>
        <h1>Listado de Candidatos</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Listado</li>
            <li class="breadcrumb-item active" aria-current="page">Candidatos</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-end mb-3 container-fluid">
    <a href="index.php?url=candidato/create" class="btn btn-primary me-2 shadow-sm">
        <i class="bi bi-plus-lg"></i> Nuevo Candidato
    </a>
    <button class="btn btn-outline-warning me-2 shadow-sm" id="btn_toggle_estado">
        <i class="bi bi-arrow-left-right"></i> Cambiar Estado
    </button>
    <button class="btn btn-outline-danger shadow-sm" id="btn_delete">
        <i class="bi bi-trash"></i> Eliminar
    </button>
</div>

<div class="container-fluid">
    <div id="resp"></div>
    
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="lookup" class="table table-hover table-bordered" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Partido</th>
                            <th>No. de tarjetón</th>
                            <th>Candidato</th>
                            <th>Aspira A</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                            <th class="text-center"><input type="checkbox" id="checkTodos" class="form-check-input"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- AJAX content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_dashboard.php'; ?>

<!-- DataTables Scripts -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    var dataTable = $('#lookup').DataTable({
        "language": { "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json" },
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "index.php?url=candidato/ajaxListar",
            type: "post",
            error: function(){
                $(".lookup-error").html("");
                $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="9">No se encontraron datos en el servidor</th></tr></tbody>');
                $("#lookup_processing").css("display","none");
            }
        },
        "columnDefs": [ { "orderable": false, "targets": [7,8] } ],
        "order": [[ 0, "desc" ]]
    });

    // Select all
    $("#checkTodos").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    // Delete selected
    $('#btn_delete').on('click', function() {
        var id = array();
        $('.delete_student:checked').each(function(i) {
            id[i] = $(this).val();
        });
        
        if(id.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Atención', text: 'Por favor seleccione al menos un registro.' });
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
                        url: 'index.php?url=candidato/delete',
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

    // Cambiar Estado selected
    $('#btn_toggle_estado').on('click', function() {
        var id = array();
        $('.delete_student:checked').each(function(i) {
            id[i] = $(this).val();
        });
        
        if(id.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Atención', text: 'Por favor seleccione al menos un candidato.' });
        } else {
            Swal.fire({
                title: '¿Cambiar estado?',
                text: "Los candidatos activos pasarán a inactivos y viceversa.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#secondary',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'index.php?url=candidato/toggleEstado',
                        method: 'POST',
                        data: {id: id},
                        dataType: 'json',
                        success: function(response) {
                            if(response.status === 'success') {
                                Swal.fire('Actualizado!', response.message, 'success');
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
});
</script>
