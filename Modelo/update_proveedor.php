<?php
require_once "../Controlador/config.php";
$nom_proveedor = $fono_proveedor = $dir_proveedor = "";
$nom_proveedor_err = $fono_proveedor_err = $dir_proveedor_err = "";

if(isset($_POST["id_proveedor"]) && !empty($_POST["id_proveedor"])){
    $id_proveedor = $_POST["id_proveedor"];

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
        $sql = "UPDATE proveedor SET nom_proveedor=?, fono_proveedor=?, dir_proveedor=? WHERE id_proveedor=?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_nom_proveedor, $param_fono_proveedor, $param_dir_proveedor, $param_id_proveedor);
            $param_nom_proveedor = $nom_proveedor;
            $param_fono_proveedor = $fono_proveedor;
            $param_dir_proveedor = $dir_proveedor;
            $param_id_proveedor = $id_proveedor;

            if(mysqli_stmt_execute($stmt)){
                // Proveedor actualizado, redirigiendo a la tabla de proveedores.
                header("location: ../Vista/proveedores.php");
                exit();
            } else{
                echo "Oops! Hubo un problema inesperado, inténtalo más tarde.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id_proveedor = trim($_GET["id"]);
        $sql = "SELECT * FROM proveedor WHERE id_proveedor = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id_proveedor);
            $param_id_proveedor = $id_proveedor;

            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $nom_proveedor = $row["nom_proveedor"];
                    $fono_proveedor = $row["fono_proveedor"];
                    $dir_proveedor = $row["dir_proveedor"];
                } else{
                    header("location: ../Vista/error.php");
                    exit();
                }
            } else{
                echo "Oops! Algo ha ocurrido, inténtalo más tarde.";
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Proveedor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 360px;
            padding: 20px;
            margin: 0 auto;
            background-color: white;
        }
        body {
            background-image: url(../img/fondo_proveedor.jpg);
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
                    <h2 class="mt-5">Actualizar Proveedor</h2>
                    <p>Por favor, actualiza los datos del proveedor para guardarlos.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                        <input type="hidden" name="id_proveedor" value="<?php echo $id_proveedor; ?>"/>
                        <input type="submit" class="btn btn-success" value="Actualizar">
                        <a href="../Vista/proveedores.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>