<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Listado de Proveedores</title>
    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script
    src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min
    .js"></script>
    <script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
        width: 600px;
        margin: 0 auto;
        border: solid;
        background-color: white;
        }
        table tr td:last-child{
        width: 120px;
        border: solid;
        }
        body {
            background-image: url(../img/fondo_proveedor.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
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
                <h2 class="pull-left">Detalle de los proveedores</h2>
                <a href="../Modelo/create_proveedor.php" class="btn btn-success
                pull-right"><i class="fa fa-plus"></i> Agregar nuevo proveedor</a>
                </div>
            <?php
            require_once "../Controlador/config.php";
            $sql = "SELECT * FROM proveedor";
            if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                    echo '<table class="table table-bordered
                    table-striped">';
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>#</th>";
                    echo "<th>Nombre</th>";
                    echo "<th>Telefono</th>";
                    echo "<th>Direccion</th>";
                    echo "<th>Accion</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td>" . $row['id_proveedor'] . "</td>";
                        echo "<td>" . $row['nom_proveedor'] . "</td>";
                        echo "<td>" . $row['fono_proveedor'] . "</td>";
                        echo "<td>" . $row['dir_proveedor'] . "</td>";
                        echo "<td>";
                        echo '<a href="../Modelo/update_proveedor.php?id='. $row['id_proveedor'] .'" class="mr-3" title="Actualizar Proveedor" 
                        data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                        echo '<a href="../Modelo/delete_proveedor.php?id='. $row['id_proveedor'] .'" title="Eliminar Proveedor" 
                        data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    mysqli_free_result($result);
                } else{
                    echo '<div class="alert alert-danger"><em>No hay registros.</em></div>';
                }
            } else{
                echo "Oops! Algo ha salido mal, intentalo mas tarde.";
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
    <a href="../Vista/Welcome.php" class="btn btn-primary ml-3">Atras</a>
</body>