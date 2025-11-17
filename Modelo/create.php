<?php
require_once "../Controlador/config.php";
$nom_producto = $descrip_producto = $precio = $stock_real = $stock_minimo = "";
$nom_producto_err = $descrip_producto_err = $precio_err = $stock_maximo_err = $stock_minimo_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
// Validar nombre de producto
$input_nom_producto = trim($_POST["nom_producto"]);
if(empty($input_nom_producto)){
$nom_producto_err = "Ingresa nombre del producto.";
} else{
$nom_producto = $input_nom_producto;
}
// Validar tipo de producto
$input_descrip_producto = trim($_POST["descrip_producto"]);
if(empty($input_descrip_producto)){
$descrip_producto_err = "Ingresa el tipo del producto.";
} else{
$descrip_producto = $input_descrip_producto;
}
// Validar precio del producto
$input_precio = trim($_POST["precio"]);
if(empty($input_precio)){
$precio_err = "Ingrese el precio del producto.";
} elseif(!ctype_digit($input_precio)){
$precio_err = "Ingrese un numero positivo.";
} else{
$precio = $input_precio;
}
//Validar stock maximo
$input_stock_real = trim($_POST["stock_real"]);
if(empty($input_stock_real)){
$stock_maximo_err = "Ingrese el stock del producto.";
} elseif(!ctype_digit($input_stock_real)){
$stock_maximo_err = "Ingrese un numero positivo.";
} else{
$stock_real = $input_stock_real;
}
//Validar stock critico
$input_stock_minimo = trim($_POST["stock_minimo"]);
if(empty($input_stock_minimo)){
$stock_minimo_err = "Ingrese el stock minimo del producto.";
} elseif(!ctype_digit($input_stock_minimo)){
$stock_minimo_err = "Ingrese un numero positivo.";
} else{
$stock_minimo = $input_stock_minimo;
}
if(empty($nom_producto_err) && empty($descrip_producto_err) && empty($precio_err) && empty($stock_maximo_err) && empty($stock_minimo_err)){
$sql = "INSERT INTO producto (nom_producto, descrip_producto, precio, stock_real, stock_minimo) VALUES
(?, ?, ?, ?, ?)";
if($stmt = mysqli_prepare($link, $sql)){
mysqli_stmt_bind_param($stmt, "sssss", $param_nom_producto,
$param_descrip_producto, $param_precio, $param_stock_real, $param_stock_minimo);
$param_nom_producto = $nom_producto;
$param_descrip_producto = $descrip_producto;
$param_precio = $precio;
$param_stock_real = $stock_real;
$param_stock_minimo = $stock_minimo;
if(mysqli_stmt_execute($stmt)){
// Creacion del producto completada, redirigiendo a la tabla productos
header("location: ../Vista/productos.php");
exit();
} else{
echo "Oops! Ocurrio un imprevisto, intentelo mas tarde.";
}
}
mysqli_stmt_close($stmt);
}
mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Ingresar Producto</title>
<link rel="stylesheet"
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap
.min.css">
<style>
.wrapper{
width: 360px;
padding: 20px;
margin: 0 auto;
background-color: white;
}
body {
    background-image: url(../Img/fondo_plato.jpg);
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
<h2 class="mt-5">Ingresar Producto</h2>
<p>Rellene el formulario para ingresar el producto a la base de datos.</p>
<form action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="form-group">
<label>Nombre</label>
<input type="text" name="nom_producto"
class="form-control <?php echo (!empty($nom_producto_err)) ? 'is-invalid' :
''; ?>" value="<?php echo $nom_producto; ?>">
<span class="invalid-feedback"><?php echo
$nom_producto_err;?></span>
</div>
<div class="form-group">
<label>Tipo de producto</label>
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
class="form-control <?php echo (!empty($stock_maximo_err)) ? 'is-invalid' :
''; ?>" value="<?php echo $stock_real; ?>">
<span class="invalid-feedback"><?php echo
$stock_maximo_err;?></span>
</div>
<div class="form-group">
<label>Stock Critico</label>
<input type="text" name="stock_minimo"
class="form-control <?php echo (!empty($stock_minimo_err)) ? 'is-invalid' :
''; ?>" value="<?php echo $stock_minimo; ?>">
<span class="invalid-feedback"><?php echo
$stock_minimo_err;?></span>
</div>
<input type="submit" class="btn btn-primary"
value="Agregar">
<a href="../Vista/productos.php" class="btn btn-secondary
ml-2">Cancelar</a>
</form>
</div>
</div>
</div>
</div>
</body>
</html>
