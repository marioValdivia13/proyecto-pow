<?php
require_once "../Controlador/config.php";

// Inicializar variables
$id_usuario = $id_producto = $cantidad = $precio_venta = "";
$id_usuario_err = $id_producto_err = $cantidad_err = $precio_venta_err = "";
$productos = array();

// Obtener lista de productos disponibles
$sql_productos = "SELECT id_producto, nom_producto, precio, stock_real FROM producto WHERE stock_real > 0";
if($result = mysqli_query($link, $sql_productos)){
    while($row = mysqli_fetch_assoc($result)){
        $productos[] = $row;
    }
    mysqli_free_result($result);
}

// Obtener usuario actual (deberías obtenerlo de la sesión)
session_start();
if(isset($_SESSION["id"])){
    $id_usuario = $_SESSION["id"];
} else {
    // Para prueba, usar el primer usuario - en producción esto debe venir de la sesión
    $id_usuario = 1;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validar producto
    $input_id_producto = trim($_POST["id_producto"]);
    if(empty($input_id_producto)){
        $id_producto_err = "Selecciona un producto.";
    } else{
        $id_producto = $input_id_producto;
    }
    
    // Validar cantidad
    $input_cantidad = trim($_POST["cantidad"]);
    if(empty($input_cantidad)){
        $cantidad_err = "Ingresa la cantidad.";
    } elseif(!ctype_digit($input_cantidad)){
        $cantidad_err = "Ingresa un número positivo.";
    } else{
        $cantidad = $input_cantidad;
        
        // Verificar stock disponible
        if($id_producto){
            $sql_stock = "SELECT stock_real, precio FROM producto WHERE id_producto = ?";
            if($stmt = mysqli_prepare($link, $sql_stock)){
                mysqli_stmt_bind_param($stmt, "i", $id_producto);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $stock_real, $precio_producto);
                    if(mysqli_stmt_fetch($stmt)){
                        if($cantidad > $stock_real){
                            $cantidad_err = "Stock insuficiente. Stock disponible: " . $stock_real;
                        } else{
                            $precio_venta = $precio_producto; // Precio actual del producto
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
    
    // Si no hay errores, proceder con la inserción
    if(empty($id_producto_err) && empty($cantidad_err)){
        
        // Calcular subtotal
        $subtotal = $cantidad * $precio_venta;
        $fecha = date('Y-m-d');
        
        $sql = "INSERT INTO venta (id, id_producto, fecha, cantidad, precio_venta, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "iisiii", $id_usuario, $id_producto, $fecha, $cantidad, $precio_venta, $subtotal);
            
            if(mysqli_stmt_execute($stmt)){
                // Actualizar stock del producto
                $sql_update_stock = "UPDATE producto SET stock_real = stock_real - ? WHERE id_producto = ?";
                if($stmt_update = mysqli_prepare($link, $sql_update_stock)){
                    mysqli_stmt_bind_param($stmt_update, "ii", $cantidad, $id_producto);
                    mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);
                }
                
                // Redirigir a la página de ventas
                header("location: ../Vista/ventas.php");
                exit();
            } else{
                echo "Oops! Ocurrió un error al registrar la venta.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            padding: 20px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        body {
            background-image: url(../Img/fondo_plato.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
            padding-top: 50px;
        }
        .product-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Registrar Venta</h2>
                    <p>Complete el formulario para registrar una nueva venta.</p>
                    
                    <!-- Información del producto seleccionado -->
                    <div class="product-info" id="productInfo" style="display: none;">
                        <h5>Información del Producto</h5>
                        <p id="stockInfo"></p>
                        <p id="precioInfo"></p>
                    </div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Producto</label>
                            <select name="id_producto" class="form-control <?php echo (!empty($id_producto_err)) ? 'is-invalid' : ''; ?>" id="selectProducto" onchange="actualizarInfoProducto()">
                                <option value="">Seleccione un producto</option>
                                <?php foreach($productos as $producto): ?>
                                    <option value="<?php echo $producto['id_producto']; ?>" 
                                            data-stock="<?php echo $producto['stock_real']; ?>"
                                            data-precio="<?php echo $producto['precio']; ?>">
                                        <?php echo $producto['nom_producto'] . " - Stock: " . $producto['stock_real'] . " - $" . $producto['precio']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $id_producto_err; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" min="1" 
                                class="form-control <?php echo (!empty($cantidad_err)) ? 'is-invalid' : ''; ?>" 
                                value="<?php echo $cantidad; ?>"
                                id="inputCantidad" onchange="calcularSubtotal()">
                            <span class="invalid-feedback"><?php echo $cantidad_err; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Subtotal: $<span id="subtotal">0</span></label>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Registrar Venta">
                        <a href="../Vista/ventas.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function actualizarInfoProducto() {
            const select = document.getElementById('selectProducto');
            const productInfo = document.getElementById('productInfo');
            const stockInfo = document.getElementById('stockInfo');
            const precioInfo = document.getElementById('precioInfo');
            
            if(select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const stock = selectedOption.getAttribute('data-stock');
                const precio = selectedOption.getAttribute('data-precio');
                
                stockInfo.textContent = 'Stock disponible: ' + stock;
                precioInfo.textContent = 'Precio unitario: $' + precio;
                productInfo.style.display = 'block';
                
                // Actualizar subtotal
                calcularSubtotal();
            } else {
                productInfo.style.display = 'none';
            }
        }
        
        function calcularSubtotal() {
            const select = document.getElementById('selectProducto');
            const cantidadInput = document.getElementById('inputCantidad');
            const subtotalSpan = document.getElementById('subtotal');
            
            if(select.value && cantidadInput.value) {
                const precio = select.options[select.selectedIndex].getAttribute('data-precio');
                const cantidad = cantidadInput.value;
                const subtotal = precio * cantidad;
                subtotalSpan.textContent = subtotal;
            } else {
                subtotalSpan.textContent = '0';
            }
        }
    </script>
</body>
</html>