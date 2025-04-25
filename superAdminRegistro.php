<?php


require_once('config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if (isset($_POST['submit'])) {

    $documento = $_POST['documento'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];

    $passwordEncrip = password_hash($password, PASSWORD_DEFAULT);

    $admin = $con->prepare("INSERT INTO superadministrador (documento,nombre,correo,password,telefono)
     VALUES ('$documento' ,'$nombre','$correo','$passwordEncrip','$telefono')");

    $admin->execute();
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
    <form action="" method="post">
        <input type="number" name="documento" placeholder="documento" id="">
        <input type="text" name="nombre" placeholder="nombre" id="">
        <input type="text" name="correo" placeholder="correo" id="">
        <input type="text" name="password" placeholder="contraseña" id="">
        <input type="number" name="telefono" placeholder="numero" id="">

        <input type="submit" name="submit" value="submit">
    </form>
</body>

</html>