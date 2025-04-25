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

if (isset($_POST['submit'])) {
    $codigoBarras = $_POST['barcodeValor'];
    $placa = $_POST['placa'];
    $id_marca = $_POST['id_marca'];
    $id_documento = $_POST['id_documento'];

    $vehiculo = $con->prepare("INSERT INTO vehiculo(placa,id_marca, id_documento, codigoBarras)
    VALUES(:placa, :id_marca, :id_documento, :codigoBarras)");

    $vehiculo->bindParam(':placa', $placa);
    $vehiculo->bindParam(':id_marca', $id_marca);
    $vehiculo->bindParam(':id_documento', $id_documento);
    $vehiculo->bindParam(':codigoBarras', $codigoBarras);
    $vehiculo->execute();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavado De Vehiculos</title>
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>

    <div class="main">
        <?php
        $empresa = $con->prepare("SELECT * FROM empresa WHERE nitEmpresa = $nitEmpresa");
        $empresa->execute();
        $nombreEmpresa = $empresa->fetch(PDO::FETCH_ASSOC);
        ?>
        <h2>Generar Código De Barras  <span style="text-decoration: underline;"> <?php echo $nombreEmpresa['nombreEmpresa'] ?> </span></h2>

        <div class="opciones">
            <div class="leftColumn">
                <button id="generar">Generar Código De Barras</button>
                <button id="generar" onclick="window.location =  './usuarios.php'">Usuarios</button>
                <button id="generar" onclick="window.location =  './vehiculos.php'">Vehiculos</button>
                <button id="generar" onclick="window.location =  '../index.php'">Cerrar Sesion</button>

            </div>

            <div class="rightColumn">
                <h1>Registrar Vehiculo</h1>
                <form action="" class="form" method="post">

                    <canvas id="barcode"></canvas>
                    <div id="barcodeValor"></div>
                    <p>Placa</p>
                    <input type="text" name="placa" id="" placeholder="placa">
                    <p>Marca</p>

                    <select name="id_marca" id="">
                        <?php

                        $marca = $con->prepare("SELECT * FROM marca ");
                        $marca->execute();
                        $fila = $marca->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($fila as $marcas) { ?>
                            <option value="<?php echo $marcas['id_marca'] ?>"><?php echo $marcas['nombreMarca'] ?></option>
                        <?php   }  ?>
                    </select>

                    <p>Documento</p>
                    <select name="id_documento" id="">
                        <option value="">Usuario</option>
                        <?php

                        $usuarios = $con->prepare("SELECT * FROM usuario WHERE id_empresa = $nitEmpresa");
                        $usuarios->execute();
                        $fila = $usuarios->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($fila as $usuario) { ?>
                            <option value="<?php echo $usuario['documento'] ?>"><?php echo $usuario['nombreCompleto'] ?></option>
                        <?php } ?>
                    </select>

                    <button type="submit" name="submit">Registrar Vehiculo</button>
                </form>


            </div>
        </div>
    </div>


    <script src="./jsBarCode.js"></script>


    <script>
        function numeros() {
            return Math.floor(Math.random() * 10000000) + 1;
        }
        const generar = document.getElementById("generar");

        generar.addEventListener("click", () => {
            const codigo = numeros().toString();

            JsBarcode("#barcode", codigo, {
                format: "CODE128",
                lineColor: "#0aa",
                width: 2,
                height: 60,
                displayValue: true
            });

            console.log("Código generado:", codigo);
            let barcodeValor = document.getElementById("barcodeValor");
            barcodeValor.innerHTML = '<input style="display: none;" type="text" name="barcodeValor" value=' + codigo + ' >'
        });
    </script>

</body>

</html>