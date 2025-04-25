<?php

require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();


$empresa = $con->prepare("SELECT * FROM `licencia` 
INNER JOIN id_tipoLicencia on id_tipoLicencia.id_tipoLicencia = licencia.id_tipoLicencia
INNER JOIN estadolicencia on estadolicencia.id_estadoLicencia = licencia.id_estadoLicencia
INNER JOIN empresa on empresa.nitEmpresa = licencia.nitEmpresa");
$empresa->execute();

$fila = $empresa->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['Eliminar'])){

    $eliminar = $con->prepare("DELETE FROM licencia WHERE licencia = :licencia");
    $eliminar->bindParam(':licencia', $_POST['Eliminar']);
    $eliminar->execute();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tarjeta Informativa</title>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1c1c1c, #2b2b2b);
        }

        .mainContainer {
            height: 100dvh;
            width: 100%;
            ;
        }

        .card {
            display: flex;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 600px;
            gap: 20px;
        }

        .logo {
            width: 100px;
            height: 100px;
            background-color: #e5e7eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #6b7280;
        }

        .info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }

        .info div {
            display: flex;
            justify-content: space-between;
        }

        .label {
            font-weight: bold;
            color: #374151;
        }

        .value {
            color: #1f2937;
        }

        .mainContainer {
            padding-top: 30px;
            display: flex;
            justify-content: center;
            width: 100%;
            height: 100%;
            flex-wrap: wrap;
            align-items: center;
            overflow-y: scroll;
            gap: 30px;
        }
    </style>
</head>

<body>

    <div style="width:100%; height:50px;"><a href="./index.php">Volver</a></div>
    <div class="mainContainer">


        <?php
        foreach ($fila as $empresa) {


        ?>
            <div class="card">
                <div class="logo">LOGO</div>
                <div class="info">
                    <div><span class="label">Empresa:</span><span class="value"><?php echo $empresa['nombreEmpresa']; ?></span></div>
                    <div><span class="label">Licencia:</span><span class="value"><?php echo $empresa['licencia']; ?></span></div>
                    <div><span class="label">Nit:</span><span class="value"><?php echo $empresa['nitEmpresa'] ?></span></div>
                    <div><span class="label">Direccion:</span><span class="value"><?php echo $empresa['direccion'] ?></span></div>
                    <div><span class="label">Estado:</span><span class="value"><?php echo $empresa['nombreEstado'] ?></span></div>
                    <div><span class="label">Tipo De Licencia:</span><span class="value"><?php echo $empresa['tipoLicencia'] ?></span></div>

                </div>
                <div style="display: flex; flex-direction:column;">
                    
                    <form action="" method="post" class="value" >
                        Eliminar <br>   
                        <input type="submit" name="Eliminar" value="<?php echo $empresa['licencia'] ?>" style="width:50px; height:30px; background:red; color:white; font-weight:600;" >
                    </form>
                </div>
            </div>

        <?php } ?>

    </div>
</body>

</html>