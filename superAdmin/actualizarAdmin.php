<?php
require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if (!isset($_SESSION['documento'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['documento'])) {
    echo "No se proporcionó un documento válido.";
    exit();
}

$documento = $_GET['documento'];

$usuario = $con->prepare("SELECT * FROM usuario WHERE documento = :documento");
$usuario->bindParam(':documento', $documento);
$usuario->execute();
$data = $usuario->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "Usuario no encontrado.";
    exit();
}

if(isset($_POST['actualizar'])){

    $hasPasword = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $actualizar = $con->prepare("UPDATE usuario SET nombreCompleto = :nombreCompleto, email = :email, contraseña = :contrasena, id_empresa = :id_empresa, id_rol = :id_rol WHERE documento = $documento");

    $actualizar->bindParam(':nombreCompleto', $_POST['nombreCompleto']);
    $actualizar->bindParam(':email', $_POST['email']);
    $actualizar->bindParam(':contrasena', $hasPasword);
    $actualizar->bindParam(':id_empresa', $_POST['id_empresa']);
    $actualizar->bindParam(':id_rol', $_POST['id_rol']);
    $actualizar->execute();

    echo "<script>window.location = './administradores.php' </script>";
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
    <a href="./editarAdmin.php" class="volver">Volver</a>
    <form action="" method="POST">
        <label>Documento:</label>
        <input type="text" name="documento" value="<?= htmlspecialchars($data['documento']) ?>" readonly>

        <label>Nombre Completo:</label>
        <input type="text" name="nombreCompleto" value="<?= htmlspecialchars($data['nombreCompleto']) ?>">

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>">

        <label>Nueva Contraseña (opcional):</label>
        <input type="password" name="contrasena" placeholder="Dejar en blanco si no deseas cambiarla">

        <label>ID Empresa:</label>
        <select name="id_empresa" id="">
        <option value="<?php echo $data['id_empresa'] ?>">Elije Una Empresa</option>
            <?php
            $empresas = $con->prepare("SELECT * FROM empresa");
            $empresas->execute();
            $empresasData = $empresas->fetchAll(PDO::FETCH_ASSOC);  
            
            foreach($empresasData as $empresa) {
                
                ?>
        <option value="<?php echo $empresa['nitEmpresa'] ?>"><?php echo $empresa['nombreEmpresa'] ?></option>
        
        
        <?php }?>
    </select>

        <label>Rol:</label>
        <select name="id_rol">
            <option value="2">Administrador</option>
            <option value="1">Cliente</option>
            
        </select>

        <button type="submit" name="actualizar"">Actualizar</button>
    </form>
</div>

</body>
</html>
