<?php
// Configuración
$fromEmail = '21045111@alumno.utc.edu.mx';
$fromName = 'ALondra';
$subject = 'Recuperación de contraseña';
$tokenDir = 'tokens'; // Directorio para almacenar tokens

// Función para generar un token aleatorio
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Enviar correo de recuperación
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $token = generateToken();
    $tokenFile = $tokenDir . '/' . $email . '.token';

    // Guardar el token en un archivo
    if (!file_exists($tokenDir)) {
        mkdir($tokenDir, 0777, true);
    }
    file_put_contents($tokenFile, $token);

    // Crear el enlace de recuperación
    $resetLink = "http://example.com/reset_password.php?email=$email&token=$token";

    // Enviar el correo
    $headers = "From: $fromName <$fromEmail>\r\n";
    $message = "Hola,\n\nPara restablecer tu contraseña, haz clic en el siguiente enlace:\n$resetLink\n\nSi no solicitaste este correo, por favor ignóralo.";
    if (mail($email, $subject, $message, $headers)) {
        echo "Correo de recuperación enviado.";
    } else {
        echo "Error al enviar el correo.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recuperar contraseña</title>
</head>
<body>
    <form action="" method="post">
        <label for="email">Introduce tu correo electrónico:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
