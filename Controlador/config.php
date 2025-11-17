<?php
// Credenciales de la base de datos (usados con sql server)
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'inventario');
// Conectar con la base de datos
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD,
DB_NAME);
// Verificar conexion
if($link === false){
die("ERROR: Could not connect. " .
mysqli_connect_error());
}
?>
