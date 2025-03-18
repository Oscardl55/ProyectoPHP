<?php
session_start();

// Configuración de la base de datos
$host = '127.0.0.1';
$dbname = 'proyecto_php';
$username = 'root';
$password = '';
$port = 3306;

// Conexión a la base de datos
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar si las contraseñas coinciden
    if ($new_password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el usuario ya existe
        $check_query = "SELECT * FROM Usuarios WHERE usuario = :usuario";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bindParam(':usuario', $new_username);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            $error = "El nombre de usuario ya está en uso.";
        } else {
            // Insertar nuevo usuario
            $insert_query = "INSERT INTO Usuarios (usuario, contrasena) VALUES (:usuario, :contrasena)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bindParam(':usuario', $new_username);
            $insert_stmt->bindParam(':contrasena', $new_password);

            if ($insert_stmt->execute()) {
                $success = "Usuario registrado con éxito. Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar el usuario.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Registro</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">Registro</h2>
                <?php if(isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <?php if(isset($success)) { ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php } ?>
                <form action="register.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </form>
                <p class="mt-3">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>