<?php
// Verificar si se ha enviado un ID de planta por GET
if (isset($_GET['id'])) {
    // Obtener el ID de la planta desde la URL
    $idPlanta = intval($_GET['id']); // Convertir a entero para mayor seguridad

    // Crear conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "9@xYwHE@P&9DQ5bS"; // La contraseña de tu base de datos
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
        $messages = array();

        // Verificar si existe información en la tabla INVESTIGACION
        $result = $conn->query("SELECT COUNT(*) AS count FROM INVESTIGACION WHERE FKID_Planta = $idPlanta");
        $row = $result->fetch_assoc();
        if ($row['count'] > 0) {
            // Si hay información en INVESTIGACION, proceder a eliminarla
            $query = "DELETE FROM INVESTIGACION WHERE FKID_Planta = $idPlanta";
            if ($conn->query($query) === TRUE) {
                $messages[] = "Datos eliminados de la tabla Investigación correctamente.";
            } else {
                throw new Exception("Error al eliminar datos de la tabla Investigación: " . $conn->error);
            }
        } else {
            $messages[] = "No se encontró información en la tabla Investigación.";
        }

        // Definir las consultas DELETE para cada tabla relacionada, excluyendo INVESTIGACION
        $queries = array(
            "DELETE FROM TAXONOMIA WHERE FKID_Planta = $idPlanta" => "Taxonomía",
            "DELETE FROM JARDIN_BOTANICO WHERE ID_JBot = $idPlanta" => "Jardín Botánico",
            "DELETE FROM PLAGAS WHERE ID_PLAG = $idPlanta" => "Plagas",
            "DELETE FROM CLIMA WHERE ID_Clim = $idPlanta" => "Clima",
            "DELETE FROM REGION WHERE ID_Reg = $idPlanta" => "Region",
            "DELETE FROM PINES WHERE ID_Pin = $idPlanta" => "Pines",
            "DELETE FROM FLORACION WHERE ID_Flora = $idPlanta" => "Floración",
            "DELETE FROM CUIDADOS WHERE ID_cuida = $idPlanta" => "Cuidados",
            "DELETE FROM LOCALIZACION WHERE ID_LOCALI = $idPlanta" => "Localización",
            "DELETE FROM SUELO WHERE ID_Suelo = $idPlanta" => "Suelo",
            "DELETE FROM IMAGEN WHERE FKID_Planta = $idPlanta" => "Imagen",
            "DELETE FROM PLANTAS WHERE ID_Planta = $idPlanta" => "Plantas"
        );

        // Ejecutar cada consulta y almacenar mensajes
        foreach ($queries as $query => $tableName) {
            if ($conn->query($query) === TRUE) {
                $messages[] = "Datos eliminados de la tabla $tableName correctamente.";
            } else {
                throw new Exception("Error al eliminar datos de la tabla $tableName: " . $conn->error);
            }
        }

        // Confirmar la transacción
        $conn->commit();

        echo json_encode(array('success' => 'Los datos de la planta y sus relacionados han sido eliminados exitosamente.', 'messages' => $messages));
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

