<?php 

require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    
    <div class="botonesRedireccion">
    <div><a href="../index.php">cerrar sesion</a></div>

    <button onclick="window.location =  './crearEmpresa.php'">
        <span> Crear Empresa </span>
    </button>
    
    
    
    <button onclick="window.location = './licencias.php'">
        <span> Generar Licencia de software </span>
    </button>
    
    
    <button onclick="window.location = './administradores.php'">
        <span> Administradores </span>
    </button>
    
    <button onclick="window.location = './logLicencias.php'">
        <span> Ver licencias  </span>
    </button>
</div>

<script>
    
</script>
    
</body>
</html>