<?php
// Verificar si se ha enviado un ID de planta por GET
if (isset($_GET['id'])) {
    // Obtener el ID de la planta desde la URL
    $idPlanta = $_GET['id'];

    // Crear conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = ""; // La contraseña de tu base de datos
    $dbname = "prueba"; // Nombre de tu base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        echo json_encode(array('error' => 'Error de conexión: ' . $conn->connect_error));
        die(); // Terminar el script
    }


    // Iniciar transacción
    $conn->begin_transaction();

    try {

// Definir las consultas DELETE para cada tabla relacionada
    
    $sql_delete_taxonomia = "DELETE FROM TAXONOMIA WHERE FKID_Planta = $idPlanta";
    $conn->query($sql_delete_taxonomia);
    $sql_delete_jardin_botanico = "DELETE FROM JARDIN_BOTANICO WHERE ID_JBot = $idPlanta";
    $conn->query($sql_delete_jardin_botanico);
    $sql_delete_zona_reserva = "DELETE FROM ZONA_RESERVA WHERE ID_ZRes = $idPlanta";
    $conn->query($sql_delete_zona_reserva);
    $sql_delete_plagas = "DELETE FROM PLAGAS WHERE ID_PLAG = $idPlanta";
    $conn->query($sql_delete_plagas);
    $sql_delete_clima = "DELETE FROM CLIMA WHERE ID_Clim = $idPlanta";
    $conn->query($sql_delete_clima);
    $sql_delete_pin = "DELETE FROM PINES WHERE ID_Pin = $idPlanta";
    $conn->query($sql_delete_pin);
    $sql_delete_flora = "DELETE FROM FLORACION WHERE ID_Flora = $idPlanta";
    $conn->query($sql_delete_flora);
    $sql_delete_cuidados = "DELETE FROM CUIDADOS WHERE ID_cuida = $idPlanta";
    $conn->query($sql_delete_cuidados);
    $sql_delete_localizacion = "DELETE FROM LOCALIZACION WHERE ID_LOCALI = $idPlanta";
    $conn->query($sql_delete_localizacion);
    $sql_delete_suelo = "DELETE FROM SUELO WHERE ID_Suelo = $idPlanta";
    $conn->query($sql_delete_suelo);
    $sql_delete_imagen = "DELETE FROM IMAGEN WHERE FKID_Planta = $idPlanta";
    $conn->query($sql_delete_imagen);

    $sql_delete_planta = "DELETE FROM PLANTAS WHERE ID_Planta = $idPlanta";
    $conn->query($sql_delete_planta);

// Confirmar la transacción
$conn->commit();

echo json_encode(array('success' => 'Los datos de la planta y sus relacionados han sido eliminados exitosamente.'));
} catch (Exception $e) {
// Revertir la transacción en caso de error
$conn->rollback();
echo json_encode(array('error' => 'Error al eliminar la planta: ' . $e->getMessage()));
}

// Cerrar la conexión
$conn->close();
} else {
echo json_encode(array('error' => 'No se ha proporcionado un ID de planta.'));
}
?>
