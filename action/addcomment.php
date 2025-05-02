<?php
include("../config/config.php"); // Tu conexión a la base de datos

if (isset($_POST['ticket_id']) && isset($_POST['comment'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    
    $sql = "INSERT INTO ticket_comments (ticket_id, comment) VALUES ('$ticket_id', '$comment')";
    if (mysqli_query($con, $sql)) {
        echo "Comentario agregado";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
