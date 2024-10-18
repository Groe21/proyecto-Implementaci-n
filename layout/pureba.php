<?php
session_start();
include_once '../model/conexion.php';

if (!isset($_SESSION['id']) || empty($_SESSION['nombre']) || empty($_SESSION['rol'])) {
    header("location: login.php");
    exit();
}

// Aquí puedes definir cualquier lógica o variables necesarias para esta página
$conn = conectarBaseDeDatos();

// Obtener datos o realizar cualquier operación necesaria para esta página

// Definir el contenido específico de la página
ob_start();
?>
<h1>Contenido de la Página de Prueba</h1>
<p>Este es un ejemplo de contenido para la página de prueba.</p>
<?php
$content = ob_get_clean();

// Incluir la plantilla
include_once "plantilla.php";
?>
