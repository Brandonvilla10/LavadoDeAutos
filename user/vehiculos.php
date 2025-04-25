<?php

require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if (!isset($_SESSION['documento'])) {
    header("Location: ../index.php");
    exit();
}

$nitEmpresa = $_SESSION['nitEmpresa'];


if (isset($_POST['eliminar'])) {

    $eliminar = $con->prepare("DELETE FROM vehiculo WHERE placa = :placa");
    $eliminar->bindParam(':placa', $_POST['eliminar']);
    $eliminar->execute();
}



?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario</title>
    <link rel="stylesheet" href="./css/usuarios.css">
</head>

<body>

    <div>
        <a href="./index.php">Volver</a>

        
    </div>






    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>Placa</th>
                <th>marca</th>
                <th>Documento</th>
                <th>Nombre Del Cliente</th>
                <th>Codigo De Barras</th>
                <th>eliminar </th>
                <th>editar</th>

            </tr>
        </thead>
        <tbody>

            <?php
            $usuario = $con->prepare("SELECT * FROM `vehiculo` 
                            INNER JOIN marca ON marca.id_marca = vehiculo.id_marca
                            INNER JOIN usuario on usuario.documento = vehiculo.id_documento
                            WHERE usuario.id_empresa = $nitEmpresa");
            $usuario->execute();

            $filas = $usuario->fetchAll(PDO::FETCH_ASSOC);

            foreach ($filas as $index => $fila) {


            ?>


                <tr>
                    <td><?php echo $fila['placa'] ?></td>
                    <td><?php echo $fila['nombreMarca'] ?></td>
                    <td><?php echo $fila['documento'] ?></td>
                    <td><?php echo $fila['nombreCompleto'] ?></td>
                    <td><canvas id="barcode<?= $index ?>"></canvas></td>
                    <td>
                        <form action="" method="post">
                            <button style="background: red; color:white; border:none;" value="<?php echo $fila['placa'] ?>" name="eliminar" type="submit">Eliminar</button>
                        </form>
                    </td>

                    <td>
                        <a href="editarVehiculo.php?placa=<?= $fila['placa'] ?>">Editar</a>

                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

    
    <script src="./js/modal.js"></script>

    <script src="./jsBarCode.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
    window.onload = function () {
        <?php foreach ($filas as $index => $fila) { ?>
            JsBarcode("#barcode<?= $index ?>", "<?= $fila['codigoBarras'] ?>", {
                format: "CODE128",
                lineColor: "#0aa",
                width: 2,
                height: 60,
                displayValue: true
            });
        <?php } ?>
    };
</script>

</body>

</html>