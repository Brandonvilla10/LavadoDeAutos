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

    $eliminar = $con->prepare("DELETE FROM usuario WHERE documento = :documento");
    $eliminar->bindParam(':documento', $_POST['eliminar']);
    $eliminar->execute();
}


if (isset($_POST['modalSubmit'])) {
    $documento = $_POST['documento'];
    $nombreCompleto = $_POST['nombreCompleto'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];
    

    $hasPassword = password_hash($contrasena, PASSWORD_DEFAULT);

    $nuevoUser = $con->prepare("INSERT INTO usuario(documento, nombreCompleto, email, contraseña, id_empresa, id_rol) 
    VALUES ($documento,'$nombreCompleto', '$email', '$hasPassword', $nitEmpresa, 1 )");
    $nuevoUser->execute();


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Admin</title>
    <link rel="stylesheet" href="css/editarAdmin.css">
</head>
<body>
    
<div>
        <a href="./administradores.php">Volver</a>

       
    </div>


<table class="tabla-usuarios">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>ID Empresa</th>
                
                <th>editar</th>

            </tr>
        </thead>
        <tbody>

            <?php
            $usuario = $con->prepare("SELECT * FROM usuario WHERE id_rol = 2");
            $usuario->execute();

            $filas = $usuario->fetchAll(PDO::FETCH_ASSOC);

            foreach ($filas as $fila) {


            ?>


                <tr>
                    <td><?php echo $fila['documento'] ?></td>
                    <td><?php echo $fila['nombreCompleto'] ?></td>
                    <td><?php echo $fila['email'] ?></td>
                    <td><?php echo $fila['id_empresa'] ?></td>


                    <td>
                        <a href="actualizarAdmin.php?documento=<?=$fila['documento'] ?>">Editar</a>

                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
</body>
</html>