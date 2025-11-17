<?php
session_start();
// Revisa si el usuario esta logeado, si no lo esta lo redirige al login.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !==
true){
header("location: ../Controlador/login.php");
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bienvenido</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ 
            font: 14px sans-serif; text-align: center;
            background-image: url(../img/fondo_bienvenida.jpg);
            
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }
        .saltito{ 
                width: 550px; padding: 100px; 
                background-color: Cornsilk;
                opacity: 0;
                border-style: none;
        }
        .wrapper{ 
                width: 360px; padding: 10px; 
                color: white;
                text-shadow: 2px 2px black;
        }

    </style>
</head>
<body>
<center>
    <div class="wrapper">
    <h1 class="my-5">Bienvenido, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
    <p>
    </div>
    <!--redirecciona a tabla de productos -->
    <a href="../Vista/productos.php" class="btn btn-success">Ir a Productos</a>
    <p>
    </p>
    <!--redirecciona a tabla de proveedores -->
    <a href="../Vista/proveedores.php" class="btn btn-success">Ir a Proveedores</a>
    <p>
    </p>
    <!--redirecciona a tabla de ordenes de compra -->
    <a href="../Vista/orden-compra.php" class="btn btn-success">Ir a Ordenes de compra</a>
    <p>
    </p>
    <!--redirecciona a la actualizacion de la contraseña -->
    <a href="../Modelo/reset-password.php" class="btn btn-success">Actualizar Contraseña</a>
    <p>
    </p>
    <!--cierre de sesion -->
    <a href="../Modelo/logout.php" class="btn btn-danger ml-3">Cerrar sesion</a>
    </p>


</center>
</body>
</html>