<?php
// Configuración de conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "innovacion";

// Conectar a la base de datos
$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener los métodos de estudio y cuántos usuarios los han elegido
$sql = "SELECT metodo_estudio, COUNT(*) as total FROM formulario GROUP BY metodo_estudio ORDER BY total DESC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métodos de Estudio</title>
    <link rel="stylesheet" href="css/vista_metodo_estudio.css">
</head>
<body>
    <h2>Métodos de Estudio Elegidos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Método de Estudio</th>
                <th>Cantidad de Usuarios</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar los resultados en la tabla
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fila["metodo_estudio"]) . "</td>";
                    echo "<td>" . $fila["total"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No hay registros todavía.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        // Recargar la página cada 10 segundos para actualizar los datos automáticamente
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>

    <button><a href="inicio.html">INICIO</a></button>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conexion->close();
?>
