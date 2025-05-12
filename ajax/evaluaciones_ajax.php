<?php
include "../config/config.php"; // Asegúrate de que esta ruta es correcta

$q = isset($_REQUEST['q']) ? mysqli_real_escape_string($con, strip_tags($_REQUEST['q'])) : '';

// Condición de búsqueda
$sWhere = "WHERE agente.name LIKE '%$q%' 
            OR evaluador.name LIKE '%$q%' 
            OR te.ticket_id LIKE '%$q%'";

// Consulta SQL para obtener evaluaciones con nombres
$sql = "SELECT 
            te.id AS evaluacion_id, 
            te.ticket_id, 
            evaluador.name AS nombre_evaluador, 
            te.calificacion, 
            te.motivo, 
            agente.name AS nombre_agente 
        FROM ticket_evaluation te 
        JOIN ticket t ON te.ticket_id = t.id 
        JOIN user agente ON t.asigned_id = agente.id 
        JOIN user evaluador ON te.user_id = evaluador.id 
        $sWhere 
        ORDER BY te.created_at DESC";

$query = mysqli_query($con, $sql);
?>

<style>
    .emoji-lg {
        font-size: 30px;
    }
</style>

<table class="table table-striped jambo_table bulk_action">
    <thead>
        <tr>
            <th>Folio</th>
            <th>Agente Evaluado</th>
            <th>Evaluador</th>
            <th>Calificación</th>
            <th>Motivo</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($query)): ?>
            <tr>
                <td><?php echo $row['ticket_id']; ?></td>
                <td><?php echo $row['nombre_agente']; ?></td>
                <td><?php echo $row['nombre_evaluador']; ?></td>
                <td>
                    <?php
                        if ($row['calificacion'] == 1) echo "<span class='emoji-lg'>😠</span>";
                        elseif ($row['calificacion'] == 2) echo "<span class='emoji-lg'>😐</span>";
                        else echo "<span class='emoji-lg'>😊</span>";
                    ?>
                </td>
                <td><?php echo $row['motivo'] ? htmlentities($row['motivo']) : "<span class='text-muted'>—</span>"; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
