<?php
if(!isset($_SESSION)) { 
    session_start(); 
}

// Verificar sesión
if(!isset($_SESSION['id_usuario'])){
    header("Location: index.php");
    exit;
}

$tipo_usuario = $_SESSION['tipo_usuario']; // 1: Admin, 2: Digitador, 3: Lider
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sistema Votantes</title>
    
    <!-- Favicon -->
    <link href="images/favicon.png" rel="icon" type="image/png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style_modern.css">
</head>
<body class="">

<div class="dashboard-container">
    <div class="main-wrapper" id="wrapper">
        
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-box-seam me-2"></i> VOTANTES
            </div>
            <?php 
            // Cargar helper de permisos
            require_once 'funcs/permisos_helper.php';
            
            // Verificar permisos para cada módulo (Deben coincidir EXACTO con la tabla 'modulos')
            $puedeVerZonas = tienePermiso($tipo_usuario, 'Zonas', 'ver');
            $puedeVerCandidatos = tienePermiso($tipo_usuario, 'Candidatos', 'ver');
            $puedeVerSimpatizantes = tienePermiso($tipo_usuario, 'Simpatizantes', 'ver');
            $puedeVerVotantes = tienePermiso($tipo_usuario, 'Votantes', 'ver');
            $puedeVerLideres = tienePermiso($tipo_usuario, 'Líderes', 'ver');
            $puedeVerUsuarios = tienePermiso($tipo_usuario, 'Usuarios', 'ver');
            $puedeVerAuditoria = tienePermiso($tipo_usuario, 'Auditoría', 'ver');
            
            $puedeVerDashboard = tienePermiso($tipo_usuario, 'Dashboard', 'ver');
            
            $puedeVerDatosMaestros = $puedeVerZonas || $puedeVerCandidatos;
            $puedeVerSeguridad = $puedeVerUsuarios || $puedeVerAuditoria;
            ?>
            <div class="list-group list-group-flush mt-3">
                <?php if($puedeVerDashboard): ?>
                <a href="index.php?url=dashboard/index" class="list-group-item list-group-item-action <?php echo (!isset($_GET['url']) || $_GET['url'] == 'dashboard/index') ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <?php endif; ?>

                <?php if($puedeVerDatosMaestros): ?>
                <!-- Menú Datos maestros con submenú -->
                <?php 
                $datosMaestrosActive = (isset($_GET['url']) && (strpos($_GET['url'], 'zona') !== false || strpos($_GET['url'], 'candidato') !== false));
                ?>
                <a class="list-group-item list-group-item-action menu-datos d-flex justify-content-between align-items-center <?php echo $datosMaestrosActive ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" href="#submenuDatosMaestros" role="button" 
                   aria-expanded="<?php echo $datosMaestrosActive ? 'true' : 'false'; ?>" aria-controls="submenuDatosMaestros">
                    <span><i class="bi bi-database"></i> Datos maestros</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse <?php echo $datosMaestrosActive ? 'show' : ''; ?>" id="submenuDatosMaestros">
                    <?php if($puedeVerZonas): ?>
                    <a href="index.php?url=zona/index" class="list-group-item list-group-item-action ps-4 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'zona') !== false) ? 'active' : ''; ?>">
                        <i class="bi bi-geo-alt"></i> Zonas
                    </a>
                    <?php endif; ?>
                    <?php if($puedeVerCandidatos): ?>
                    <a href="index.php?url=candidato/index" class="list-group-item list-group-item-action ps-4 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'candidato') !== false) ? 'active' : ''; ?>">
                        <i class="bi bi-person-vcard"></i> Candidatos
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Separador -->
                <div class="menu-divider"></div>
                
                <?php if($puedeVerSimpatizantes): ?>
                <a href="index.php?url=simpatizante/index" class="list-group-item list-group-item-action <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'simpatizante') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-emoji-smile"></i> Simpatizante cercano
                </a>
                <?php endif; ?>
                
                <?php if($puedeVerVotantes): ?>
                <a href="index.php?url=votante/index" class="list-group-item list-group-item-action <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'votante') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-person-check-fill"></i> Cuerpo electoral
                </a>
                <?php endif; ?>
                
                <?php if($puedeVerLideres): ?>
                <a href="index.php?url=lider/index" class="list-group-item list-group-item-action <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'lider') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-person-badge"></i> Líder amigo
                </a>
                <?php endif; ?>

                <?php 
                $puedeVerRegistroV = tienePermiso($tipo_usuario, 'Registro v.', 'ver');
                $puedeVerResultados = tienePermiso($tipo_usuario, 'Resultados', 'ver');
                $puedeVerReportes = tienePermiso($tipo_usuario, 'Reportes G.', 'ver');
                ?>

                <?php if($puedeVerRegistroV): ?>
                <a href="index.php?url=registrovoto/index" class="list-group-item list-group-item-action <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'registrovoto') !== false && strpos($_GET['url'], 'resultados') === false) ? 'active' : ''; ?>">
                    <i class="bi bi-box-arrow-in-right"></i> Registro de Votos
                </a>
                <?php endif; ?>

                <?php if($puedeVerResultados): ?>
                <a href="index.php?url=registrovoto/resultados" class="list-group-item list-group-item-action <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'resultados') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-bar-chart-fill"></i> Resultados en Vivo
                </a>
                <?php endif; ?>

                <?php if($puedeVerReportes): ?>
                <a href="index.php?url=reporte/index" class="list-group-item list-group-item-action <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'reporte/index') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reportes Generales
                </a>
                <?php endif; ?>

                <?php if($puedeVerSeguridad): ?>
                <!-- Separador -->
                <div class="menu-divider"></div>

                <!-- Menú Seguridad con submenú -->
                <?php 
                $seguridadActive = (isset($_GET['url']) && (strpos($_GET['url'], 'usuario') !== false || strpos($_GET['url'], 'auditoria') !== false));
                ?>
                <a class="list-group-item list-group-item-action menu-seguridad d-flex justify-content-between align-items-center <?php echo $seguridadActive ? 'active' : ''; ?>" 
                   data-bs-toggle="collapse" href="#submenuSeguridad" role="button" 
                   aria-expanded="<?php echo $seguridadActive ? 'true' : 'false'; ?>" aria-controls="submenuSeguridad">
                    <span><i class="bi bi-shield-lock"></i> Seguridad</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse <?php echo $seguridadActive ? 'show' : ''; ?>" id="submenuSeguridad">
                    <?php if($puedeVerUsuarios): ?>
                    <a href="index.php?url=usuario/index" class="list-group-item list-group-item-action ps-4 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'usuario') !== false) ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                    <?php endif; ?>
                    <?php if($puedeVerAuditoria): ?>
                    <a href="index.php?url=auditoria/index" class="list-group-item list-group-item-action ps-4 <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'auditoria') !== false) ? 'active' : ''; ?>">
                        <i class="bi bi-eye"></i> Auditoría
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Overlay for mobile styling -->
            <div id="overlay"></div>

            <nav class="navbar navbar-expand-lg navbar-dashboard">
                <div class="container-fluid">
                    <i class="bi bi-list navbar-toggler-icon-custom" id="menu-toggle"></i>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i> <?php echo isset($_SESSION['nombres']) ? $_SESSION['nombres'] : 'Usuario'; ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="index.php?url=usuario/perfil">Mi Perfil</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="index.php?url=auth/logout">Salir</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 py-4">
