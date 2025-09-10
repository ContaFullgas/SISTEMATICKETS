<?php
session_start();
if (empty($_POST['mod_id'])) {
    $errors[] = "ID vacío";
} else if (empty($_POST['title'])) {
    $errors[] = "Titulo vacío";
} else if (empty($_POST['description'])) {
    $errors[] = "Description vacío";
} else if (
    !empty($_POST['title']) &&
    !empty($_POST['description'])
) {

    include "../config/config.php"; // Contiene la conexión a la base de datos

    $title = $_POST["title"];
    $description = $_POST["description"];
    $category_id = $_POST["category_id"];
    $project_id = $_POST["project_id"];
    $priority_id = $_POST["priority_id"];
    $user_id = $_SESSION["user_id"];
    $status_id = $_POST["status_id"];
    $asigned_id = $_POST["asigned_id"];
    $kind_id = $_POST["kind_id"];
    $id = $_POST['mod_id'];

    // Obtener tipousuario del usuario actual
    $sql_tipo = "SELECT tipousuario FROM user WHERE id = $user_id";
    $result_tipo = mysqli_query($con, $sql_tipo);
    if ($result_tipo && mysqli_num_rows($result_tipo) > 0) {
        $row_tipo = mysqli_fetch_assoc($result_tipo);
        $tipousuario = $row_tipo['tipousuario'];
    } else {
        $tipousuario = null;
    }

    // Validar que si es administrador, no pueda guardar si prioridad y agente no están asignados
    if ($tipousuario == 1 && $priority_id == 0 || $asigned_id == 0) {
        $errors[] = "Como administrador, no puedes guardar el ticket si no hay prioridad ni agente asignado.";
    } else {

        // Obtener el valor actual de status_id en la base de datos antes de actualizar
        $sql_get_status = "SELECT status_id FROM ticket WHERE id = $id";
        $result_status = mysqli_query($con, $sql_get_status);
        if ($result_status && mysqli_num_rows($result_status) > 0) {
            $row_status = mysqli_fetch_assoc($result_status);
            $current_status_id = $row_status['status_id'];
        } else {
            $current_status_id = null; // Si no encuentra, se asume nulo
        }

        // Obtener el valor actual de asigned_id en la base de datos antes de actualizar
        $sql_get_current = "SELECT asigned_id FROM ticket WHERE id = $id";
        $result_current = mysqli_query($con, $sql_get_current);
        if ($result_current && mysqli_num_rows($result_current) > 0) {
            $row_current = mysqli_fetch_assoc($result_current);
            $current_asigned_id = $row_current['asigned_id'];
        } else {
            $current_asigned_id = null; // Si no encuentra, se asume nulo
        }

        // Actualizar el ticket
        $sql = "UPDATE ticket SET title=\"$title\", category_id=\"$category_id\", project_id=\"$project_id\", priority_id=\"$priority_id\", description=\"$description\", status_id=\"$status_id\", asigned_id=\"$asigned_id\", kind_id=\"$kind_id\", updated_at=NOW() WHERE id=$id";

        $query_update = mysqli_query($con, $sql);
        if ($query_update) {
            $messages[] = "El ticket ha sido actualizado satisfactoriamente.";

            // Verificar si el valor de asigned_id cambió
            if ($current_asigned_id != $asigned_id) {
                $messages[] = "Agente asignado";

                // Después de actualizar, obtener el asigned_id actualizado
                $sql_get_asigned = "SELECT asigned_id FROM ticket WHERE id=$id";
                $result_asigned = mysqli_query($con, $sql_get_asigned);
                if ($result_asigned && mysqli_num_rows($result_asigned) > 0) {
                    $row_asigned = mysqli_fetch_assoc($result_asigned);
                    $assigned_id_value = $row_asigned['asigned_id'];

                    // Buscar el email del usuario asignado
                    $sql_user = "SELECT email FROM user WHERE id = $assigned_id_value";
                    $result_user = mysqli_query($con, $sql_user);
                    if ($result_user && mysqli_num_rows($result_user) > 0) {
                        $row_user = mysqli_fetch_assoc($result_user);
                        $email = $row_user['email'];

                        // Buscar en la tabla 'user' un usuario con tipousuario=1
                        $sql_tipousuario = "SELECT email FROM user WHERE tipousuario=1 LIMIT 1";
                        $result_tipousuario = mysqli_query($con, $sql_tipousuario);
                        $cc_email = null;
                        if ($result_tipousuario && mysqli_num_rows($result_tipousuario) > 0) {
                            $row_cc = mysqli_fetch_assoc($result_tipousuario);
                            $cc_email = $row_cc['email'];
                        }

                        // Preparar cabeceras de correo con formato correcto
                        $headers = "From: contabsistemas4@fullgas.com.mx\r\n";
                        $headers .= "Reply-To: contabsistemas4@fullgas.com.mx\r\n";
                        if ($cc_email) {
                            $headers .= "Cc: $cc_email\r\n";
                        }
                        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                        $headers .= "X-Mailer: PHP/" . phpversion();

                        // Enviar el correo
                        $subject = "Nuevo Ticket Asignado";
                        $message = "Has sido asignado a un nuevo ticket con folio $id. Por favor, ingresa al sistema para más detalles.";
                        $mail_sent = mail($email, $subject, $message, $headers);
                        if (!$mail_sent) {
                            $errors[] = "No se pudo enviar el correo a $email.";
                        }
                    } else {
                        $errors[] = "No se encontró el usuario con ID $assigned_id_value.";
                    }
                } else {
                    $errors[] = "No se pudo obtener el asignado del ticket.";
                }
            } else {
                $messages[] = "Agente sin cambios";
            }

            // Después de actualizar, obtener el user_id del ticket
            $sql_get_user = "SELECT user_id FROM ticket WHERE id=$id";
            $result_user = mysqli_query($con, $sql_get_user);
            if ($result_user && mysqli_num_rows($result_user) > 0) {
                $row_user = mysqli_fetch_assoc($result_user);
                $user_id_value = $row_user['user_id'];

                // Nuevo: verificar si status_id cambió a 3
                if ($current_status_id != $status_id && $status_id == 3) {
                    // Obtener información del usuario (email y name)
                    $sql_user = "SELECT email, name FROM user WHERE id = $user_id_value";
                    $result_user = mysqli_query($con, $sql_user);
                    if ($result_user && mysqli_num_rows($result_user) > 0) {
                        $user = mysqli_fetch_assoc($result_user);
                        $to = $user['email'];
                        $name = $user['name'];

                        // Enviar email
                        $subject = "Notificación de cambio de estado";
                        $message = "Hola " . $name . ",\n
                        El ticket con Folio: $id se cerró.";
                        $headers = "From: contabsistemas4@fullgas.com.mx\r\n";
                        $headers .= "Reply-To: contabsistemas4@fullgas.com.mx\r\n";
                        $headers .= "X-Mailer: PHP/" . phpversion();

                        mail($to, $subject, $message, $headers);
                    }
                }
            }
        } else {
            $errors[] = "Lo siento algo ha salido mal intenta nuevamente." . mysqli_error($con);
        }
    }
} else {
    $errors[] = "Error desconocido.";
}

// Mostrar errores
if (isset($errors)) {
?>
<div class="alert alert-danger" role="alert">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>Error!</strong>
<?php
foreach ($errors as $error) {
    echo $error . "<br>";
}
?>
</div>
<?php
}

// Mostrar mensajes
if (isset($messages)) {
?>
<div class="alert alert-success" role="alert">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>¡Bien hecho!</strong>
<?php
foreach ($messages as $message) {
    echo $message . "<br>";
}
?>
</div>
<?php
}
?>
