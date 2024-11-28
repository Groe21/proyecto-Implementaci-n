<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la conexión a la base de datos
include_once '../model/conexion.php';

// Conectar a la base de datos
$conn = conectarBaseDeDatos();

$estudiantes = []; // Inicializamos la variable en caso de que no haya resultados

try {
    // Realizar una consulta SELECT para obtener los estudiantes
    $stmtEstudiantes = $conn->prepare("SELECT e.id, e.cedula, e.apellidos, e.nombres, e.direccion, e.condicion, d.tipo AS discapacidad, d.porcentaje AS porcentaje_discapacidad
                                       FROM estudiante e
                                       LEFT JOIN discapacidad d ON e.id = d.id_estudiante");  // Cambié e.id_estudiante por e.id
    $stmtEstudiantes->execute();
    
    // Obtener los resultados como un array asociativo
    $estudiantes = $stmtEstudiantes->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    // En caso de error, mostrar el mensaje de error
    echo "Error al obtener los estudiantes: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Estudiantes</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Si tienes un archivo CSS para el diseño -->
</head>
<body>    
    <!-- Tabla para mostrar los datos -->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cédula</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Dirección</th>
                <th>Condición</th>
                <th>Discapacidad</th>
                <th>Porcentaje de Discapacidad</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($estudiantes) > 0): ?>
                <?php foreach ($estudiantes as $estudiante): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($estudiante['id']); ?></td> <!-- Cambié id_estudiante por id -->
                        <td><?php echo htmlspecialchars($estudiante['cedula']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['nombres']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['direccion']); ?></td>
                        <td>
                            <?php 
                            // Mostrar si el estudiante tiene discapacidad o no
                            echo $estudiante['condicion'] == 1 ? 'Con discapacidad' : 'Sin discapacidad'; 
                            ?>
                        </td>
                        <td><?php echo $estudiante['discapacidad'] ? htmlspecialchars($estudiante['discapacidad']) : 'N/A'; ?></td>
                        <td><?php echo $estudiante['porcentaje_discapacidad'] ? htmlspecialchars($estudiante['porcentaje_discapacidad']) . '%' : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No hay estudiantes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
