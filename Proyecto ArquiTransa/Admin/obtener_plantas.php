<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prueba";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


// Consulta para obtener las plantas
$sql = "SELECT * FROM PLANTAS";
$result = $conn->query($sql);

// Generar el HTML con los datos de las plantas
$html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= "<li class='planta' id='" . $row["ID_Planta"] . "'>" . $row["Nombrep"] . "</li>";
    }
} else {
    $html = "No se encontraron plantas.";
}

// Cerrar la conexión
$conn->close();

// Devolver el HTML generado
echo $html;
?>
