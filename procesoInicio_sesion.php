<?php
session_start();

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "innovacion";

$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Consultar el usuario en la base de datos
    $sql = "SELECT id, password FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $password_hash);
        $stmt->fetch();

        // Verificar contraseña
        if (password_verify($password, $password_hash)) {
            $_SESSION["usuario_id"] = $id;
            $stmt->close();
            $conexion->close();
            header("Location: inicio.html");
            exit();
        } else {
            $stmt->close();
            $conexion->close();
            die("Contraseña incorrecta.");
        }
    } else {
        $stmt->close();
        $conexion->close();
        die("Correo no registrado.");
    }
}
?>
