<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_producto"])){
require_once "../Controlador/config.php";
$sql = "DELETE FROM producto WHERE id_producto = ?";
if($stmt = mysqli_prepare($link, $sql)){
mysqli_stmt_bind_param($stmt, "i", $param_id_producto);
$param_id_producto = trim($_POST["id_producto"]);
if(mysqli_stmt_execute($stmt)){
// Se borro el producto, redirigiendo a pagina de productos
header("location: ../Vista/productos.php");
exit();
} else{
    echo "Error en la ejecución de la consulta: " . mysqli_error($link);;
}
}
mysqli_stmt_close($stmt);
mysqli_close($link);
} else{
// Revisa la existencia de la id
if(empty(trim($_GET["id"]))){
// URL no existe, redirigiendo a pagina de error
//echo "ID no presente en la URL";
header("location: ../Vista/error.php");
exit();
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Borrar Productos</title>
<link rel="stylesheet"
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap
.min.css">
<style>
.wrapper{
width: 600px;
margin: 0 auto;
}
body {
    background-image: url(../Img/fondo_plato.jpg);
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: 100% 100%;
}
h2 {
    color: white;
}
</style>
</head>
<body>
<div class="wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<h2 class="mt-5 mb-3">Borrar Productos</h2>
<form action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="alert alert-danger">
<input type="hidden" name="id_producto"
value="<?php echo trim($_GET["id"]); ?>"/>
<p>¿Estas seguro de querer eliminar este producto?</p>
<p>
<input type="submit" value="Si"
class="btn btn-danger">
<a href="../View/productos.php" class="btn
btn-secondary">No</a>
</p>
</div>
</form>
</div>
</div>
</div>
</div>
</body>
</html>