<?php include 'includes/header_dashboard.php'; ?>

<!-- Content Header -->
<div class="page-header-container">
    <div class="page-header-title">
        <i class="bi bi-eye"></i>
        <h1>Auditoría del Sistema</h1>
    </div>
    <nav aria-label="breadcrumb" class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?url=dashboard/index">Home</a></li>
            <li class="breadcrumb-item text-muted">Seguridad</li>
            <li class="breadcrumb-item active" aria-current="page">Auditoría</li>
        </ol>
    </nav>
</div>

<div class="d-flex justify-content-end mb-3 container-fluid">
    <button type="button" class="btn btn-outline-danger shadow-sm" id="btn_delete">
        <i class="bi bi-trash"></i> Eliminar Selección
    </button>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table id="lookup" class="table table-hover table-bordered" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Usuario</th>
                        <th>Nombres</th>
                        <th>Acción</th>
                        <th>Tipo</th>
                        <th>Rol</th>
                        <th>IP</th>
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
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "ajax": {
            url: "index.php?url=auditoria/ajaxListar",
            type: "POST"
        },
        "order": [[0, "desc"]]
    });

    // Seleccionar/Deseleccionar todos
    $('#checkTodos').on('click', function() {
        $('.delete_student').prop('checked', this.checked);
    });

    // Eliminar seleccionados
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
                        url: 'index.php?url=auditoria/delete',
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
});
</script>
