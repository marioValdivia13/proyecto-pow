<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_proveedor"])){
    require_once "../Controlador/config.php";
    $sql = "DELETE FROM proveedor WHERE id_proveedor = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id_proveedor);
        $param_id_proveedor = trim($_POST["id_proveedor"]);
        if(mysqli_stmt_execute($stmt)){
            // Se eliminó el proveedor, redirigiendo a la página de proveedores
            header("location: ../Vista/proveedores.php");
            exit();
        } else{
            echo "Error en la ejecución de la consulta: " . mysqli_error($link);
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else{
    // Revisa la existencia de la id
    if(empty(trim($_GET["id"]))){
        // URL no existe, redirigiendo a la página de error
        header("location: ../Vista/error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar Proveedor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        body {
            background-image: url(../Images/fondo_proveedor.jpg);
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
                    <h2 class="mt-5 mb-3">Borrar Proveedor</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id_proveedor" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>¿Estás seguro de querer eliminar este proveedor?</p>
                            <p>
                                <input type="submit" value="Sí" class="btn btn-danger">
                                <a href="../Vista/proveedores.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>