<?php
session_start();
$id_usuario = $_SESSION["id"];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Historial de Ventas</title>
    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script
    src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
        width: 900px;
        margin: 0 auto;
        border: solid;
        background-color: white;
        }
        table tr td:last-child{
        width: 120px;
        border: solid;
        }
        body {
            background-image: url(../img/fondo_plato.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }
        .stock-bajo {
            background-color: #ffcccc;
        }
        .stock-critico {
            background-color: #ff9999;
            font-weight: bold;
        }
    </style>
    <script>
        $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mt-5 mb-3 clearfix">
                            <h2 class="pull-left">Historial de Ventas</h2>
                            <a href="../Modelo/create_venta.php" class="btn btn-success pull-right">
                                <i class="fa fa-plus"></i> Nueva Venta
                            </a>
                            <!-- BOTÓN PARA VOLVER A PRODUCTOS -->
                            <a href="../Vista/productos.php" class="btn btn-info ml-2 pull-right">
                                <i class="fa fa-cubes"></i> Ver Productos
                            </a>
                        </div>
                        
                        <!-- ESTADÍSTICAS RÁPIDAS -->
                        <?php
                        require_once "../Controlador/config.php";
                        
                        // Obtener estadísticas
                        $sql_estadisticas = "SELECT 
                            COUNT(*) as total_ventas,
                            SUM(subtotal) as total_ingresos,
                            AVG(subtotal) as promedio_venta
                            FROM venta";
                        
                        $estadisticas = mysqli_fetch_assoc(mysqli_query($link, $sql_estadisticas));
                        ?>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-white bg-primary">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Ventas</h5>
                                        <h3><?php echo $estadisticas['total_ventas']; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Ingresos</h5>
                                        <h3>$<?php echo number_format($estadisticas['total_ingresos']); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-info">
                                    <div class="card-body">
                                        <h5 class="card-title">Promedio por Venta</h5>
                                        <h3>$<?php echo number_format($estadisticas['promedio_venta']); ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                        $sql = "SELECT 
                                v.id_venta,
                                v.fecha,
                                u.username as vendedor,
                                p.nom_producto,
                                p.descrip_producto,
                                v.cantidad,
                                v.precio_venta,
                                v.subtotal,
                                p.stock_real,
                                p.stock_minimo
                            FROM venta v 
                            JOIN users u ON v.id = u.id 
                            JOIN producto p ON v.id_producto = p.id_producto 
                            ORDER BY v.fecha DESC, v.id_venta DESC";
                        
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th># Venta</th>";
                                echo "<th>Fecha</th>";
                                echo "<th>Vendedor</th>";
                                echo "<th>Producto</th>";
                                echo "<th>Tipo</th>";
                                echo "<th>Cantidad</th>";
                                echo "<th>Precio Unitario</th>";
                                echo "<th>Subtotal</th>";
                                echo "<th>Stock Actual</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                
                                while($row = mysqli_fetch_array($result)){
                                    // Determinar clase CSS para stock
                                    $clase_stock = "";
                                    if ($row['stock_real'] <= $row['stock_minimo']) {
                                        $clase_stock = "stock-critico";
                                    } elseif ($row['stock_real'] <= ($row['stock_minimo'] * 2)) {
                                        $clase_stock = "stock-bajo";
                                    }
                                    
                                    echo "<tr>";
                                    echo "<td>" . $row['id_venta'] . "</td>";
                                    echo "<td>" . $row['fecha'] . "</td>";
                                    echo "<td>" . $row['vendedor'] . "</td>";
                                    echo "<td>" . $row['nom_producto'] . "</td>";
                                    echo "<td>" . $row['descrip_producto'] . "</td>";
                                    echo "<td>" . $row['cantidad'] . "</td>";
                                    echo "<td>$" . number_format($row['precio_venta']) . "</td>";
                                    echo "<td>$" . number_format($row['subtotal']) . "</td>";
                                    echo "<td class='$clase_stock'>" . $row['stock_real'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                                mysqli_free_result($result);
                            } else{
                                echo '<div class="alert alert-info"><em>No hay ventas registradas todavía.</em></div>';
                            }
                        } else{
                            echo '<div class="alert alert-danger">Oops! Algo ha salido mal, inténtalo más tarde.</div>';
                        }
                        mysqli_close($link);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            body{ font: 17px sans-serif; text-align: center; }
        </style>
        <p>
        </p>
        <a href="../Vista/Welcome.php" class="btn btn-primary ml-3">Atrás</a>
    </body>
</html>