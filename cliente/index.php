<?php
include('../config/conex.php');
$conexion = new Database;
$con = $conexion->conectar();
session_start();

$documento = $_SESSION['documento'];

$datos = $con->prepare("SELECT * FROM vehiculo WHERE id_documento = $documento ");
$datos->execute();

$datosUsuario = $datos->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    $codigoDeBarras = $_POST['codigoDeBarras'];
    $tipoServicio = $_POST['tipoServicio'];

    echo "<script>alert('$codigoDeBarras') </script>";
    
    $vehiculo = $con->prepare("SELECT placa FROM vehiculo WHERE codigoBarras = :codigo OR placa = '$codigoDeBarras'");
    $vehiculo->bindParam(':codigo', $codigoDeBarras);
    $vehiculo->execute();
    $placa = $vehiculo->fetchColumn();

    if ($placa) {
        
        $registrar = $con->prepare("INSERT INTO detalleservicio(id_tipoServicio, placa) VALUES (:tipoServicio, :placa)");
        $registrar->bindParam(':tipoServicio', $tipoServicio);
        $registrar->bindParam(':placa', $placa);
        $registrar->execute();
    } else {
        echo "No se encontró un vehículo con ese código de barras.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <button onclick="window.location = '../index.php'">Cerrar sesion</button>
    <h1>Vehiculos y Sus Codigos De Barras</h1>


    <?php

    foreach ($datosUsuario as $index => $datos) {


    ?>
        <h1>Placa: <?php echo $datos['placa'] ?></h1>
        <canvas id="barcode<?= $index ?>"></canvas>
    <?php } ?>


    <form action="" method="POST">
        <input type="text"name="codigoDeBarras" placeholder="Codigo De Barras">

        <select name="tipoServicio" id="">
            <option value="">Seleccione un tipo de servicio antes de scanear</option>

            <?php
            
            $servicio = $con->prepare("SELECT * FROM tiposervicio");
            $servicio->execute();
            $fila = $servicio->fetchAll();

            foreach($fila as $datos){
            ?>

                <option  value="<?php echo $datos['id_tipoServicio'] ?>"><?php echo $datos['nombre'] ?></option>

            <?php } ?>
        </select>
        <input name="submit" type="submit">
    </form>


    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>




    <script>
        window.onload = function() {
            <?php foreach ($datosUsuario as $index => $datos) { ?>
                JsBarcode("#barcode<?= $index ?>", "<?= $datos['codigoBarras'] ?>", {
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