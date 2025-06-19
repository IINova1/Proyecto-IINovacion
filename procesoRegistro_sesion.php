<?php
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
    $confirmar_password = trim($_POST["confirmar_password"]);

    if ($password !== $confirmar_password) {
        die("Las contraseñas no coinciden.");
    }

    // Encriptar la contraseña antes de guardarla
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $sql_verificar = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_verificar = $conexion->prepare($sql_verificar);

    if (!$stmt_verificar) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt_verificar->bind_param("s", $email);
    $stmt_verificar->execute();
    $stmt_verificar->store_result();

    if ($stmt_verificar->num_rows > 0) {
        $stmt_verificar->close();
        $conexion->close();
        die("El correo ya está registrado.");
    }

    $stmt_verificar->close();

    // Insertar usuario en la base de datos
    $sql_insertar = "INSERT INTO usuarios (email, password) VALUES (?, ?)";
    $stmt_insertar = $conexion->prepare($sql_insertar);

    if (!$stmt_insertar) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt_insertar->bind_param("ss", $email, $password_hash);

    if ($stmt_insertar->execute()) {
        $stmt_insertar->close();
        $conexion->close();
        header("Location: inicioSesion.html");
        exit();
    } else {
        $stmt_insertar->close();
        $conexion->close();
        die("Error al registrar el usuario.");
    }
}
?>
