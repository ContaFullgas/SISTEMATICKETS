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


		include "../config/config.php";//Contiene funcion que conecta a la base de datos

		//Consulta para asignar un agente al ticket de forma automatica
		// $users = mysqli_query($con, "select * from user where tipousuario = 1");

		// Verifica si hay resultados
		// if (mysqli_num_rows($users) > 0) {
		// 	$userIds = [];
		
		// 	// Almacena los IDs en un array
		// 	while ($row = mysqli_fetch_assoc($users)) {
		// 		$userIds[] = $row['id']; // Asegúrate de que 'id' es el nombre correcto de la columna
		// 	}
		
		// 	// Selecciona un ID aleatorio de un agente
		// 	$asigned_id = $userIds[array_rand($userIds)];
	
		// } else {
		// 	echo "No se encontraron usuarios.";
		// }

		$title = $_POST["title"];
		$description = $_POST["description"];
		$category_id = $_POST["category_id"];
		$project_id = $_POST["project_id"];
		// $priority_id = $_POST["priority_id"];
		$user_id = $_SESSION["user_id"];
		// $status_id = $_POST["status_id"];
		$kind_id = $_POST["kind_id"];
		$created_at="NOW()";

		// $user_id=$_SESSION['user_id'];

		// $sql= "INSERT INTO ticket (title, description, category_id, project_id, priority_id, user_id, status_id, asigned_id, kind_id, created_at) values (\"$title\",\"$description\",\"$category_id\",\"$project_id\",$priority_id,$user_id,$status_id,$asigned_id,$kind_id,$created_at)";
		//En la consulta de insertar, directamente inserta el ID del estatus como pendiente y prioridad como no asignado
		//Esto porque estos dos campos el administrador los asigna
		$sql= "INSERT INTO ticket (title, description, category_id, project_id, priority_id, user_id, status_id, kind_id, asigned_id,created_at) values (\"$title\",\"$description\",\"$category_id\",\"$project_id\",0,$user_id,1,$kind_id,0,$created_at)";

		$query_new_insert = mysqli_query($con,$sql);
			if ($query_new_insert){
				$ticket_id = mysqli_insert_id($con); // 🔥 Obtiene el ID del ticket recién insertado
				$messages[] = "Tu ticket ha sido ingresado satisfactoriamente.";
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

	// codigo nuevo para adjuntar archivos
	// Verificar si se han subido archivos
	if (isset($_FILES['imagen'])) {
		// Ruta absoluta a la carpeta de destino en tu servidor
		$upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/xampp/SISTEMATICKETS/images/ticket/";

		// Recorrer todos los archivos subidos
		foreach ($_FILES['imagen']['tmp_name'] as $key => $tmp_name) {
			// Obtener el nombre del archivo
			$file_name = basename($_FILES['imagen']['name'][$key]);
			$target_file = $upload_dir . uniqid() . "_" . $file_name; // Usamos un identificador único para evitar sobrescribir archivos

			// Verificar que el archivo sea válido
			if (move_uploaded_file($tmp_name, $target_file)) {
				// El archivo se subió correctamente, ahora guardamos la ruta en la base de datos

				// Insertar la ruta en la base de datos
				$query = "INSERT INTO rutadearchivos (rutadearchivos, ticket_id) VALUES ('$target_file','$ticket_id')";
				if (mysqli_query($con, $query)) {
					echo "Archivo subido y guardado exitosamente: $file_name";
				} else {
					echo "Error al guardar la ruta en la base de datos.";
				}
			} else {
				echo "Error al subir el archivo: $file_name";
			}
		}
	} else {
		echo "No se han seleccionado archivos.";
	}
?>