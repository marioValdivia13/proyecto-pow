<?php
// Inicia sesion
session_start();
// Se incluye el archivo de configuración
require_once "../Controlador/config.php";
// Define variables y las inicia vacias
$username = $password = "";
$username_err = $password_err = $login_err = "";
// Procesa los datos del formulario anterior
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Revisa si el username esta vacio
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor, ingresa tu nombre de usuario.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Revisa si la contraseña esta vacia
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, ingresa tu contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Valida las credenciales
    if(empty($username_err) && empty($password_err)){
        // Hace un select a la base de datos
        $sql = "SELECT id, username, password FROM users WHERE
        username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s",
            $param_username);
            $param_username = $username;
            $hashed_password = "";
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                // Verifica si el usuario existe, si es asi, verifica la contraseña
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Contraseña correcta, inicia sesion
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            // Redirecciona al usuario a la pagina de welcome
                            header("location: ../Vista/welcome.php");
                        } else{
                            // Contraseña incorrecta, enseña mensaje
                            $login_err = "Usuario o contraseña incorrecta.";
                        }
                    }
                } else{
                    // Si el nombre de usuario no existe, muestra mensaje
                    $login_err = "Usuario o contraseña invalida.";
                }
            } else{
            echo "Oops! Algo salio mal, intenalo de nuevo mas tarde.";
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
    <title>Ingresar</title>
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
        <h2>Ingreso</h2>
        <p>Ingresa tus datos para loguearte.</p>
<?php
if(!empty($login_err)){
    echo '<div class="alert alert-danger">' .
    $login_err . '</div>';
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group">
        <label>Nombre de usuario</label>
        <input type="text" name="username"
        class="form-control <?php echo (!empty($username_err)) ?
        'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
    </div>
    <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password"
        class="form-control <?php echo (!empty($password_err)) ?
        'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Ingresar">
    </div>
    <p>¿No tienes una cuenta aún? <a
    href="registro.php">Registrate aquí</a>.</p>
</form>
    </div>
</center>
</body>
</html>
