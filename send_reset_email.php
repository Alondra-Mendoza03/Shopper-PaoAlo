<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopper";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Verificar si el correo existe en la base de datos
        $stmt = $conn->prepare("SELECT * FROM registros WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date("U") + 1800; // El token expira en 30 minutos

            // Insertar el token en la base de datos
            $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (:email, :token, :expires)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires', $expires);
            $stmt->execute();

            // Enviar el correo usando PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Configura tu servidor SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'alondra.mitchel@gmail.com'; // Tu correo de Gmail
                $mail->Password = 'xmcz jvtd gbhs azqn'; // Tu contraseña de Gmail
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Configuración del correo
                $mail->setFrom('alondra.mitchel@gmail.com', 'xmcz jvtd gbhs azqn');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Restablecer tu contraseña';
                $mail->Body    = "Para restablecer tu contraseña, haz clic en el siguiente enlace:<br><a href='http://localhost/reset_password.php?token=$token'>Restablecer contraseña</a>";
                


                
                $mail->send();
                echo 'Correo de recuperación enviado.';
            } catch (Exception $e) {
                echo "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        } else {
            echo "Correo no encontrado.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
