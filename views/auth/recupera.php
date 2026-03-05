<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Votantes</title>
    <link href="images/favicon.png" rel="icon" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style_modern.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                 <img src="images/favicon.png" alt="Logo" class="login-logo">
                <h1 class="login-title">Recuperar Acceso</h1>
                <p class="login-subtitle">Ingrese su correo para restablecer su contraseña</p>
            </div>
            
            <div class="login-body">
                 <?php if(!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) echo $error . "<br>"; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($msg)): ?>
                    <div class="alert alert-success">
                        <?php echo $msg; ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?url=auth/forgotPassword" method="POST" autocomplete="off">
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" required>
                        <label for="email"><i class="bi bi-envelope me-2"></i>Correo Electrónico</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="bi bi-send me-2"></i> Enviar Link
                    </button>

                    <div class="login-footer">
                        <p class="text-muted mb-2 small">¿Recordaste tu contraseña?</p>
                        <a href="index.php?url=auth/login" class="fw-bold text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> Regresar al Inicio de Sesión
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
