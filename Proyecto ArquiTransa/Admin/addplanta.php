<?php
// Lineas para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se han enviado datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = ""; // La contraseña de tu base de datos
    $dbname = "prueba"; // Nombre de tu base de datos

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->begin_transaction();

    try {
    // Preparar los datos para su inserción en la base de datos
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
    // Aquí puedes continuar con el resto de datos...

// Insertar datos en la tabla PLANTAS
    $sql_plantas = "INSERT INTO PLANTAS (Nombrep, Nombre_cientifico) VALUES ('$nombre', '$nombre_cientifico')";
    $conn->query($sql_plantas);
    // Obtener el ID de la planta insertada
    $id_planta = $conn->insert_id;

        // Mover la imagen a una ubicación permanente
    $imagen_nombre = $_FILES["imagen"]["name"];
    $imagen_temporal = $_FILES["imagen"]["tmp_name"];
    $directorio_destino = "../Admin/imagen/"; // Cambiar a tu directorio destino
    $ruta_imagen = $directorio_destino . $imagen_nombre;

    // Mover la imagen al directorio de destino
    if (move_uploaded_file($imagen_temporal, $ruta_imagen)) {
        // Insertar la ruta de la imagen en la tabla IMAGEN
        $sql_insert_imagen = "INSERT INTO IMAGEN (FKID_Planta, direccion)
                                VALUES ('$id_planta', '$ruta_imagen')";

        // Ejecutar la inserción de la imagen
        if ($conn->query($sql_insert_imagen) === TRUE) {
            echo "Datos de planta e imagen insertados correctamente.";
        } else {
            echo "Error al insertar la imagen: " . $conn->error;
        }
    } else {
        echo "Error al mover la imagen al directorio de destino.";
    }

    // Insertar datos en la tabla TAXONOMIA
    $sql_taxonomia = "INSERT INTO TAXONOMIA (nombre, reino, division, subdivision, clase, subclase, orden, familia, genero, especie, FKID_Planta)
                      VALUES ('$nombre_cientifico', '$reino', '$division', '$subdivision', '$clase', '$subclase', '$orden', '$familia', '$genero', '$especie', '$id_planta')";
    $conn->query($sql_taxonomia);

    // Insertar datos en la tabla JARDIN_BOTANICO
    $sql_jardin_botanico = "INSERT INTO JARDIN_BOTANICO (nombrep, nombrej, ubicacion, superficie, fecha_creacion)
                            VALUES ('$nombre_cientifico', '$jardin_botanico', '$ubicacion', '$superficie_decimal', NOW())";
    $conn->query($sql_jardin_botanico);

    // Insertar datos en la tabla ZONA_RESERVA
    $sql_zona_reserva = "INSERT INTO ZONA_RESERVA (nombrep, nombrez) VALUES ('$nombre_cientifico', '$zona_reserva')";
    $conn->query($sql_zona_reserva);

        // Insertar datos en la tabla PINES
        $sql_pines = "INSERT INTO PINES (nombre, latitud, longitud) VALUES ('$nombre_cientifico', '$latitud', '$longitud')";
        $conn->query($sql_pines);
    
        // Insertar datos en la tabla CLIMA
        $sql_clima = "INSERT INTO CLIMA (nombrec, descripcionc, temperatura_promedio, humedad_promedio)
                      VALUES ('$ciudad_clima', '$descripcion_clima', '$temperatura_promedio', '$humedad_promedio')";
        $conn->query($sql_clima);
    
        // Insertar datos en la tabla SUELO
        $sql_suelo = "INSERT INTO SUELO (Nombre, Descripcions) VALUES ('$nombre_cientifico', '$descripcion_suelo')";
        $conn->query($sql_suelo);
    
        // Insertar datos en la tabla CUIDADOS
        $sql_cuidados = "INSERT INTO CUIDADOS (Nombre, tipo_cuidado ,INFORMACION) VALUES ('$nombre_cientifico','$tipo_cuidado','$informacion')";
        $conn->query($sql_cuidados);
    
        $sql_plagas = "INSERT INTO PLAGAS (Nombre, TIPO, Descripcion) VALUES ('$nombre_cientifico', '$tipo_plaga', '$descripcion_plaga')";
        $conn->query($sql_plagas);
    
        // Insertar datos en la tabla FLORACION
        $sql_floracion = "INSERT INTO FLORACION (nombre, inicio_flora, fin_flora) VALUES ('$nombre_cientifico', '$inicio_flora', '$fin_flora')";
        $conn->query($sql_floracion);
    
        // Insertar datos en la tabla REGION
        $sql_region = "INSERT INTO REGION (Nombre, Ciudad) VALUES ('$nombre_cientifico', '$ciudad')";
        $conn->query($sql_region);

        // Insertar datos en la tabla LOCALIZACION
        $sql_localizacion = "INSERT INTO LOCALIZACION (Nombre, Direccion ,Ciudad) VALUES ('$nombre_cientifico','$direccion' ,'$ciudad')";
        $conn->query($sql_localizacion);
    
        // Mover la imagen a una ubicación permanente
        $imagen_nombre = $_FILES["imagen"]["name"];
        $imagen_temporal = $_FILES["imagen"]["tmp_name"];
        $directorio_destino = "../Admin/imagen/"; // Cambiar a tu directorio destino
        $ruta_imagen = $directorio_destino . $imagen_nombre;
    
        $conn->commit();

        echo json_encode(array('success' => 'Los datos de la planta y sus relacionados han sido eliminados exitosamente.'));
        } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(array('error' => 'Error al insertar la planta: ' . $e->getMessage()));
        }
    
        // Cerrar conexión
        $conn->close();

    // Redireccionar a Admin.html
    header("Location: Admin.html");
    exit;
    }
    ?>
