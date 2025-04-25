<?php
require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if (!isset($_SESSION['documento'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['placa'])) {
    echo "No se proporcionó un placa válida.";
    exit();
}

$placa = $_GET['placa'];

$vehiculo = $con->prepare("SELECT * FROM vehiculo
INNER JOIN marca ON marca.id_marca = vehiculo.id_marca
INNER JOIN usuario ON usuario.documento = vehiculo.id_documento
 WHERE placa = :placa");

$vehiculo->bindParam(':placa', $placa);
$vehiculo->execute();
$data = $vehiculo->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "Vehiculo no encontrado.";
    exit();
}

if (isset($_POST['actualizar'])) {
$placa = $_POST['placa'];
$marca = $_POST['marca'];    
$persona = $_POST['persona'];
$codigoDeBarras = $_POST['codigoDeBarras'];   


$vehiculo = $con->prepare("UPDATE vehiculo SET placa = '$placa', id_marca = $marca, id_documento = $persona, codigoBarras = $codigoDeBarras WHERE placa = '$placa'");
$vehiculo->execute();
    echo "<script>window.location = './index.php' </script>";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="./css/index.css">
    <style>
        .formulario-editar {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .formulario-editar h2 {
            margin-bottom: 1rem;
            color: #0076be;
        }

        .formulario-editar input,
        .formulario-editar select {
            width: 100%;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .formulario-editar button {
            padding: 0.8rem 2rem;
            background: #0076be;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .formulario-editar button:hover {
            background: #005a94;
        }
    </style>
</head>

<body>

    <div class="formulario-editar">
        <h2>Editar Usuario</h2>
        <form action="" method="POST">
            <label>Placa:</label>
            <input type="text" name="placa" value="<?= htmlspecialchars($data['placa']) ?>" readonly>

            <label>Marca Actual:</label>

            <select name="marca" id="">
                <option value="<?php echo $data['id_marca'] ?>"> <?php echo $data['nombreMarca'] ?></option>
                <?php
                $marca = $con->prepare("SELECT * FROM marca");
                $marca->execute();
                $marcas = $marca->fetchAll(PDO::FETCH_ASSOC);

                foreach ($marcas as $marca) {

                ?>

                    <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['nombreMarca']; ?></option>

                <?php } ?>
            </select>




            <label>Dueño:</label>
            
            <select name="persona" id="">
                <option value="<?php echo $data['id_documento'] ?>"><?php echo $data['nombreCompleto'] ?></option>
                <?php
                $usuario = $con->prepare("SELECT * FROM usuario");
                $usuario->execute();
                $usuarios = $usuario->fetchAll(PDO::FETCH_ASSOC);

                foreach ($usuarios as $usuario) {
                ?>

                <option value="<?php echo $usuario['documento'] ?>"><?php echo $usuario['nombreCompleto'] ?></option>
                    
                <?php } ?>
            </select>

            <label>Codigo De Barras</label>
            <input type="number" name="codigoDeBarras" value="<?php echo $data['codigoBarras'] ?>" >

        

            <button type="submit" name="actualizar"">Actualizar</button>
    </form>
</div>

</body>
</html>