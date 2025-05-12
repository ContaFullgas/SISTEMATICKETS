
<?php
session_start();
include("../config/config.php"); // Tu conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $calificacion = $_POST['calificacion'];
    $motivo = isset($_POST['motivo']) ? mysqli_real_escape_string($con, $_POST['motivo']) : null;
    $user_id = $_SESSION['user_id'];

    // Validar si el ticket pertenece al usuario
    $check = mysqli_query($con, "SELECT id FROM ticket WHERE id = $ticket_id AND user_id = $user_id");
    if (mysqli_num_rows($check) == 0) {
        http_response_code(403);
        echo "No autorizado.";
        exit;
    }

      // Validar que la calificación no sea 0
    if ($calificacion == 0) {
        http_response_code(400);
        echo "La calificación no puede ser 0.";
        exit;
    }

    // Insertar evaluación
    $query = "INSERT INTO ticket_evaluation (ticket_id, user_id, calificacion, motivo)
              VALUES ('$ticket_id', '$user_id', '$calificacion', " . ($motivo ? "'$motivo'" : "NULL") . ")";

    if (mysqli_query($con, $query)) {
        echo "Evaluación guardada.";
    } else {
        http_response_code(500);
        echo "Error al guardar.";
    }
}
?>
