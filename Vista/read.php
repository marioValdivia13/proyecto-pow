<?php
// Comprueba la existencia del id
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Incluye config file
    require_once "../Controlador/config.php";
    // Select a la tabla de productos
    $sql = "SELECT * FROM producto WHERE id_producto = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        // Establece la id
        $param_id = trim($_GET["id"]);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                //Obtener la fila de resultados como array
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                // Recuperar el valor de cada campo
                $name = $row["nom_producto"];
                $type = $row["descrip_producto"];
                $price = $row["precio"];
            } else{
                // URL no contiene parametro ID, redireccionando a pagina de error
                header("location: ../Vista/error.php");
                exit();
            }
        } else{
        echo "Algo salio mal, intentar mas tarde.";
        }
    }
    // Cerrar declaracion
    mysqli_stmt_close($stmt);
    // Cerrar conexion
    mysqli_close($link);
} else{
    // URL no contiene parametro ID, redireccionando a pagina de error
    header("location: ../Vista/error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Ver productos</title>
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
    background-image: url(../img/fondo_uno.jpg);
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
                    <h1 class="mt-5 mb-3">Ver productos</h1>
                    <div class="form-group">
                        <label>Nombre</label>
                        <p><b><?php echo $row["nom_producto"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <p><b><?php echo $row["descrip_producto"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Precio</label>
                        <p><b><?php echo $row["precio"]; ?></b></p>
                    </div>
                    <p><a href="../Vista/productos.php" class="btn btn-primary">Atras</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
