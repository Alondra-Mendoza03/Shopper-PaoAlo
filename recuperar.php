<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Contraseña</title>
</head>
<body>
    <h2>Recuperar Contraseña</h2>
    <form name="sentMessage" id="form" method="POST" action="send_reset_email.php" novalidate="novalidate">
        <div class="control-group">
            <input type="email" class="form-control" id="email" name="email" placeholder="Tu Email" required="required" data-validation-required-message="Por favor inserta tu email" />
            <p class="help-block text-danger"></p>
        </div>
        <div>
            <button class="btn btn-primary py-2 px-4" type="submit" id="entrar" name="entrar">Enviar</button>
        </div>
    </form>
</body>
</html>
