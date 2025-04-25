<?php
require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

$key = [];

function generarLetraMayuscula()
{
    return chr(rand(65, 90));
}



for ($i = 1; $i <= 5; $i++) {
    $numerosCodigo = random_int(0, 5);
    $letra = generarLetraMayuscula();
    array_push($key, $letra, $numerosCodigo);
}

$codigoCompleto = implode("", $key);



if (isset($_POST['submit'])) {
    $nitEmpresa = $_POST['nitEmpresa'];
    $tipoDeLicencia = $_POST['tipoDeLicencia'];
    $estadoLicencia = $_POST['estadoLicencia'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    $nuevaLicencia = $con->prepare("INSERT INTO licencia (licencia, id_tipoLicencia, id_estadoLicencia, fechaInicio, fechaFin, nitEmpresa) VALUES (?, ?, ?, ?, ?, ?)");
    $nuevaLicencia->execute([$codigoCompleto, $tipoDeLicencia, $estadoLicencia, $fechaInicio, $fechaFin, $nitEmpresa]);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Generar Licencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1c1c1c, #2b2b2b);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 1.2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }

        label,
        p {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        select,
        input[type="date"],
        input[type="submit"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1.2rem;
            border: 1px solid #ccc;
            border-radius: 0.8rem;
            font-size: 1rem;
            transition: 0.3s ease;
            background-color: #f8f8f8;
        }

        select:focus,
        input[type="date"]:focus {
            border-color: #58bc82;
            outline: none;
            box-shadow: 0 0 8px rgba(88, 188, 130, 0.4);
        }

        input[type="submit"] {
            background-color: #58bc82;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 2rem;
            transition: 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #41a069;
        }

        a {
            display: inline-block;
            margin-bottom: 1rem;
            color: #58bc82;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body onload="form.empresa.focus()">

    <div class="form-container">
        <a href="./index.php">← Volver</a>
        <a href="./crearEmpresa.php">|Crear Empresa </a>
        <h1>Generar Licencia</h1>

        <form action="" method="post" id="form">

            <!-- Empresa -->
            <?php
            $empresa = $con->prepare("SELECT * FROM empresa");
            $empresa->execute();
            $empresas = $empresa->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <label for="empresa">Empresa</label>
            <select name="nitEmpresa" id="empresa" required>
                <option value="">Seleccione una empresa</option>
                <?php foreach ($empresas as $empresa): ?>
                    <option value="<?= $empresa['nitEmpresa'] ?>"><?= $empresa['nombreEmpresa'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Tipo Licencia -->
            <?php
            $tipoLicencia = $con->prepare("SELECT * FROM id_tipolicencia");
            $tipoLicencia->execute();
            $tipos = $tipoLicencia->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <label for="tipo">Tipo de Licencia</label>
            <select name="tipoDeLicencia" id="tipoLicencia" onclick="cambiar(this.value)" required>
                <option value="">Seleccione un tipo de licencia</option>
                <?php foreach ($tipos as $tipo): ?>
                    <option value="<?= $tipo['id_tipoLicencia'] ?>"><?= $tipo['tipoLicencia'] ?> : <?= $tipo['valor'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Estado Licencia -->
            <?php
            $estado = $con->prepare("SELECT * FROM estadolicencia");
            $estado->execute();
            $estados = $estado->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <label for="estado">Estado de Licencia</label>
            <select name="estadoLicencia"  required>
                <option value="">Seleccione un estado</option>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id_estadoLicencia'] ?>"><?= $estado['nombreEstado'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Fechas -->
            <p>Fecha De Inicio De La Membresía</p>
            <div id="inicio"></div>

            <p>Fecha De Fin De La Membresía</p>
            <div id="fin"></div>
            

            <input type="submit" name="submit" value="Crear Licencia">
        </form>
    </div>


    <script>

let fecha = new Date();
let hoy = fecha.getDate().toString().padStart(2, '0');
let mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
let año = fecha.getFullYear();

document.addEventListener('DOMContentLoaded', function() {
    let inicio = document.getElementById('inicio');
    
    let input = document.createElement("input");
    input.type = "date"; 
    input.name = "fechaInicio";
    input.value = `${año}-${mes}-${hoy}`; 
    
    inicio.appendChild(input);
});

const tipoLicencia = document.getElementById('tipoLicencia')

let fin = document.getElementById("fin")

let fechaFin = document.createElement('input'); 

function cambiar(element){

    if (element == 1) {

        let mes = (fecha.getMonth() + 2).toString().padStart(2,'0')

        fechaFin.type = "date";
        fechaFin.name = "fechaFin";
        fechaFin.value = `${año}-${mes}-${hoy}`;
        fin.appendChild(fechaFin);
    }
    if (element == 2) {
       
        let mes = (fecha.getMonth() + 7).toString().padStart(2,'0')

        fechaFin.type = "date";
        fechaFin.name = "fechaFin";
        fechaFin.value = `${año}-${mes}-${hoy}`;
        fin.appendChild(fechaFin);
    }
    console.log(element)
}




    </script>
</body>

</html>