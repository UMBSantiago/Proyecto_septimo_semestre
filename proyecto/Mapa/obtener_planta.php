<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "9@xYwHE@P&9DQ5bS"; // Tu contraseña de la base de datos
$dbname = "prueba"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la planta desde la solicitud GET
$id = intval($_GET['id']);

// Consulta preparada para obtener la información de la planta
$sql = "SELECT TAXONOMIA.*, IMAGEN.direccion
FROM TAXONOMIA
JOIN IMAGEN ON TAXONOMIA.ID_Taxo = IMAGEN.FKID_Planta
WHERE TAXONOMIA.ID_Taxo = ?";
;
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$id); // "i" indica que el parámetro es un entero

// Ejecutar la consulta preparada
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

// Obtener el resultado de la consulta
$result = $stmt->get_result();
$planta = array();

if ($result->num_rows > 0) {
    // Obtener los datos de la planta y agregarlos al array
    $row = $result->fetch_assoc();
    $planta = $row;
}

// Convertir el array de la planta a formato JSON y devolverlo
echo json_encode($planta);

// Cerrar conexión
$stmt->close();
$conn->close();
?>
