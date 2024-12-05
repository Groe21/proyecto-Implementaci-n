<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la conexión a la base de datos
include_once '../model/conexion.php';

// Conectar a la base de datos
$conn = conectarBaseDeDatos();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    // Recuperar el ID del estudiante a eliminar
    $idEstudiante = $_POST['eliminar_id'];
    
    try {
        // Iniciar una transacción
        $conn->beginTransaction();

        // Eliminar los registros dependientes en la tabla 'discapacidad' (relacionados con 'estudiante')
        $stmtEliminarDiscapacidad = $conn->prepare("DELETE FROM discapacidad WHERE id_estudiante = :id_estudiante");
        $stmtEliminarDiscapacidad->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmtEliminarDiscapacidad->execute();

        // Eliminar los registros en la tabla 'rol' (relacionados con 'persona')
        $stmtEliminarRol = $conn->prepare("DELETE FROM rol WHERE id_persona IN (SELECT Id FROM persona WHERE id_estudiante = :id_estudiante)");
        $stmtEliminarRol->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmtEliminarRol->execute();

        // Eliminar los registros en la tabla 'persona' relacionados con el estudiante
        $stmtEliminarPersona = $conn->prepare("DELETE FROM persona WHERE id_estudiante = :id_estudiante");
        $stmtEliminarPersona->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmtEliminarPersona->execute();

        // Eliminar el estudiante de la tabla 'estudiante'
        $stmtEliminarEstudiante = $conn->prepare("DELETE FROM estudiante WHERE id = :id");
        $stmtEliminarEstudiante->bindParam(':id', $idEstudiante, PDO::PARAM_INT);
        $stmtEliminarEstudiante->execute();

        // Confirmar la transacción
        $conn->commit();

        echo "Estudiante y registros dependientes eliminados correctamente.";

    } catch (Exception $e) {
        // Si ocurre un error, hacer rollback de la transacción
        $conn->rollBack();
        echo "Error al eliminar el estudiante: " . $e->getMessage();
    }
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
    <link rel="stylesheet" href="bott.css">
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
                <th>Porcentaje Discapacidad</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Conectar a la base de datos nuevamente para mostrar los estudiantes
            $conn = conectarBaseDeDatos();
            $estudiantes = [];

            try {
                // Consulta para obtener los estudiantes
                $stmtEstudiantes = $conn->prepare("SELECT e.id, e.cedula, e.apellidos, e.nombres, e.direccion, e.condicion, d.tipo AS discapacidad, d.porcentaje AS porcentaje_discapacidad
                                                   FROM estudiante e
                                                   LEFT JOIN discapacidad d ON e.id = d.id_estudiante");
                $stmtEstudiantes->execute();

                // Obtener los resultados
                $estudiantes = $stmtEstudiantes->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo "Error al obtener los estudiantes: " . $e->getMessage();
            }

            // Mostrar los estudiantes
            if (count($estudiantes) > 0):
                foreach ($estudiantes as $estudiante):
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($estudiante['id']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['cedula']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['nombres']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['direccion']); ?></td>
                        <td><?php echo $estudiante['condicion'] == 1 ? 'Con discapacidad' : 'Sin discapacidad'; ?></td>
                        <td><?php echo $estudiante['discapacidad'] ? htmlspecialchars($estudiante['discapacidad']) : 'N/A'; ?></td>
                        <td><?php echo $estudiante['porcentaje_discapacidad'] ? htmlspecialchars($estudiante['porcentaje_discapacidad']) . '%' : 'N/A'; ?></td>
                        <td>
                            <!-- Botón de editar -->
                            <form action="editar_estudiante.php" method="get" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($estudiante['id']); ?>">
                                <button type="submit">Editar</button>
                            </form>
                        </td>
                        <td>
                            <!-- Botón de eliminar -->
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="eliminar_id" value="<?php echo htmlspecialchars($estudiante['id']); ?>">
                                <button type="submit" onclick="return confirm('¿Está seguro de eliminar este estudiante?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
            <?php
                endforeach;
            else:
            ?>
                <tr>
                    <td colspan="10">No hay estudiantes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>