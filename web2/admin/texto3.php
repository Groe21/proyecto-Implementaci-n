<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    include_once 'model/conexion.php';
   
    // Obtener datos del formulario
    $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
    $descripcion1 = isset($_POST["descripcion1"]) ? $_POST["descripcion1"] : "";
    $contenido1 = isset($_POST["contenido1"]) ? $_POST["contenido1"] : "";
    $descripcion2 = isset($_POST["descripcion2"]) ? $_POST["descripcion2"] : "";
    $contenido2 = isset($_POST["contenido2"]) ? $_POST["contenido2"] : "";
    $descripcion3 = isset($_POST["descripcion3"]) ? $_POST["descripcion3"] : "";
    $contenido3 = isset($_POST["contenido3"]) ? $_POST["contenido3"] : "";
    $descripcion4 = isset($_POST["descripcion4"]) ? $_POST["descripcion4"] : "";
    $contenido4 = isset($_POST["contenido4"]) ? $_POST["contenido4"] : "";
    $descripcion5 = isset($_POST["descripcion5"]) ? $_POST["descripcion5"] : "";
    $contenido5 = isset($_POST["contenido5"]) ? $_POST["contenido5"] : "";
    
    // Modificar datos en la base de datos
    $update_query = "UPDATE blog SET 
        titulo=?, descripcion1=?, contenido1=?, descripcion2=?, contenido2=?, 
        descripcion3=?, contenido3=?, descripcion4=?, contenido4=?, 
        descripcion5=?, contenido5=?";
    
    $stmt = $pdo->prepare($update_query);
    $stmt->bindParam(1, $titulo);
    $stmt->bindParam(2, $descripcion1);
    $stmt->bindParam(3, $contenido1);
    $stmt->bindParam(4, $descripcion2);
    $stmt->bindParam(5, $contenido2);
    $stmt->bindParam(6, $descripcion3);
    $stmt->bindParam(7, $contenido3);
    $stmt->bindParam(8, $descripcion4);
    $stmt->bindParam(9, $contenido4);
    $stmt->bindParam(10, $descripcion5);
    $stmt->bindParam(11, $contenido5);

    $success = $stmt->execute();
    $stmt->closeCursor();

    // Mostrar alerta después de realizar cambios
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    if ($success) {
        echo '<script>
                Swal.fire("Cambios guardados!", "Los cambios se han guardado correctamente.", "success");
            </script>';
    } else {
        echo '<script>
                Swal.fire("Error", "Hubo un error al guardar los cambios.", "error");
            </script>';
    }
}
?>

<main>
<div class="container"> 

<div class="form-container" style=" margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: space-between;" >

    <style>
   .container {
    max-width: 90%; /* Ajusta al 90% del ancho de la ventana para hacer el contenedor un poco más pequeño */
    margin: 0 auto;
    padding: 20px;
}

.form-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Estilo del formulario destacado */
.formulario-destacado {
    background: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada */
    overflow-y: auto;
    max-height: 700px;
}

/* Estilos de los campos y etiquetas del formulario */
.formulario-destacado label {
    display: block;
    margin-bottom: 0.5em;
    color: #333333;
    font-weight: 600;
}

.formulario-destacado input[type="text"],
.formulario-destacado textarea {
    width: calc(100% - 1.5em); /* Resta el padding para mantener el diseño */
    padding: 0.75em;
    border: 1px solid #dcdcdc;
    border-radius: 4px;
    margin-bottom: 1em;
}

.formulario-destacado textarea {
    min-height: 120px;
    resize: vertical;
}

/* Estilo para los botones */
.formulario-destacado input[type="submit"] {
    display: block; /* Asegura que el botón ocupe toda una línea */
    width: auto; /* El botón se ajustará al contenido */
    padding: 0.8em 2em; /* Ajusta el tamaño del botón: más alto y con suficiente espacio lateral */
    background-color: #007bff; /* Color del fondo */
    color: #ffffff; /* Color del texto */
    border: none; /* Sin bordes */
    border-radius: 30px; /* Bordes redondeados */
    cursor: pointer; /* Cambia el cursor al pasar el mouse */
    font-size: 1em; /* Tamaño de fuente */
    transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Transición suave */
    box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.2); /* Sombra suave */
    margin-top: 1em; /* Espacio superior */
    margin-left: auto; /* Empuja el botón a la derecha */
    margin-right: 0; /* Asegura la alineación completa a la derecha */
    text-align: center; /* Centra el texto dentro del botón */
}

.formulario-destacado input[type="submit"]:hover {
    background-color: #0069d9; /* Color ligeramente más oscuro para el hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada en hover */
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 20px 10px; /* Menos padding en dispositivos móviles */
    }

    .formulario-destacado {
        max-height: none; /* No limitar la altura en dispositivos móviles */
        padding: 20px; /* Menos padding en dispositivos móviles */
    }

    .formulario-destacado input[type="text"],
    .formulario-destacado textarea {
        width: calc(100% - 40px); /* Ajuste para el padding en dispositivos móviles */
    }
}

</style>



<?php
include_once 'model/conexion.php';

// Obtener datos de la base de datos
$query = "SELECT * FROM blog";
$result = $pdo->query($query);

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<div class="formulario-destacado">';
    echo '<form method="post" action="" id="form-' . $row['Id'] . '" enctype="multipart/form-data">';

    $campos = array(
        "titulo", "descripcion1", "contenido1", "descripcion2", 
        "contenido2", "descripcion3", "contenido3", "descripcion4", 
        "contenido4", "descripcion5", "contenido5"
    );

    foreach ($campos as $campo) {
        echo '<div class="campo">';
        echo '<label for="' . $campo . '">' . ucfirst($campo) . ':</label>';

        // Verificar si la clave existe en la fila actual antes de acceder a ella
        $valorCampo = isset($row[$campo]) ? $row[$campo] : '';

        // Usar textarea para los campos que deben ser grandes
        if (strpos($campo, 'contenido') !== false || $campo == 'descripcion') {
            echo '<textarea name="' . $campo . '" required>' . $valorCampo . '</textarea>';
        } else {
            echo '<input type="text" name="' . $campo . '" value="' . $valorCampo . '" required>';
        }

        echo '</div>';
    }

    echo '<input type="submit" name="submitForm" value="Guardar Cambios">';
    echo '</form>';
    echo '</div>';
}

// Cerrar la conexión
$pdo = null;
?>





    </div>
</div> 
</main>
