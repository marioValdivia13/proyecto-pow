<?php
require_once "../Controlador/config.php";

$proveedores = array();
$productos = array();

// Obtener IDs de proveedores existentes
$sql_proveedores = "SELECT id_proveedor, nom_proveedor FROM proveedor";
$result_proveedores = mysqli_query($link, $sql_proveedores);

if ($result_proveedores) {
    while ($row_proveedor = mysqli_fetch_assoc($result_proveedores)) {
        $proveedores[$row_proveedor['id_proveedor']] = $row_proveedor['nom_proveedor'];
    }
}

// Obtener IDs de productos existentes
$sql_productos = "SELECT id_producto, nom_producto FROM producto";
$result_productos = mysqli_query($link, $sql_productos);

if ($result_productos) {
    while ($row_producto = mysqli_fetch_assoc($result_productos)) {
        $productos[$row_producto['id_producto']] = $row_producto['nom_producto'];
    }
}

$id_DetalleOrden = $proveedor_id = $producto_id = $cant_ordencompra = $precio_unitario = $subtotal = "";
$proveedor_id_err = $producto_id_err = $cant_ordencompra_err = $precio_unitario_err = $subtotal_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input_id_DetalleOrden = trim($_POST["id_DetalleOrden"]);
    $id_DetalleOrden = $input_id_DetalleOrden;

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

    $input_cant_ordencompra = trim($_POST["cant_ordencompra"]);
    if (empty($input_cant_ordencompra) || !ctype_digit($input_cant_ordencompra)) {
        $cant_ordencompra_err = "Ingresa una cant_ordencompra válida.";
    } else {
        $cant_ordencompra = $input_cant_ordencompra;
    }

    $input_precio_unitario = trim($_POST["precio_unitario"]);
    if (empty($input_precio_unitario) || !is_numeric($input_precio_unitario) || $input_precio_unitario <= 0) {
        $precio_unitario_err = "Ingresa un precio unitario válido.";
    } else {
        $precio_unitario = $input_precio_unitario;
    }

    // Calcula el subtotal
    $subtotal = $cant_ordencompra * $precio_unitario;

    if (empty($proveedor_id_err) && empty($producto_id_err) && empty($cant_ordencompra_err) && empty($precio_unitario_err) && empty($subtotal_err)) {

        $sql = "UPDATE detordencompra SET id_proveedor=?, id_producto=?, cant_ordencompra=?, precio_unitario=?, subtotal_orden=? WHERE id_DetalleOrden=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iidddi", $param_proveedor_id, $param_producto_id, $param_cant_ordencompra, $param_precio_unitario, $param_subtotal, $param_id_DetalleOrden);

            $param_proveedor_id = $proveedor_id;
            $param_producto_id = $producto_id;
            $param_cant_ordencompra = $cant_ordencompra;
            $param_precio_unitario = $precio_unitario;
            $param_subtotal = $subtotal;
            $param_id_DetalleOrden = $id_DetalleOrden;

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
} else {
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id_DetalleOrden = trim($_GET["id"]);
        $sql = "SELECT * FROM detordencompra WHERE id_DetalleOrden = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id_DetalleOrden);
            $param_id_DetalleOrden = $id_DetalleOrden;

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $proveedor_id = $row["id_proveedor"];
                    $producto_id = $row["id_producto"];
                    $cant_ordencompra = $row["cant_ordencompra"];
                    $precio_unitario = $row["precio_unitario"];
                    $subtotal = $row["subtotal_orden"];
                } else {
                    header("location: ../Vista/error.php");
                    exit();
                }
            } else {
                echo "Oops! Algo ha ocurrido, inténtalo más tarde.";
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    } else {
        header("location: ../Vista/error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar Orden de Compra</title>
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
                    <h2 class="mt-5">Actualizar Orden de Compra</h2>
                    <p>Por favor, actualiza los datos de la orden de compra para guardarlos.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Proveedor</label>
                            <select name="proveedor_id" class="form-control <?php echo (!empty($proveedor_id_err)) ? 'is-invalid' : ''; ?>">
                                <option value="" selected disabled>Seleccione un proveedor</option>
                                <?php foreach ($proveedores as $id => $nom) : ?>
                                    <option value="<?php echo $id; ?>" <?php echo ($proveedor_id == $id) ? 'selected' : ''; ?>><?php echo $nom; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $proveedor_id_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Producto</label>
                            <select name="producto_id" class="form-control <?php echo (!empty($producto_id_err)) ? 'is-invalid' : ''; ?>">
                                <option value="" selected disabled>Seleccione un producto</option>
                                <?php foreach ($productos as $id => $nom) : ?>
                                    <option value="<?php echo $id; ?>" <?php echo ($producto_id == $id) ? 'selected' : ''; ?>><?php echo $nom; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $producto_id_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Cantidad deseada</label>
                            <input type="text" name="cant_ordencompra" class="form-control <?php echo (!empty($cant_ordencompra_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cant_ordencompra; ?>">
                            <span class="invalid-feedback"><?php echo $cant_ordencompra_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Precio Unitario</label>
                            <input type="text" name="precio_unitario" class="form-control <?php echo (!empty($precio_unitario_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $precio_unitario; ?>">
                            <span class="invalid-feedback"><?php echo $precio_unitario_err; ?></span>
                        </div>
                        <input type="hidden" name="id_DetalleOrden" value="<?php echo $id_DetalleOrden; ?>" />
                        <input type="submit" class="btn btn-success" value="Actualizar">
                        <a href="../Vista/orden-compra.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>