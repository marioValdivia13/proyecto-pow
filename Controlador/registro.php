<?php
require_once "../Controlador/config.php";
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validar username
    if(empty(trim($_POST["username"]))){
        $username_err = "Ingrese el nombre de usuario que desea usar";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/',trim($_POST["username"]))){
        $username_err = "El nombre de usuario solo puede contener letras, numeros y guiones bajo";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "El nombre de usuario ya esta en uso.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
            echo "Oops! Algo ha ocurrido, vuelve a intentarlo mas tarde.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    if(empty(trim($_POST["password"]))){
        $password_err = "Ingresa tu contraseña.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña debe tener 6 caracteres o mas.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Validar la confirmacion de la contraseña
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor confirma tu contraseña.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Contraseñas no coinciden.";
        }
    }
    // Buscar errores antes de ingresar a la base de datos
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "ss",
            $param_username, $param_password);

            $param_username = $username;
            $param_password = password_hash($password,
            PASSWORD_DEFAULT); // Crear el hash de la contraseña

            if(mysqli_stmt_execute($stmt)){
                // Redirigir al login
                header("location: ../Controlador/login.php");
            } else{
                echo "Oops! Algo ha ocurrido, intentalo mas tarde.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    // Cerrar conexión
    mysqli_close($link);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title> Registrarse</title>
<link rel="stylesheet"
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
body{ 
    font: 14px sans-serif;
    background-image: url(../img/fondo_uno.jpg);
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: 100% 100%; 
}
.saltito{ 
        width: 550px; padding: 110px; 
        background-color: Cornsilk;
        opacity: 0;
        border-style: none;
}
.wrapper{ 
    width: 360px; padding: 25px; 
    background-color: white;
    }
</style>
</head>
<body>
    <center>
        <div class="saltito">
        </div>
        <div class="wrapper">
            <h2>Registrarse</h2>
            <p>Por favor, completa el formulario para crear tu cuenta.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err))? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Ingresar">
                    <input type="reset" class="btn btn-secondary ml-2" value="Borrar Todo">
                </div>
                <p>¿Tienes una cuenta? <a href="login.php">Ingresa aquí</a>.</p>
            </form>
        </div>
    </center>
</body>
</html>