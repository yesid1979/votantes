<?php
require_once "funcs/conexion.php";
require_once "funcs/funcs.php";

class AuthController {
    
    public function login() {
        if(isset($_SESSION["id_usuario"])) { 
            header("Location: " . URL_BASE . "dashboard");
            exit;
        }

        $errors = array();

        if(!empty($_POST)) { 
             
            $usuario = trim($_POST['usuario']);
            $password = trim($_POST['password']);
            
            if(isNullLogin($usuario, $password)) {
                $errors[] = "Debe llenar todos los campos";
            }
            
            $loginError = login($usuario, $password);
            
            if(!empty($loginError)) {
                $errors[] = $loginError;
            } else {
                if(isset($_SESSION['id_usuario'])) {
                     header("Location: " . URL_BASE . "dashboard");
                     exit;
                }
            }
        }
        
        require 'views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: " . URL_BASE);
        exit;
    }

    public function forgotPassword() {
        $errors = array();
        if(!empty($_POST)) {
            $email = trim($_POST['email']);
            if(!isEmail($email)) {
                $errors[] = "Debes ingresar un correo electrónico válido";
            } else {
                if(emailExiste($email)) {
                    $user_id = getValor('id', 'correo', $email);
                    $nombre = getValor('nombre', 'correo', $email);
                    
                    $token = generaTokenPass($user_id);
                    
                    $url = URL_BASE . "index.php?url=auth/resetPassword&user_id=".$user_id."&token=".$token;
                    
                    $asunto = 'Recuperar Password - Sistema de Votantes';
                    
                    // Cuerpo del correo con diseño profesional
                    $cuerpo = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e1e4e8; border-radius: 12px; background-color: #ffffff;'>
                        <div style='text-align: center; margin-bottom: 30px;'>
                            <h2 style='color: #0d47a1; margin-bottom: 10px;'>Recuperación de Contraseña</h2>
                            <p style='color: #586069; font-size: 16px;'>Sistema de Gestión de Votantes</p>
                        </div>
                        <div style='color: #24292e; line-height: 1.6;'>
                            <p>Hola <strong>$nombre</strong>,</p>
                            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Si no has sido tú, puedes ignorar este mensaje con total tranquilidad.</p>
                            <p>Para crear una nueva contraseña, por favor haz clic en el siguiente botón:</p>
                        </div>
                        <div style='text-align: center; margin: 40px 0;'>
                            <a href='$url' style='background-color: #0d47a1; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block; transition: background-color 0.3s ease;'>Restablecer mi Contraseña</a>
                        </div>
                        <div style='color: #586069; font-size: 14px; margin-top: 40px; border-top: 1px solid #eaecef; padding-top: 20px;'>
                            <p>Si el botón no funciona, puedes copiar y pegar este enlace en tu navegador:</p>
                            <p style='word-break: break-all; color: #0366d6;'><a href='$url'>$url</a></p>
                        </div>
                        <p style='font-size: 12px; color: #959da5; text-align: center; margin-top: 30px;'>
                            Este es un correo automático, por favor no respondas a este mensaje.
                        </p>
                    </div>";
                    
                    if(enviarEmail($email, $nombre, $asunto, $cuerpo)){
                        // Success message handling
                        $msg = "Hemos enviado un correo electrónico a las direccion $email para restablecer tu password.";
                    } else {
                        $errors[] = "Error al enviar el correo";
                    }
                } else {
                    $errors[] = "La dirección de correo electrónico no existe";
                }
            }
        }
        require 'views/auth/recupera.php';
    }

    public function resetPassword() {
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
        $token = isset($_GET['token']) ? $_GET['token'] : '';
        
        if(empty($user_id) || empty($token)){
            header("Location: " . URL_BASE);
            exit;
        }
        
        if(!verificaTokenPass($user_id, $token)){
             $errors = array();
             $errors[] = "El enlace de recuperación es inválido o ha expirado. Por favor, solicite uno nuevo.";
             require 'views/auth/recupera.php';
             exit;
        }

        $errors = array();
        
        if(!empty($_POST)) {
            $password = trim($_POST['password']);
            $con_password = trim($_POST['con_password']);
            
            if(validaPassword($password, $con_password)) {
                 $pass_hash = password_hash($password, PASSWORD_DEFAULT);
                 
                 if(cambiaPassword($pass_hash, $user_id, $token)) {
                     $msg = "Contraseña modificada con éxito. <a href='index.php?url=auth/login'>Iniciar Sesión</a>";
                 } else {
                     $errors[] = "Error al modificar contraseña";
                 }
            } else {
                $errors[] = "Las contraseñas no coinciden";
            }
        }
        
        require 'views/auth/cambia_pass.php';
    }
}
?>
