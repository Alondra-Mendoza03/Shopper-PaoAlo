<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopper";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        // Verificar si el token es válido y no ha expirado
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = :token AND expires >= :now");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':now', date("U"));
        $stmt->execute();
        $reset = $stmt->fetch();

        if ($reset) {
            if (isset($_POST['password'])) {
                $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // Actualizar la contraseña del usuario
                $stmt = $conn->prepare("UPDATE registros SET password = :password WHERE email = :email");
                $stmt->bindParam(':password', $newPassword);
                $stmt->bindParam(':email', $reset['email']);
                $stmt->execute();

                // Eliminar el token usado
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = :token");
                $stmt->bindParam(':token', $token);
                $stmt->execute();

                echo "Contraseña restablecida exitosamente.";
            } else {
                ?>
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Restablecer Contraseña</title>
                </head>
                <body>
                    <h2>Restablecer Contraseña</h2>
                    <form name="sentMessage" id="form" method="POST" action="" novalidate="novalidate">
                        <div class="control-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Tu nueva contraseña" required="required" data-validation-required-message="Por favor inserta tu nueva contraseña" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div>
                            <button class="btn btn-primary py-2 px-4" type="submit" id="entrar" name="entrar">Restablecer</button>
                        </div>
                    </form>
                </body>
                </html>
                <?php
            }
        } else {
            echo "Enlace de recuperación inválido o caducado.";
        }
    } else {
        echo "Parámetros inválidos.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
