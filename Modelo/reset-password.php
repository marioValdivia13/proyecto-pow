<?php
// Iniciar sesion
session_start();
//Ve si esta logeado, si no lo esta, lo redirecciona al login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !==
true){
header("location: ../Controlador/login.php");
exit;
}
require_once "../Controlador/config.php";
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validar nueva contraseña
    if(empty(trim($_POST["new_password"]))){
    $new_password_err = "Ingresa tu nueva contraseña.";
    } elseif(strlen(trim($_POST["new_password"])) < 6){
    $new_password_err = "La contraseña debe tener 6 caracteres o mas.";
    } else{
    $new_password = trim($_POST["new_password"]);
    }
    // Validar nueva contraseña 
    if(empty(trim($_POST["confirm_password"]))){
    $confirm_password_err = "Confirma tu contraseña.";
    } else{
    $confirm_password = trim($_POST["confirm_password"]);
    if(empty($new_password_err) && ($new_password !=
    $confirm_password)){
    $confirm_password_err = "Las contraseñas no coinciden.";
    }
    }
    if(empty($new_password_err) &&
    empty($confirm_password_err)){
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "si",
    $param_password, $param_id);
    $param_password = password_hash($new_password,
    PASSWORD_DEFAULT);
    $param_id = $_SESSION["id"];
    if(mysqli_stmt_execute($stmt)){
        // Contraseña actualizada correctamente, cerrando sesion y redirigiendo al login
        session_destroy();
        header("location: ../Controlador/login.php");
        exit();
        } else{
        echo "Oops! ocurrio un problema inesperado, intentalo de nuevo mas tarde.";
        }
        mysqli_stmt_close($stmt);
        }
        }
        mysqli_close($link);
        }
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Actualizar contraseña</title>
<link rel="stylesheet"
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    body{ 
        font: 14px sans-serif; 
    }
    .wrapper{ 
        width: 360px; padding: 20px;
        background-color: white; 
    }
    body {
        background-image: url(../img/fondo_dos.jpg);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 100% 100%;
    }
</style>
</head>
<body>
    <center>
    <div class="wrapper">
        <h2>Actualizar Contraseña</h2>
        <p>Rellene este formulario para actualizar su contraseña.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirmar Contraseña</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err))? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Cambiar Contraseña">
                <a class="btn btn-link ml-2" href="../Vista/welcome.php">Cancelar</a>
            </div>
        </form>
    </div>
    </center>
</body>
</html>    