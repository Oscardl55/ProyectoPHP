<?php
session_start();

// Configuración de la base de datos
$host = '127.0.0.1';
$dbname = 'proyecto php'; // Cambia si es necesario
$username = 'root';
$password = ''; // Sin contraseña
$port = 3307; // Asegúrate de que sea el puerto correcto

// Conexión a la base de datos
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];
    
    // Consulta preparada para prevenir inyección SQL
    $query = "SELECT * FROM Usuarios WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $input_username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica si la columna 'password' existe en el resultado
        if (isset($row['password'])) {
            // Verificar la contraseña usando password_verify
            if ($input_password === $row['password']) {
                // Inicio de sesión exitoso
                $_SESSION['username'] = $input_username;
                header("Location: ../html/index.html"); // Redirigir a index.html
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "La columna 'password' no existe en la base de datos.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Login</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">Login</h2>
                <?php if(isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password">                    
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>