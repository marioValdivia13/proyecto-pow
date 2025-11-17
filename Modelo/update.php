<?php
require_once "../Controlador/config.php";
$nom_producto = $descrip_producto = $precio = $stock_real = $stock_minimo = "";
$nom_producto_err = $descrip_producto_err = $precio_err = $stock_real_err = $stock_minimo_err = "";
if(isset($_POST["id_producto"]) && !empty($_POST["id_producto"])){
$id_producto = $_POST["id_producto"];
// Validar nombre
$input_nom_producto = trim($_POST["nom_producto"]);
if(empty($input_nom_producto)){
$nom_producto_err = "Ingresa el nombre del producto.";
} else{
$nom_producto = $input_nom_producto;
}
//Validar tipo de producto
$input_descrip_producto = trim($_POST["descrip_producto"]);
if(empty($input_descrip_producto)){
$descrip_producto_err = "Ingresa el tipo del producto.";
} else{
$descrip_producto = $input_descrip_producto;
}
// Validar Precio
$input_precio = trim($_POST["precio"]);
if(empty($input_precio)){
$precio_err = "Ingresa el precio del producto.";
} elseif(!ctype_digit($input_precio)){
$precio_err = "Ingrese un numero positivo.";
} else{
$precio = $input_precio;
}
// Validar stock maximo
$input_stock_real = trim($_POST["stock_real"]);
if(empty($input_stock_real)){
$stock_real_err = "Ingresa el precio del producto.";
} elseif(!ctype_digit($input_stock_real)){
$stock_real_err = "Ingrese un numero positivo.";
} else{
$stock_real = $input_stock_real;
}
// Validar stock critico
$input_stock_minimo = trim($_POST["stock_minimo"]);
if(empty($input_stock_minimo)){
$stock_minimo_err = "Ingresa el precio del producto.";
} elseif(!ctype_digit($input_stock_minimo)){
$stock_minimo_err = "Ingrese un numero positivo.";
} else{
$stock_minimo = $input_stock_minimo;
}
if(empty($nom_producto_err) && empty($descrip_producto_err) && empty($precio_err) && empty($stock_real_err) && empty($stock_minimo_err)){
$sql = "UPDATE producto SET nom_producto=?, descrip_producto=?, precio=?, stock_real=?, stock_minimo=? WHERE
id_producto=?";
if($stmt = mysqli_prepare($link, $sql)){
mysqli_stmt_bind_param($stmt, "sssssi", $param_nom_producto, $param_descrip_producto, $param_precio, $param_stock_real, $param_stock_minimo, $param_id_producto);
$param_nom_producto = $nom_producto;
$param_descrip_producto = $descrip_producto;
$param_precio = $precio;
$param_stock_real = $stock_real;
$param_stock_minimo = $stock_minimo;
$param_id_producto = $id_producto;
if(mysqli_stmt_execute($stmt)){
    // Producto actualizado, redirigiendo a la tabla productos.
    header("location: ../Vista/productos.php");
    exit();
    } else{
    echo "Oops! Hubo un problema inesperado, intentalo mas tarde.";
    }
    }
    mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
    } else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id_producto = trim($_GET["id"]);
    $sql = "SELECT * FROM producto WHERE id_producto = ?";
    if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $param_id_producto);
    $param_id_producto = $id_producto;
    if(mysqli_stmt_execute($stmt)){
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) == 1){
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $nom_producto = $row["nom_producto"];
    $descrip_producto = $row["descrip_producto"];
    $precio = $row["precio"];
    $stock_real = $row["stock_real"];
    $stock_minimo = $row["stock_minimo"];
} else{
    header("location: ../Vista/error.php");
    exit();
    }
    } else{
    echo "Oops! Algo ha ocurrido, intentalo mas tarde.";
    }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    } else{
    header("location: ../Vista/error.php");
    exit();
    }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Actualizar Productos</title>
    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .wrapper{
    width: 360px;
    padding: 20px;
    margin: 0 auto;
    background-color: white;
    }
    body {
        background-image: url(../img/fondo_plato.jpg);
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
    <h2 class="mt-5">Actualizar Productos</h2>
    <p>Por favor, actualiza los productos para guardarlos.</p>
<form action="<?php echo
htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>"
method="post">
<div class="form-group">
<label>Nombre</label>
<input type="text" name="nom_producto"
class="form-control <?php echo (!empty($nom_producto_err)) ? 'is-invalid' :
''; ?>" value="<?php echo $nom_producto; ?>">
<span class="invalid-feedback"><?php echo
$nom_producto_err;?></span>
</div>
<div class="form-group">
<label>Tipo</label>
<textarea name="descrip_producto"
class="form-control <?php echo (!empty($descrip_producto_err)) ? 'is-invalid' :
''; ?>"><?php echo $descrip_producto; ?></textarea>
<span class="invalid-feedback"><?php echo
$descrip_producto_err;?></span>
</div>
<div class="form-group">
<label>Precio</label>
<input type="text" name="precio"
class="form-control <?php echo (!empty($precio_err)) ? 'is-invalid' :
''; ?>" value="<?php echo $precio; ?>">
<span class="invalid-feedback"><?php echo
$precio_err;?></span>
</div>
<div class="form-group">
<label>Stock Actual</label>
<input type="text" name="stock_real"
class="form-control <?php echo (!empty($stock_real_err)) ? 'is-invalid' :
''; ?>" value="<?php echo $stock_real; ?>">
<span class="invalid-feedback"><?php echo
$stock_real_err;?></span>
</div>
<div class="form-group">
<label>Stock Minimo</label>
<input type="text" name="stock_minimo"
class="form-control <?php echo (!empty($stock_minimo_err)) ? 'is-invalid' :
''; ?>" value="<?php echo $stock_minimo; ?>">
<span class="invalid-feedback"><?php echo
$stock_minimo_err;?></span>
</div>
<input type="hidden" name="id_producto" value="<?php
echo $id_producto; ?>"/>
<input type="submit" class="btn btn-success"
value="Actualizar">
<a href="../Vista/productos.php" class="btn btn-secondary
ml-2">Cancelar</a>
</form>
</div>
</div>
</div>
</div>
</body>
</html>