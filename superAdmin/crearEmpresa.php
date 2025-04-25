<?php
require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if (isset($_POST['submit'])) {


    $nitEmpresa = $_POST['nitEmpresa'];
    $nombreEmpresa = $_POST['nombreEmpresa'];
    $direccion = $_POST['direccion'];

    $consultar = $con->prepare("SELECT * FROM empresa WHERE nitEmpresa = $nitEmpresa");
    $consultar->execute();
    $fila = $consultar->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        echo "<script>alert('nit ya registrado')</script>";
    } else {


        $empresa = $con->prepare("INSERT INTO empresa (nitEmpresa, nombreEmpresa, direccion) 
    VALUES($nitEmpresa, '$nombreEmpresa', '$direccion') ");
        $empresa->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/crearEmpresa.css">
</head>

<body>
    <a href="index.php">Volver</a>
    <div class="main">


        <form class="form" method="POST">

            <span class="input-span">
                <label for="nitempresa" class="label">Nit Empresa</label>
                <input type="number" name="nitEmpresa" id="nitempresa" /></span>

            <span class="input-span">
                <label for="text" class="label">Nombre Empresa</label>
                <input type="text" name="nombreEmpresa" id="nombreEmpresa" /></span>

            <span class="input-span">
                <label for="direccion" class="label">Direccion</label>
                <input type="text" name="direccion" id="direccion" /></span>


            <input class="submit" type="submit" name="submit" value="Registrar nueva empresa" />

        </form>

    </div>
</body>

</html>