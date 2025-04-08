<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['username'] !== 'admin') {
    $_SESSION['error'] = "No tienes permisos para eliminar productos.";
    header("Location: productos.php");
    exit();
}


// Conexión a la base de datos
$host = '127.0.0.1';
$dbname = 'proyecto_php';
$username = 'root';
$password = '';
$port = 3306;

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Eliminar producto si se proporciona ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM productos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['mensaje'] = "Producto eliminado correctamente!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
    }
}

header("Location: productos.php");
exit();
?>
