<?php
require_once "../Controlador/config.php";

$proveedores = array();
$productos = array();

// Obtener IDs y nombres de proveedores existentes
$sql_proveedores = "SELECT id_proveedor, nom_proveedor FROM proveedor";
$result_proveedores = mysqli_query($link, $sql_proveedores);

if ($result_proveedores) {
    while ($row_proveedor = mysqli_fetch_assoc($result_proveedores)) {
        $proveedores[$row_proveedor['id_proveedor']] = $row_proveedor['nom_proveedor'];
    }
}

// Obtener IDs y nombres de productos existentes
$sql_productos = "SELECT id_producto, nom_producto FROM producto";
$result_productos = mysqli_query($link, $sql_productos);

if ($result_productos) {
    while ($row_producto = mysqli_fetch_assoc($result_productos)) {
        $productos[$row_producto['id_producto']] = $row_producto['nom_producto'];
    }
}

$proveedor_id = $producto_id = $cant_orden_compra = $precio_unitario = $subtotal = "";
$proveedor_id_err = $producto_id_err = $cant_orden_compra_err = $precio_unitario_err = $subtotal_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input_proveedor_id = trim($_POST["proveedor_id"]);
    if (empty($input_proveedor_id) || !array_key_exists($input_proveedor_id, $proveedores)) {
        $proveedor_id_err = "Selecciona un proveedor válido.";
    } else {
        $proveedor_id = $input_proveedor_id;
    }

    $input_producto_id = trim($_POST["producto_id"]);
    if (empty($input_producto_id) || !array_key_exists($input_producto_id, $productos)) {
        $producto_id_err = "Selecciona un producto válido.";
    } else {
        $producto_id = $input_producto_id;
    }

    $input_cant_orden_compra = trim($_POST["cant_orden_compra"]);
    if (empty($input_cant_orden_compra) || !ctype_digit($input_cant_orden_compra)) {
        $cant_orden_compra_err = "Ingresa una cantidad válida.";
    } else {
        $cant_orden_compra = $input_cant_orden_compra;
    }

    $input_precio_unitario = trim($_POST["precio_unitario"]);
    if (empty($input_precio_unitario) || !is_numeric($input_precio_unitario) || $input_precio_unitario <= 0) {
        $precio_unitario_err = "Ingresa un precio unitario válido.";
    } else {
        $precio_unitario = $input_precio_unitario;
    }

    // Calcula el subtotal
    $subtotal = $cant_orden_compra * $precio_unitario;

    if (empty($proveedor_id_err) && empty($producto_id_err) && empty($cant_orden_compra_err) && empty($precio_unitario_err) && empty($subtotal_err)) {

        $sql = "INSERT INTO detordencompra (id_proveedor, id_producto, cant_ordencompra, precio_unitario, subtotal_orden) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iiddd", $param_proveedor_id, $param_producto_id, $param_cant_orden_compra, $param_precio_unitario, $param_subtotal);

            $param_proveedor_id = $proveedor_id;
            $param_producto_id = $producto_id;
            $param_cant_orden_compra = $cant_orden_compra;
            $param_precio_unitario = $precio_unitario;
            $param_subtotal = $subtotal;

            if (mysqli_stmt_execute($stmt)) {
                header("location: ../Vista/orden-compra.php");
                exit();
            } else {
                echo "Oops! Ocurrió un error. Por favor, inténtalo de nuevo más tarde.";
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Orden de Compra</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 360px;
            padding: 20px;
            margin: 0 auto;
            background-color: white;
        }

        body {
            background-image: url(../Img/fondo_ordencompra.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Agregar Orden de Compra</h2>
                    <p>Rellene el formulario para agregar una nueva orden de compra.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Proveedor</label>
                            <select name="proveedor_id" class="form-control <?php echo (!empty($proveedor_id_err)) ? 'is-invalid' : ''; ?>">
                                <option value="" selected disabled>Seleccione un proveedor</option>
                                <?php foreach ($proveedores as $id => $nombre) : ?>
                                    <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $proveedor_id_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Producto</label>
                            <select name="producto_id" class="form-control <?php echo (!empty($producto_id_err)) ? 'is-invalid' : ''; ?>">
                                <option value="" selected disabled>Seleccione un producto</option>
                                <?php foreach ($productos as $id => $nombre) : ?>
                                    <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $producto_id_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input type="text" name="cant_orden_compra" class="form-control <?php echo (!empty($cant_orden_compra_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cant_orden_compra; ?>">
                            <span class="invalid-feedback"><?php echo $cant_orden_compra_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Precio Unitario</label>
                            <input type="text" name="precio_unitario" class="form-control <?php echo (!empty($precio_unitario_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $precio_unitario; ?>">
                            <span class="invalid-feedback"><?php echo $precio_unitario_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Agregar">
                        <a href="../Vista/orden-compra.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>