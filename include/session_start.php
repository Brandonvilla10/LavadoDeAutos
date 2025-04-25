<?php
require_once('../config/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if (isset($_POST['submit'])) {

    if (!empty($_POST['nitEmpresa'])) {
        $nitEmpresa = $_POST['nitEmpresa'];
    }

    $_SESSION['nitEmpresa'] = $nitEmpresa;

    $documento = filter_input(INPUT_POST, 'documento');
    $passwordDesc = filter_input(INPUT_POST, 'password');

    $superAdmin = $con->prepare("SELECT * FROM superadministrador WHERE documento = $documento");
    $superAdmin->execute();
    $fila = $superAdmin->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        if (password_verify($passwordDesc, $fila['password'])) {
            $_SESSION['documento'] = $fila['documento'];
            echo "<script>window.location = '../superAdmin/index.php'</script>";
        }
    }

    $sql = $con->prepare("SELECT * FROM usuario WHERE documento = :documento");
    $sql->bindParam(':documento', $documento, PDO::PARAM_STR);
    $sql->execute();
    $fila = $sql->fetch(PDO::FETCH_ASSOC);



    if (!$fila || !is_array($fila)) {
        echo "<script>alert('Usuario no encontrado o error en la consulta.')</script>";
        echo "<script>window.location='../index.php'</script>";
        exit();
    }

    $empresa = $fila['id_empresa'];

    $licencia = $con->prepare("SELECT * FROM licencia WHERE nitEmpresa = $empresa AND id_estadoLicencia = 1 ");
    $licencia->execute();
    $filaLicencia = $licencia->fetch(PDO::FETCH_ASSOC);



    if ($filaLicencia) {

        if ($fila['id_rol'] == 1) {
            if (password_verify($passwordDesc, $fila['contraseña'])) {
                $_SESSION['documento'] = $fila['documento'];
                header("Location: ../cliente/index.php");
                exit();
            }
        } else if ($fila['id_rol'] = 2) {

            if ($fila) {
                if (password_verify($passwordDesc, $fila['contraseña'])) {
                    $_SESSION['documento'] = $fila['documento'];
                    header("Location: ../user/index.php");
                    exit();
                }
            } else {
                echo "<script>alert('Usuario no encontrado. Intenta de nuevo.')</script>";
                echo "<script>window.location='../index.php'</script>";
                exit();
            }
        }
    } else {
        echo "<script>alert('Empresa sin licencia.')</script>";
        echo "<script>window.location='../index.php'</script>";
    }
}
