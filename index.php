<?php
// Configuración básica
// Detección dinámica de la URL Base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$base = dirname($script);
// Asegurar que termine en / y corregir backslashes en Windows
$base_url = $protocol . "://" . $host . rtrim(str_replace('\\', '/', $base), '/') . '/';

define('URL_BASE', $base_url);

// Autocarga de clases básica
spl_autoload_register(function ($class_name) {
    if (file_exists('controllers/' . $class_name . '.php')) {
        require_once 'controllers/' . $class_name . '.php';
    } elseif (file_exists('models/' . $class_name . '.php')) {
        require_once 'models/' . $class_name . '.php';
    } elseif (file_exists('funcs/' . $class_name . '.php')) {
        require_once 'funcs/' . $class_name . '.php';
    }
});

// Iniciar sesión si no está iniciada
if(!isset($_SESSION)) { session_start(); }

// Enrutamiento básico
$url = isset($_GET['url']) ? $_GET['url'] : 'auth/login';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

// Controlador por defecto (Auth/Login o Inicio)
$controllerName = isset($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'AuthController';
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';

// Excepciones de rutas "Legacy" o amigables personalizadas
if($urlParts[0] == 'welcome.php' || $urlParts[0] == 'welcome') {
    $controllerName = 'DashboardController';
    $methodName = 'index';
}
if($urlParts[0] == 'index.php' || empty($urlParts[0])) {
    $controllerName = 'AuthController';
    $methodName = 'login';
}

// Verificar autenticación para rutas protegidas
if($controllerName != 'AuthController' && !isset($_SESSION['id_usuario'])) {
   // Si intenta acceder a algo protegido sin loguearse, enviar al login
   $controllerName = 'AuthController';
   $methodName = 'login';
}

// Instanciar controlador y ejecutar método
if (file_exists('controllers/' . $controllerName . '.php')) {
    require_once 'controllers/' . $controllerName . '.php';
    $controller = new $controllerName();
    
    if (method_exists($controller, $methodName)) {
        // Pasar parámetros extra si existen
        $params = array_slice($urlParts, 2);
        call_user_func_array([$controller, $methodName], $params);
    } else {
        // Método no encontrado, mostrar 404 o redirigir
        echo "Error 404: Método no encontrado ($methodName).";
    }
} else {
    // Controlador no encontrado
    echo "Error 404: Controlador no encontrado ($controllerName).";
}
?>
