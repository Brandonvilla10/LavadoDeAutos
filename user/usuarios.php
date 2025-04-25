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
    <title>Usuario</title>
    <link rel="stylesheet" href="./css/usuarios.css">
</head>

<body>

    <div>
        <a href="./index.php">Volver</a>

        <button onclick="mostrar()">Crear Usuario</button>
    </div>



    <div id="modalUsuario" class="modal">
        <div class="modal-contenido">
            <span id="cerrar" onclick="cerrarModal()" class="cerrar">&times;</span>
            <h2>Registrar Usuario</h2>

            <form action="" method="POST">
                <input type="text" name="documento" placeholder="Documento">
                <input type="text" name="nombreCompleto" placeholder="Nombre completo">
                <input type="email" name="email" placeholder="Correo electrónico">
                <input type="password" name="contrasena" placeholder="Contraseña">


                <button type="submit" name="modalSubmit">Guardar Usuario</button>
            </form>
        </div>
    </div>



    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>ID Empresa</th>
                <th>eliminar </th>
                <th>editar</th>

            </tr>
        </thead>
        <tbody>

            <?php

            $licencia = $con->prepare("SELECT * FROM licencia ");
            $licencia->execute();
            $fila = $licencia->fetch(PDO::FETCH_ASSOC);
            
            if($fila['id_tipoLicencia'] == 1){
                $usuario = $con->prepare("SELECT * FROM usuario WHERE id_empresa = $nitEmpresa LIMIT 3");
                $usuario->execute();
    
                $filas = $usuario->fetchAll(PDO::FETCH_ASSOC);
            }else{ 
                
                $usuario = $con->prepare("SELECT * FROM usuario WHERE id_empresa = $nitEmpresa");
                $usuario->execute();
                $filas = $usuario->fetchAll(PDO::FETCH_ASSOC);
            }

            foreach ($filas as $fila) {


            ?>


                <tr>
                    <td><?php echo $fila['documento'] ?></td>
                    <td><?php echo $fila['nombreCompleto'] ?></td>
                    <td><?php echo $fila['email'] ?></td>
                    <td><?php echo $fila['id_empresa'] ?></td>

                    <td>
                        <form action="" method="post">
                            <button style="background: red; color:white; border:none;" value="<?php echo $fila['documento'] ?>" name="eliminar" type="submit">Eliminar</button>
                        </form>
                    </td>

                    <td>
                        <a href="editar.php?documento=<?= $fila['documento'] ?>">Editar</a>

                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>



    <script src="./js/modal.js"></script>
</body>

</html>