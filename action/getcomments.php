<?php
include("../config/config.php"); // Tu conexión a la base de datos

if (isset($_GET['ticket_id'])) {
    $ticket_id = intval($_GET['ticket_id']);
    $sql = "SELECT * FROM ticket_comments WHERE ticket_id = $ticket_id ORDER BY created_at ASC";
    $query = mysqli_query($con, $sql);
    
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<div class='comment'>";
        echo "<p>" . htmlspecialchars($row['comment']) . "</p>";
        echo "<small>" . $row['created_at'] . "</small>";
        echo "<hr style='margin: 2px 0;'>";
        echo "</div>";
    }
}
?>
