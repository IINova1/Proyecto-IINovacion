<?php
// Configuración de conexión a la base de datos
$servidor = "localhost";   // Servidor MySQL
$usuario = "root";      // Usuario de MySQL
$clave = "";     // Contraseña de MySQL
$base_datos = "innovacion"; // Nombre de la base de datos

// Conectar a la base de datos
$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar los datos del formulario
    $rut = trim($_POST["rut"]);
    $nombre = trim($_POST["nombre"]);
    $carrera = trim($_POST["carrera"]);
    $anio = intval($_POST["anio"]); // Convertir a número entero
    $dificultad = $_POST["dificultad"];
    $metodo_estudio = $_POST["metodo_estudio"];

    // Validar que los datos no estén vacíos
    if (empty($rut) || empty($nombre) || empty($carrera) || empty($anio) || empty($dificultad) || empty($metodo_estudio)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Preparar la consulta SQL para insertar datos en la tabla `formulario`
    $sql = "INSERT INTO formulario (rut, nombre, carrera, anio, dificultad, metodo_estudio) VALUES (?, ?, ?, ?, ?, ?)";

    // Preparar la consulta antes de ejecutarla
    $stmt = $conexion->prepare($sql);
    
    // Verificar si la consulta fue preparada correctamente
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    // Vincular los parámetros con la consulta preparada
    $stmt->bind_param("sssiss", $rut, $nombre, $carrera, $anio, $dificultad, $metodo_estudio);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Cerrar la consulta y conexión con la base de datos
        $stmt->close();
        $conexion->close();
        
        // Redirigir al usuario a la página de inicio después del envío exitoso
        header("Location: inicio.html");
        exit();
    } else {
        // Mostrar un mensaje de error si la inserción falla
        die("Error al ejecutar la consulta: " . $stmt->error);
    }
}
?>


