<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Detalles de Órdenes de Compra</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            width: 800px;
            margin: 0 auto;
            border: solid;
            background-color: white;
            padding: 20px;
        }

        table tr td:last-child {
            width: 120px;
            border: solid;
        }

        body {
            background-image: url(../img/fondo_ordencompra.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }
    </style>
    <script>
        $(document).ready(function () {
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
                        <h2 class="pull-left">Detalle de Órdenes de Compra</h2>
                        <a href="../Modelo/create_orden.php" class="btn btn-success
                        pull-right"><i class="fa fa-plus"></i> Agregar nuevo producto</a>
                    </div>
                    <?php
                    require_once "../Controlador/config.php";
                    $sql = "SELECT * FROM detordencompra";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>ID Orden</th>";
                            echo "<th>ID Proveedor</th>";
                            echo "<th>ID Producto</th>";
                            echo "<th>Cantidad</th>";
                            echo "<th>Precio Unitario</th>";
                            echo "<th>Subtotal</th>";
                            echo "<th> Acción </th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id_DetalleOrden'] . "</td>";
                                echo "<td>" . $row['id_proveedor'] . "</td>";
                                echo "<td>" . $row['id_producto'] . "</td>";
                                echo "<td>" . $row['cant_ordencompra'] . "</td>";
                                echo "<td>" . $row['precio_unitario'] . "</td>";
                                echo "<td>" . $row['subtotal_orden'] . "</td>";
                                echo "<td>";
                                echo '<a href="../Modelo/update_orden.php?id=' . $row['id_DetalleOrden'] . '" class="mr-3" title="Actualizar Detalle" 
                                    data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                echo '<a href="../Modelo/delete_orden.php?id=' . $row['id_DetalleOrden'] . '" title="Eliminar Detalle" 
                                    data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            mysqli_free_result($result);
                        } else {
                            echo '<div class="alert alert-danger"><em>No hay detalles de órdenes de compra.</em></div>';
                        }
                    } else {
                        echo "Oops! Algo ha salido mal, intentalo más tarde.";
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
    <p></p>
    <a href="../Vista/Welcome.php" class="btn btn-primary ml-3">Atrás</a>
</body>

</html>