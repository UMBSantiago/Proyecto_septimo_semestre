<?php
// Lineas para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se han enviado datos desde el formulario
if (isset($_POST['id_planta'])) {
    // Obtener el ID de la planta desde el formulario
    $idPlanta = $_POST['id_planta'];

    // Crear conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "9@xYwHE@P&9DQ5bS"; // La contraseña de tu base de datos
    $dbname = "prueba"; // Nombre de tu base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->begin_transaction();
    try {
   
    echo "ID de la planta: " . $idPlanta;
    // Preparar los datos para su actualización en la base de datos
    $nombre = $_POST["nombre"];
    $nombre_cientifico = $_POST["nombre_cientifico"];
    $reino = $_POST["reino"];
    $division = $_POST["division"];
    $subdivision = $_POST["SubDivision"];
    $clase = $_POST["clase"];
    $subclase = $_POST["SubClase"];
    $orden = $_POST["orden"];
    $familia = $_POST["familia"];
    $genero = $_POST["genero"];
    $especie = $_POST["especie"];
    $ciudad = $_POST["ciudad"];
    $direccion = $_POST["direccion"];
    $zona_reserva = $_POST["zona_reserva"];
    $ubicacion = $_POST["ubicacion"];
    $jardin_botanico = $_POST["jardin_botanico"];
    $superficie_decimal = $_POST["superficie_decimal"];
    $inicio_flora = $_POST["inicio_flora"];
    $fin_flora = $_POST["fin_flora"];
    $tipo_cuidado = $_POST["tipo_cuidado"];
    $informacion = $_POST["informacion"];
    $tipo_plaga = $_POST["tipo_plaga"];
    $descripcion_plaga = $_POST["descripcion_plaga"];
    $ciudad_clima = $_POST["ciudad_clima"];
    $descripcion_clima = $_POST["descripcion_clima"];
    $temperatura_promedio = $_POST["temperatura_promedio"];
    $humedad_promedio = $_POST["humedad_promedio"];
    $descripcion_suelo = $_POST["descripcion_suelo"];
    $latitud = $_POST["latitud"];
    $longitud = $_POST["longitud"];

    // Actualizar datos en la tabla PLANTAS
    $sql_plantas = "UPDATE PLANTAS 
                SET Nombrep = '$nombre', Nombre_cientifico = '$nombre_cientifico' 
                WHERE ID_Planta = $idPlanta";
    $conn->query($sql_plantas);
    if ($conn->query($sql_plantas) === TRUE) {
        echo "Datos actualizados correctamente.";
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }
    $sql_planta = "SELECT * FROM PLANTAS WHERE ID_Planta = $idPlanta";
    $result_planta = $conn->query($sql_planta);
    $planta = $result_planta->fetch_assoc();
    // Actualizar datos en la tabla TAXONOMIA
    $sql_taxonomia = "UPDATE TAXONOMIA 
                      SET nombre = '$nombre_cientifico', reino = '$reino', division = '$division', subdivision = '$subdivision', clase = '$clase', subclase = '$subclase', orden = '$orden', familia = '$familia', genero = '$genero', especie = '$especie' 
                      WHERE FKID_Planta = $idPlanta";
    $conn->query($sql_taxonomia);

    // Actualizar datos en la tabla JARDIN_BOTANICO
    $sql_jardin_botanico = "UPDATE JARDIN_BOTANICO 
                            SET nombrep = '$nombre_cientifico', nombrej = '$jardin_botanico', ubicacion = '$ubicacion', superficie = '$superficie_decimal' 
                            WHERE ID_JBot = $idPlanta";
    $conn->query($sql_jardin_botanico);

    // Actualizar datos en la tabla ZONA_RESERVA
    $sql_zona_reserva = "UPDATE ZONA_RESERVA 
                         SET nombrez = '$zona_reserva' 
                         WHERE ID_ZRes = $idPlanta";
    $conn->query($sql_zona_reserva);

    // Actualizar datos en la tabla PINES
    $sql_pines = "UPDATE PINES 
                  SET nombre = '$nombre_cientifico', latitud = '$latitud', longitud = '$longitud' 
                  WHERE ID_Pin = $idPlanta";
    $conn->query($sql_pines);

    // Actualizar datos en la tabla CLIMA
    $sql_clima = "UPDATE CLIMA 
                  SET nombrec = '$ciudad_clima', descripcionc = '$descripcion_clima', temperatura_promedio = '$temperatura_promedio', humedad_promedio = '$humedad_promedio' 
                  WHERE ID_Clim = $idPlanta";
    $conn->query($sql_clima);

    // Actualizar datos en la tabla SUELO
    $sql_suelo = "UPDATE SUELO 
                  SET Descripcions = '$descripcion_suelo' 
                  WHERE ID_Suelo = $idPlanta";
    $conn->query($sql_suelo);

    // Actualizar datos en la tabla CUIDADOS
    $sql_cuidados = "UPDATE CUIDADOS 
                     SET tipo_cuidado = '$tipo_cuidado', INFORMACION = '$informacion' 
                     WHERE ID_cuida = $idPlanta";
    $conn->query($sql_cuidados);

    // Actualizar datos en la tabla PLAGAS
    $sql_plagas = "UPDATE PLAGAS 
                   SET TIPO = '$tipo_plaga', Descripcion = '$descripcion_plaga' 
                   WHERE ID_PLAG = $idPlanta";
    $conn->query($sql_plagas);

    // Actualizar datos en la tabla FLORACION
    $sql_floracion = "UPDATE FLORACION 
                      SET inicio_flora = '$inicio_flora', fin_flora = '$fin_flora' 
                      WHERE ID_Flora = $idPlanta";
    $conn->query($sql_floracion);

    // Actualizar datos en la tabla REGION
    $sql_region = "UPDATE REGION 
                   SET Ciudad = '$ciudad' 
                   WHERE ID_Reg = $idPlanta";
    $conn->query($sql_region);

    // Actualizar datos en la tabla LOCALIZACION
    $sql_localizacion = "UPDATE LOCALIZACION 
                         SET Direccion = '$direccion', Ciudad = '$ciudad' 
                         WHERE ID_LOCALI = $idPlanta";
    $conn->query($sql_localizacion);

    // Verificar si se ha proporcionado una nueva imagen
if (!empty($_FILES["imagen"]["name"])) {
    // Mover la nueva imagen a una ubicación permanente
    $imagen_nombre = $_FILES["imagen"]["name"];
        $imagen_temporal = $_FILES["imagen"]["tmp_name"];
        $directorio_destino = "../Admin/imagen/"; // Ruta relativa al directorio raíz del servidor
        $ruta_imagen = $directorio_destino . $imagen_nombre;


    // Mover la imagen al directorio de destino
    if (move_uploaded_file($imagen_temporal, $ruta_imagen)) {
        // Actualizar la ruta de la imagen en la tabla IMAGEN
        $sql_update_imagen = "UPDATE IMAGEN 
                              SET direccion = '$ruta_imagen' 
                              WHERE FKID_Planta = $idPlanta";

        // Ejecutar la actualización de la imagen
        if ($conn->query($sql_update_imagen) === TRUE) {
            echo "Imagen actualizada correctamente.";
        } else {
            echo "Error al actualizar la imagen: " . $conn->error;
        }
    } else {
        echo "Error al mover la nueva imagen al directorio de destino.";
    }
}   

        $conn->commit();

        echo json_encode(array('success' => 'Los datos de la planta y sus relacionados han sido editadosexitosamente.'));
        } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(array('error' => 'Error al editar la planta: ' . $e->getMessage()));
        }

    // Cerrar conexión
    $conn->close();

    header("Location: Admin.html");
    exit; 
}
?>

