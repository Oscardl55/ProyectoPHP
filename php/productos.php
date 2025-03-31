<?php
session_start();


// Configuración de la base de datos
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


// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $marca = $_POST['marca'];


    try {
        $query = "INSERT INTO productos (Nombre, Descripcion, Precio, Stock, Marca)
                  VALUES (:nombre, :descripcion, :precio, :stock, :marca)";
        $stmt = $conn->prepare($query);
       
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':marca', $marca);
       
        $stmt->execute();
       
        $mensaje = "Producto añadido correctamente!";
    } catch(PDOException $e) {
        $error = "Error al añadir producto: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 30px;
        }
        #formularioProducto {
            display: none;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Gestión de Productos</h2>
        
        <?php if(isset($mensaje)): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <button id="mostrarFormulario" class="btn btn-primary mb-4">Añadir Nuevo Producto</button>
        <a href="admin.php" class="btn btn-secondary mb-4">Volver al Panel</a>
        
        <div id="formularioProducto">
            <h4 class="mb-3">Formulario de Producto</h4>
            <form method="POST" action="productos.php">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required 
                           value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                </div>
                
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php 
                        echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; 
                    ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" required 
                           value="<?php echo isset($_POST['precio']) ? htmlspecialchars($_POST['precio']) : ''; ?>">
                </div>
                
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" required 
                           value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : ''; ?>">
                </div>
                
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" required 
                           value="<?php echo isset($_POST['marca']) ? htmlspecialchars($_POST['marca']) : ''; ?>">
                </div>
                
                <button type="submit" class="btn btn-success">Guardar Producto</button>
                <button type="button" id="ocultarFormulario" class="btn btn-outline-secondary">Cancelar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mostrarBtn = document.getElementById('mostrarFormulario');
            const ocultarBtn = document.getElementById('ocultarFormulario');
            const formulario = document.getElementById('formularioProducto');
            
            mostrarBtn.addEventListener('click', function() {
                formulario.style.display = 'block';
                mostrarBtn.style.display = 'none';
            });
            
            ocultarBtn.addEventListener('click', function() {
                formulario.style.display = 'none';
                mostrarBtn.style.display = 'block';
            });
            
            // Mostrar el formulario si hay un error para que el usuario pueda corregirlo
            <?php if(isset($error)): ?>
                formulario.style.display = 'block';
                mostrarBtn.style.display = 'none';
            <?php endif; ?>
        });
    </script>
</body>
</html>