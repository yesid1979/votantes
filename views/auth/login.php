<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso al Sistema | Votantes</title>
    <!-- Favicon -->
    <link href="images/favicon.png" rel="icon" type="image/png">
    <!-- Fonts Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style_modern.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                 <img src="images/favicon.png" alt="Logo" class="login-logo">
                <h1 class="login-title">Bienvenido</h1>
                <p class="login-subtitle">Ingrese sus credenciales para continuar</p>
            </div>
            
            <div class="login-body">
                <!-- Mostrar errores si existen -->
                 <?php if(!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) echo $error . "<br>"; ?>
                    </div>
                <?php endif; ?>

                <form id="loginform" action="index.php" method="POST" autocomplete="off">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario o Email" required>
                        <label for="usuario"><i class="bi bi-person me-2"></i>Usuario o Email</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                        <label for="password"><i class="bi bi-lock me-2"></i>Contraseña</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar
                    </button>

                    <div class="login-footer">
                        <a href="index.php?url=auth/forgotPassword">¿Olvidaste tu contraseña?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>