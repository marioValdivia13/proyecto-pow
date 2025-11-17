<?php
session_start();
$_SESSION = array();
session_destroy();
header("location: ../Controlador/login.php");
exit;
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            body {
                background-image: url(../img/fondo_dos.jpg);
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: 100% 100%;
            }
        </style>
    </head>
</html>
