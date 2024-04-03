<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "9@xYwHE@P&9DQ5bS";
$dbname = "prueba";

$conn = new mysqli($servername, $username, $password, $dbname);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $dni = $_POST["dni"];

    // Verificar si el usuario es un administrador
    $sql = "SELECT * FROM ADMINISTRADOR WHERE email = '$email'";
    $result_admin = $conn->query($sql);

    if ($result_admin->num_rows > 0) {
        // Email existe, verifica el DNI
        $row = $result_admin->fetch_assoc();
        $stored_dni = $row["DNI"];
        if ($dni == $stored_dni) {
            // Usuario es administrador
            $_SESSION['correoUsuario'] = $email;
            setcookie("email", $email, time() + 31536000, "/");
            header("Location: ../Admin/Admin.html"); // Redirigir al panel de administrador
            exit();
        } else {
            // DNI incorrecto, muestra un mensaje de error específico
            $error_message = "DNI incorrecto.";
            header("Location: login.html?error_message=" . urlencode($error_message));
            exit();
        }
    }

    // Verificar si el usuario es un investigador
    $sql = "SELECT * FROM INVESTIGADORES WHERE email = '$email'";
    $result_investigador = $conn->query($sql);

    if ($result_investigador->num_rows > 0) {
        $row = $result_investigador->fetch_assoc();
        $stored_dni = $row["dni"];
        if ($dni == $stored_dni) {
            // Usuario es investigador
            $_SESSION['correoUsuario'] = $email;
            setcookie("email", $email, time() + 31536000, "/");
            header("Location: ../Admin/investigador.html"); // Redirigir al panel de investigador
            exit();
        } else {
            // DNI incorrecto, muestra un mensaje de error específico
            $error_message = "DNI incorrecto.";
            header("Location: login.html?error_message=" . urlencode($error_message));
            exit();
        }
    }

    // Si no se encontró ningún usuario con ese correo, mostrar mensaje de error
    $error_message = "Usuario no encontrado.";
    header("Location: login.html?error_message=" . urlencode($error_message));
    exit();
}

$conn->close();
?>

