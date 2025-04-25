<?php
require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if(isset($_POST['submit'])){
    $documento = $_POST['documento'];
    $nombreCompleto = $_POST['nombreCompleto'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];
    $fechaRegistro = $_POST['fechaRegistro'];
    $id_empresa = $_POST['id_empresa'];

    $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

    $nuevoAdmin = $con->prepare("INSERT INTO usuario (documento, nombreCompleto, email, contraseña, id_empresa,id_rol) VALUES (?, ?, ?, ?, ?,?)");
    $nuevoAdmin->execute([$documento, $nombreCompleto, $email, $contrasenaHash,  $id_empresa, 2]);  
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <style>
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
          background: linear-gradient(135deg, #1c1c1c, #2b2b2b);
          min-height: 100vh;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 20px;
        }
        
        .container {
          width: 100%;
          max-width: 800px;
          padding: 2rem;
          background: #ffffff;
          border-radius: 1.2rem;
          box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
          border: 1px solid #e0e0e0;
        }
        
        h1, .form h2 {
          text-align: center;
          margin-bottom: 1.5rem;
          color: #1976d2;
        }
        
        .form-group {
          margin-bottom: 1.2rem;
        }
        
        label {
          display: block;
          margin-bottom: 0.5rem;
          font-weight: bold;
          color: #555;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
        select {
          width: 100%;
          padding: 0.9rem;
          border-radius: 0.6rem;
          border: 1px solid #ccc;
          background-color: #f9f9f9;
          color: #333;
          transition: border-color 0.3s, background-color 0.3s;
        }
        
        input:focus,
        select:focus {
          border-color: #1976d2;
          background-color: #fff;
          outline: none;
        }
        
        .btn {
          width: 100%;
          padding: 1rem;
          background: linear-gradient(90deg, #42a5f5, #1e88e5);
          color: white;
          border: none;
          border-radius: 0.6rem;
          font-weight: bold;
          cursor: pointer;
          margin-top: 2rem;
          transition: background 0.3s;
        }
        
        .btn:hover {
          background: linear-gradient(90deg, #1e88e5, #1565c0);
        }
        
       
       
    </style>
</head>
<body>
    <div class="container">
        <h1>Formulario de Registro Administrador</h1>
        <a href="./administradores.php">Volver</a>
        <form id="registrationForm" method="post">
            <div class="form-group">
                <label for="documento">Documento</label>
                <input type="text" id="documento" name="documento" required>
            </div>
            
            <div class="form-group">
                <label for="nombreCompleto">Nombre Completo</label>
                <input type="text" id="nombreCompleto" name="nombreCompleto" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            
            <div class="form-group">
                <label for="fechaRegistro">Fecha de Registro</label>
                <input type="date" id="fechaRegistro" name="fechaRegistro" required>
            </div>
            <?php
            
            $empresa = $con->prepare("SELECT * FROM empresa");
            $empresa->execute();
            $empresas = $empresa->fetchAll(PDO::FETCH_ASSOC);
            
            ?>
            
            <div class="form-group">
                <label for="id_empresa">ID Empresa</label>
                <select name="id_empresa" id="id_empresa" id="">
                    <option value="">Seleccione una empresa</option>
                    <?php 
                    foreach ($empresas as $empresa){

                     ?>
                        <option value="<?php echo $empresa['nitEmpresa']?>"><?php echo $empresa['nombreEmpresa']?></o ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <button type="submit" name="submit" class="btn">Registrar</button>
        </form>
        
     
</body>
</html>