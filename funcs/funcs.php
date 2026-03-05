<?php
	require_once 'password.php';
	
	function isNull($nombre, $user, $pass, $pass_con, $email)
	{
		if(strlen(trim($nombre)) < 1 || strlen(trim($user)) < 1 || strlen(trim($pass)) < 1 || strlen(trim($pass_con)) < 1 || strlen(trim($email)) < 1)
		{
			return true;
		} 
		else 
		{
			return false;
		}		
	}
	
	function isEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
			} else {
			return false;
		}
	}
	
	function validaPassword($var1, $var2)
	{
		if (strcmp($var1, $var2) !== 0){
			return false;
			} else {
			return true;
		}
	}
	
	function minMax($min, $max, $valor){
		if(strlen(trim($valor)) < $min)
		{
			return true;
		}
		else if(strlen(trim($valor)) > $max)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function usuarioExiste($usuario)
	{
		global $conexion;
		
		$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = :usuario LIMIT 1");
		$stmt->bindParam(":usuario", $usuario);
		$stmt->execute();
		$num = $stmt->rowCount();
		
		if ($num > 0){
			return true;
			} else {
			return false;
		}
	}
	
	function emailExiste($email)
	{
		global $conexion;
		
		$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = :email LIMIT 1");
		$stmt->bindParam(":email", $email);
		$stmt->execute();
		$num = $stmt->rowCount();
		
		if ($num > 0){
			return true;
			} else {
			return false;	
		}
	}
	
	function generateToken()
	{
		$gen = md5(uniqid(mt_rand(), false));	
		return $gen;
	}
	
	function resultBlock($errors){
		if(count($errors) > 0)
		{
			echo "<div id='error' class='alert alert-danger alert-dismissable' role='alert' >
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			<ul>";
			foreach($errors as $error)
			{
				echo "<li>".$error."</li>";
			}
			echo "</ul>";
			echo "</div>";
		}
	}
	
	function registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario)
	{
		global $conexion;
		
		$stmt = $conexion->prepare("INSERT INTO usuarios (usuario, password_usu, nombre, correo, activacion, token, id_tipo) VALUES(:usuario, :pass, :nombre, :email, :activo, :token, :tipo)");
		$stmt->bindParam(':usuario', $usuario);
		$stmt->bindParam(':pass', $pass_hash);
		$stmt->bindParam(':nombre', $nombre);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':activo', $activo);
		$stmt->bindParam(':token', $token);
		$stmt->bindParam(':tipo', $tipo_usuario);
		
		if ($stmt->execute())
		{
			return $conexion->lastInsertId();
		} 
		else 
        {
			return 0;	
		}		
	}
	
	function enviarEmail($email, $nombre, $asunto, $cuerpo)
	{
		require_once __DIR__ . '/../PHPMailer/class.phpmailer.php';
		require_once __DIR__ . '/../PHPMailer/class.smtp.php';
		
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls'; 
		$mail->Host = 'smtp.gmail.com'; 
		$mail->Port = 587; 
		
		$mail->Username = 'registrovotantes@gmail.com'; 
		$mail->Password = 'ftnf tspt bini lbau'; 
		
		$mail->setFrom('registrovotantes@gmail.com', 'Sistema de Gestión de Votantes');
		$mail->addAddress($email, $nombre);
		
		$mail->Subject = $asunto;
		$mail->Body    = $cuerpo;
		$mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
		
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		return $mail->send();
	}
	
	function validaIdToken($id, $token){
		global $conexion;
		
		$stmt = $conexion->prepare("SELECT activacion FROM usuarios WHERE id = :id AND token = :token LIMIT 1");
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":token", $token);
		$stmt->execute();
		$num = $stmt->rowCount(); // Using rowCount for checking existence
		
		if($num > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$activacion = $row['activacion'];
			
			if($activacion == 1){
				$msg = "La cuenta ya se activo anteriormente.";
				} else {
				if(activarUsuario($id)){
					$msg = 'Cuenta activada.';
					} else {
					$msg = 'Error al Activar Cuenta';
				}
			}
			} else {
			$msg = 'No existe el registro para activar.';
		}
		return $msg;
	}
	
	function activarUsuario($id)
	{
		global $conexion;
		
		$stmt = $conexion->prepare("UPDATE usuarios SET activacion=1 WHERE id = :id");
		$stmt->bindParam(':id', $id);
		$result = $stmt->execute();
		return $result;
	}
	
	function isNullLogin($usuario, $password){
		if(strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	function login($usuario, $password)
	{
		global $conexion;
		
		$stmt = $conexion->prepare("SELECT id, id_tipo, password_usu, nombre, usuario, ced_usuario FROM usuarios WHERE usuario = :usuario OR correo = :correo LIMIT 1");
		$stmt->bindParam(":usuario", $usuario);
		$stmt->bindParam(":correo", $usuario); // Using same var for user/email check
		$stmt->execute();
		
		if($stmt->rowCount() > 0) 
		{
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $row['id'];
            $id_tipo = $row['id_tipo'];
            $passwd = $row['password_usu'];
            $nombre = $row['nombre'];
            $cedula = $row['ced_usuario'];
            $lastlogin = date("Y-m-d H:i:s"); // Assuming current time as last login for logging purposed in session, though DB update happens in lastSession()? Legacy code logic.
            // Wait, legacy code fetched lastlogin from DB? "SELECT... $lastlogin" didn't exist in SELECT list in legacy, it fetched "nombre,usuario,ced_usuario" but bound "lastlogin"? 
            // Looking at legacy: "SELECT id, id_tipo, password_usu, nombre,usuario,ced_usuario..." -> bind_result($id, $id_tipo, $passwd, $nombre, $lastlogin,$cedula);
            // $lastlogin variable in bind_result matched 'usuario' column?? That seems like a bug in legacy or mismatch. 
            // In legacy: id->id, id_tipo->id_tipo, password_usu->passwd, nombre->nombre, usuario->lastlogin, ced_usuario->cedula.
            // So legacy $_SESSION['lastlogin'] was actually holding the username?
            // Let's replicate legacy behavior or fix it. If it was holding username, let's keep it or fix to actual last login if available. 
            // For now, I'll store the username in lastlogin session var to be safe with legacy usage, OR better, I'll create a real timestamp.
            // Actually, let's look at legacy again: "$stmt->bind_result($id, $id_tipo, $passwd, $nombre, $lastlogin,$cedula);"
            // 5th column in SELECT is 'usuario'. So yes, lastlogin session var held the username.
            
			date_default_timezone_set('America/Bogota');
			$fecha=date("d/m/Y");
			$hora=date("h:i:s A");
			$lastaccion="Ingreso al sistema";
			$lasttipo="Inicio session";
            
			if(isActivo($usuario))
			{
				$validaPassw = password_verify($password, $passwd);
				
				if($validaPassw)
				{
					lastSession($id);
					$tomarip=ipCheck();
					$tipoUser=BuscarTipo($id_tipo);
					$_SESSION['id_usuario'] = $id;
					$_SESSION['tipo_usuario'] = $id_tipo;
				    $_SESSION['nombres']= $nombre;
					$_SESSION['cedula']= $cedula;
					$_SESSION['lastlogin']= $row['usuario']; // Replicating legacy behavior where 5th col (usuario) was mapped to lastlogin var.
					$_SESSION['tipoUser']= $tipoUser;
					$registrados=auditoria($fecha, $hora, $_SESSION['lastlogin'], $nombre, $lastaccion, $lasttipo, $tipoUser, $tomarip);
					// Removed header redirection here, Controller handles it.
                    return null; // Success
				} 
				else 
				{
					return "La contraseña es incorrecta";
				}
			} 
			else 
			{
				return 'El usuario no esta activo';
			}
		} 
		else 
		{
			return "El nombre de usuario o correo electrónico no existe";
		}
	}
	
	function lastSession($id)
	{
		global $conexion;
		
		$stmt = $conexion->prepare("UPDATE usuarios SET last_session=NOW(), token_password='', password_request=0 WHERE id = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}
	
	function isActivo($usuario)
	{
		global $conexion;
		
		$stmt = $conexion->prepare("SELECT activacion FROM usuarios WHERE usuario = :usuario OR correo = :correo LIMIT 1");
		$stmt->bindParam(":usuario", $usuario);
		$stmt->bindParam(":correo", $usuario);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($row && $row['activacion'] == 1)
		{
			return true;
		}
		else
		{
			return false;	
		}
	}	
	
	function generaTokenPass($user_id)
	{
		global $conexion;
		
		$token = generateToken();
		
		$stmt = $conexion->prepare("UPDATE usuarios SET token_password=:token, password_request=1 WHERE id = :id");
		$stmt->bindParam(':token', $token);
		$stmt->bindParam(':id', $user_id);
		$stmt->execute();
		
		return $token;
	}
	
	function getValor($campo, $campoWhere, $valor)
	{
		global $conexion;
		
		// Note: $campo and $campoWhere are dynamic column names, they cannot be bound parameters. 
        // We must ensure they are safe or hardcoded in usage. Assuming legacy usage was safe-ish.
		$stmt = $conexion->prepare("SELECT $campo FROM usuarios WHERE $campoWhere = :valor LIMIT 1");
		$stmt->bindParam(':valor', $valor);
		$stmt->execute();
		
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row[$campo];
		}
		else
		{
			return null;	
		}
	}
	
	function getPasswordRequest($id)
	{
		global $conexion;
		
		$stmt = $conexion->prepare("SELECT password_request FROM usuarios WHERE id = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($row && $row['password_request'] == 1)
		{
			return true;
		}
		else
		{
			return null;	
		}
	}
	
	function verificaTokenPass($user_id, $token){
		
		global $conexion;
		
		$stmt = $conexion->prepare("SELECT activacion FROM usuarios WHERE id = :id AND token_password = :token AND password_request = 1 LIMIT 1");
		$stmt->bindParam(':id', $user_id);
		$stmt->bindParam(':token', $token);
		$stmt->execute();
		
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($row['activacion'] == 1)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else
		{
			return false;	
		}
	}
	
	function cambiaPassword($password, $user_id, $token){
		
		global $conexion;
		
		$stmt = $conexion->prepare("UPDATE usuarios SET password_usu = :pass, token_password='', password_request=0 WHERE id = :id AND token_password = :token");
		$stmt->bindParam(':pass', $password);
		$stmt->bindParam(':id', $user_id);
		$stmt->bindParam(':token', $token);
		
		if($stmt->execute()){
			return true;
			} else {
			return false;		
		}
	}	
   
   function auditoria($lastfecha, $lasthora, $lastlogin, $lastname, $lastaccion,$lasttipo,$tipousu,$lastip)
   {
		// Create own connection instead of relying on global
		require_once __DIR__ . '/class.conexion.php';
		$conn = new Conexion();
		$conexion = $conn->get_conexion();
			
		$stmt = $conexion->prepare("INSERT INTO auditoria (lastfecha, lasthora, lastlogin, lastname, lastaccion,lasttipo,tipousu,lastip) VALUES(:lastfecha,:lasthora,:lastlogin,:lastname,:lastaccion,:lasttipo,:tipousu,:lastip)");
		$stmt->bindParam(':lastfecha',$lastfecha);
		$stmt->bindParam(':lasthora',$lasthora);
		$stmt->bindParam(':lastlogin',$lastlogin);
		$stmt->bindParam(':lastname',$lastname);
		$stmt->bindParam(':lastaccion',$lastaccion);
		$stmt->bindParam(':lasttipo',$lasttipo);
		$stmt->bindParam(':tipousu',$tipousu);
		$stmt->bindParam(':lastip',$lastip);
		
		if ($stmt->execute())
		{
			return $conexion->lastInsertId();
		} 
		else 
        {
			return 0;	
		}	 
   }
   
   // Alias for backward compatibility with auditoria1 calls
   function auditoria1($lastfecha, $lasthora, $lastlogin, $lastname, $lastaccion, $lasttipo, $tipousu, $lastip) {
       return auditoria($lastfecha, $lasthora, $lastlogin, $lastname, $lastaccion, $lasttipo, $tipousu, $lastip);
   }
   
function ipCheck()
{
	if (getenv('HTTP_CLIENT_IP'))
    {
	  $ips = getenv('HTTP_CLIENT_IP');
	}
	elseif (getenv('HTTP_X_FORWARDED_FOR'))
    {
	  $ips = getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif (getenv('HTTP_X_FORWARDED')) 
    {
	  $ips = getenv('HTTP_X_FORWARDED');
	}
	elseif (getenv('HTTP_FORWARDED_FOR'))
    {
	  $ips = getenv('HTTP_FORWARDED_FOR');
    }
	elseif (getenv('HTTP_FORWARDED'))
    {
	  $ips = getenv('HTTP_FORWARDED');
    }
	else
	{
	  $ips = $_SERVER['REMOTE_ADDR'];
	}
	return $ips;
}

function BuscarTipo($id)
{
		global $conexion;
		
		$stmt = $conexion->prepare("SELECT tipo FROM tipo_usuario WHERE id = :id LIMIT 1");
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		
		if($stmt->rowCount() > 0) 
		{
		  $row = $stmt->fetch(PDO::FETCH_ASSOC);
		  return $row['tipo'];
		}
		return null;
}
?>
