<?php
include("../config/config.php"); // Conexión a base de datos

if (isset($_GET['ticket_id'])) {
    $ticket_id = intval($_GET['ticket_id']);
    
    $sql = "SELECT status_id FROM ticket WHERE id = $ticket_id";
    $query = mysqli_query($con, $sql);
    $ticket = mysqli_fetch_assoc($query);

    if ($ticket['status_id'] == 3) { // Asumimos que 3 = 'Terminado'
        echo "terminado";
    } else {
        echo "abierto";
    }
}
?>
