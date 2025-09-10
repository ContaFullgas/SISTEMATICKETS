<?php
session_start();
/*Inicia validacion del lado del servidor*/
if (empty($_POST['title'])) {
    $errors[] = "Titulo vacío";
} else if (empty($_POST['description'])){
    $errors[] = "Description vacío";
} else if (
    !empty($_POST['title']) &&
    !empty($_POST['description'])
){
    include "../config/config.php"; // Conecta a la base de datos

    $title = $_POST["title"];
    $description = $_POST["description"];
    $category_id = $_POST["category_id"];
    $project_id = $_POST["project_id"];
    $user_id = $_SESSION["user_id"]; // ID del usuario en sesión
    $kind_id = $_POST["kind_id"];
    $created_at = "NOW()";

    // Inserta el ticket
    $sql= "INSERT INTO ticket (title, description, category_id, project_id, priority_id, user_id, status_id, kind_id, asigned_id, created_at) 
            VALUES (\"$title\",\"$description\",\"$category_id\",\"$project_id\",2,$user_id,1,$kind_id,0,$created_at)";

    $query_new_insert = mysqli_query($con,$sql);
    if ($query_new_insert){
        $ticket_id = mysqli_insert_id($con); // Obtiene el ID del ticket recién insertado
        $messages[] = "Tu ticket ha sido ingresado satisfactoriamente.";

        // Obtener el email del usuario
        $sql_user = "SELECT email, name FROM user WHERE id='$user_id'";
        $result_user = mysqli_query($con, $sql_user);
        if ($result_user && mysqli_num_rows($result_user) > 0) {
            $row_user = mysqli_fetch_assoc($result_user);
            $email_usuario = $row_user['email'];
			$name = $row_user['name'];


            // Enviar correo al usuario
            $subject = "Nuevo ticket creado";
            $message = "Hola " . $name . ",\n
Se ha creado un nuevo ticket con ID: $ticket_id.\nTítulo: $title
Descripción: $description

Gracias.";
$headers = "From: contabsistemas4@fullgas.com.mx\r\n";
$headers .= "Reply-To: contabsistemas4@fullgas.com.mx charset=UTF-8\r\n";

            // Función para enviar el email
            mail($email_usuario, $subject, $message, $headers);
        } else {
            // No se encontró el email del usuario, puedes manejarlo si quieres
            error_log("No se encontró el email para el usuario ID: $user_id");
        }
    } else{
        $errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
    }
} else {
    $errors []= "Error desconocido.";
}

if (isset($errors)){
?>
<div class="alert alert-danger" role="alert">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>Error!</strong>
<?php
foreach ($errors as $error) {
    echo $error;
}
?>
</div>
<?php
}
if (isset($messages)){
?>
<div class="alert alert-success" role="alert">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>¡Bien hecho!</strong>
<?php
foreach ($messages as $message) {
    echo $message;
}
?>
</div>
<?php
}

// Código para subir archivos (igual que antes)
if (isset($_FILES['imagen'])) {
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/xampp/SISTEMATICKETS/images/ticket/";
    foreach ($_FILES['imagen']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['imagen']['name'][$key]);
        $target_file = $upload_dir . uniqid() . "_" . $file_name;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $query = "INSERT INTO rutadearchivos (rutadearchivos, ticket_id) VALUES ('$target_file','$ticket_id')";
            mysqli_query($con, $query);
        }
    }
} else {
    echo "No se han seleccionado archivos.";
}
?>
