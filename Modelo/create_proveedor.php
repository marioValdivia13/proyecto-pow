<?php
require_once "../Controlador/config.php";
$nom_proveedor = $fono_proveedor = $dir_proveedor = "";
$nom_proveedor_err = $fono_proveedor_err = $dir_proveedor_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validar nombre del proveedor
    $input_nom_proveedor = trim($_POST["nom_proveedor"]);
    if(empty($input_nom_proveedor)){
        $nom_proveedor_err = "Ingresa el nombre del proveedor.";
    } else{
        $nom_proveedor = $input_nom_proveedor;
    }

    // Validar teléfono del proveedor
    $input_fono_proveedor = trim($_POST["fono_proveedor"]);
    if(empty($input_fono_proveedor)){
        $fono_proveedor_err = "Ingresa el teléfono del proveedor.";
    } else{
        $fono_proveedor = $input_fono_proveedor;
    }

    // Validar dirección del proveedor
    $input_dir_proveedor = trim($_POST["dir_proveedor"]);
    if(empty($input_dir_proveedor)){
        $dir_proveedor_err = "Ingresa la dirección del proveedor.";
    } else{
        $dir_proveedor = $input_dir_proveedor;
    }

    if(empty($nom_proveedor_err) && empty($fono_proveedor_err) && empty($dir_proveedor_err)){
        $sql = "INSERT INTO proveedor (nom_proveedor, fono_proveedor, dir_proveedor) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_nom_proveedor, $param_fono_proveedor, $param_dir_proveedor);
            $param_nom_proveedor = $nom_proveedor;
            $param_fono_proveedor = $fono_proveedor;
            $param_dir_proveedor = $dir_proveedor;

            if(mysqli_stmt_execute($stmt)){
                // Creación del proveedor completada, redirigiendo a la tabla de proveedores
                header("location: ../Vista/proveedores.php");
                exit();
            } else{
                echo "Oops! Ocurrió un imprevisto, inténtelo más tarde.";
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
    <title>Ingresar Proveedor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 360px;
            padding: 20px;
            margin: 0 auto;
            background-color: white;
        }
        body {
            background-image: url(../Img/fondo_proveedor.jpg);
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
                    <h2 class="mt-5">Ingresar Proveedor</h2>
                    <p>Rellene el formulario para ingresar el proveedor a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nombre del Proveedor</label>
                            <input type="text" name="nom_proveedor" class="form-control <?php echo (!empty($nom_proveedor_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nom_proveedor; ?>">
                            <span class="invalid-feedback"><?php echo $nom_proveedor_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Teléfono del Proveedor</label>
                            <input type="text" name="fono_proveedor" class="form-control <?php echo (!empty($fono_proveedor_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fono_proveedor; ?>">
                            <span class="invalid-feedback"><?php echo $fono_proveedor_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Dirección del Proveedor</label>
                            <textarea name="dir_proveedor" class="form-control <?php echo (!empty($dir_proveedor_err)) ? 'is-invalid' : ''; ?>"><?php echo $dir_proveedor; ?></textarea>
                            <span class="invalid-feedback"><?php echo $dir_proveedor_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Agregar">
                        <a href="../Vista/proveedores.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>