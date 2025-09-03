<?php 
    $title ="Dashboard - "; 
    include "head.php";
    include "sidebar.php";

    // $userId = $_SESSION['user_id'];
    $TicketData=mysqli_query($con, "select * from ticket where status_id=1");
    $ProjectData=mysqli_query($con, "select * from project");
    $CategoryData=mysqli_query($con, "select * from category");
    $UserData=mysqli_query($con, "select * from user order by created_at desc");
    // $MyUser=mysqli_query($con, "select * from user where id = $userId");

    //Arreglo que almacena los datos del usuario que inicia sesion
    // $arregloUsuario = [];
    // $arregloUsuario =mysqli_fetch_array($MyUser);

    // echo "<pre>";  // Formatea la salida para mejorar la visualización
    // print_r($arregloUsuario['tipousuario']);
    // echo "</pre>";
    
    // Detecta tipousuario desde donde lo tengas disponible
    $rol = null;
    if (isset($arregloUsuario['tipousuario'])) {
        $rol = (int)$arregloUsuario['tipousuario'];
    } elseif (isset($_SESSION['tipousuario'])) {
        $rol = (int)$_SESSION['tipousuario'];
    }

    // Admin es 1
    $ES_ADMIN = ($rol === 1);

?>


    <div class="right_col" role="main"> <!-- page content -->
        <div class="">

        <!-- Consulta para extraer el folio del ticket y el nombre del agente para la notificacion de evaluacion de tickets -->
        <?php
            $userId = $_SESSION['user_id'];
            $ticketCheck = mysqli_query($con, "
                SELECT t.id, u.name AS agente
                FROM ticket t
                LEFT JOIN user u ON t.asigned_id = u.id
                LEFT JOIN ticket_evaluation te ON t.id = te.ticket_id 
                WHERE t.user_id = '$userId' AND t.status_id = 3 AND te.id IS NULL
            ");

            $ticketPendiente = mysqli_fetch_assoc($ticketCheck);
            $idTicketPendiente = $ticketPendiente['id'] ?? null;
            $agenteAsignado = $ticketPendiente['agente'] ?? 'No asignado';



            if ($idTicketPendiente) {
                echo '<div class="alert alert-info" role="alert" style="font-size: 17px;">
                        Tienes un ticket terminado con Folio ' . $idTicketPendiente . ' pendiente por evaluar.
                        <br> <br>
                        <button id="openEvaluationModal" class="btn btn-warning">Evaluar Ticket</button>
                    </div>';
            }
        ?>

        <!-- Codigo para mostrar notificacion de tickets -->
        <?php
        // Solo mostrar notificación si es agente (ajusta según cómo definas los roles)
        if ($arregloUsuario['tipousuario'] == 2) { // Suponiendo que 2 es "agente"
            $ticketsAsignados = mysqli_query($con, "
                SELECT id, title 
                FROM ticket 
                WHERE asigned_id = $userId AND status_id IN (1, 2)
            ");
            
            if (mysqli_num_rows($ticketsAsignados) > 0) {
                echo '<div class="alert alert-info" role="alert" style="font-size: 17px;">';
                echo 'Tienes tickets asignados pendientes de atender:<br><ul>';
                while ($ticket = mysqli_fetch_assoc($ticketsAsignados)) {
                    echo '<li><strong>Folio:</strong> ' . $ticket['id'] . htmlspecialchars($ticket['title']) . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            }
        }
        ?>

            <div class="page-title">
            <!-- Validacion para mostrar los datos generales solo al administrador -->
              <?php if ($ES_ADMIN): ?>
                <div class="row top_tiles">
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-ticket"></i></div>
                          <div class="count"><?php echo mysqli_num_rows($TicketData) ?></div>
                          <h3>Tickets Pendientes</h3>
                        </div>
                    </div>
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-list-alt"></i></div>
                          <div class="count"><?php echo mysqli_num_rows($ProjectData) ?></div>
                          <h3>Proyectos</h3>
                        </div>
                    </div>
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-th-list"></i></div>
                          <div class="count"><?php echo mysqli_num_rows($CategoryData) ?></div>
                          <h3>Categorias</h3>
                        </div>
                    </div>
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-users"></i></div>
                          <div class="count"><?php echo mysqli_num_rows($UserData) ?></div>
                          <h3>Usuarios</h3>
                        </div>
                    </div>
                </div>
              <?php endif; ?>
                <!-- content -->
                <br><br>
                <div class="row">
                    <div class="col-md-4">
                        <div class="image view view-first">
                            <img class="thumb-image" style="width: 100%; display: block;" src="images/profiles/<?php echo $profile_pic; ?>" alt="image" />
                        </div>
                        <span class="btn btn-my-button btn-file">
                            <form method="post" id="formulario" enctype="multipart/form-data">
                            Cambiar Imagen de perfil: <input type="file" name="file">
                            </form>
                        </span>
                        <div id="respuesta"></div>
                    </div>
                    <div class="col-md-8 col-xs-12 col-sm-12">
                        <?php include "lib/alerts.php";
                            profile(); //llamada a la funcion de alertas
                        ?>    
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Informacion personal</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                            <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />
                                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="action/upd_profile.php" method="post">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!-- Clase que usa el arregloUsuario para bloquear las etiquetas de acuerdo al perfil del usuario -->
                                            <input type="text" name="name" id="first-name" class="form-control col-md-7 col-xs-12" value="<?php echo $name; ?>" <?php echo ($arregloUsuario['tipousuario'] != 1) ? 'disabled' : ''; ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Correo electronico 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!-- Clase que usa el arregloUsuario para bloquear las etiquetas de acuerdo al perfil del usuario -->
                                            <input type="text" id="last-name" name="email" class="form-control col-md-7 col-xs-12" value="<?php echo $email; ?>" <?php echo ($arregloUsuario['tipousuario'] != 1) ? 'disabled' : ''; ?>>
                                        </div>
                                    </div>

                                    <br><br><br>
                                    <h2 style="padding-left: 50px">Cambiar Contraseña</h2>
                            
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Contraseña antigua
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!-- Clase que usa el arregloUsuario para bloquear las etiquetas de acuerdo al perfil del usuario -->
                                            <input id="birthday" name="password" class="date-picker form-control col-md-7 col-xs-12" type="text" placeholder="**********" <?php echo ($arregloUsuario['tipousuario'] != 1) ? 'disabled' : ''; ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nueva contraseña 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!-- Clase que usa el arregloUsuario para bloquear las etiquetas de acuerdo al perfil del usuario -->
                                            <input id="birthday" name="new_password" class="date-picker form-control col-md-7 col-xs-12" type="text" <?php echo ($arregloUsuario['tipousuario'] != 1) ? 'disabled' : ''; ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Confirmar contraseña nueva
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!-- Clase que usa el arregloUsuario para bloquear las etiquetas de acuerdo al perfil del usuario -->
                                            <input id="birthday" name="confirm_new_password" class="date-picker form-control col-md-7 col-xs-12" type="text" <?php echo ($arregloUsuario['tipousuario'] != 1) ? 'disabled' : ''; ?>>
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <!-- Clase que usa el arregloUsuario para bloquear las etiquetas de acuerdo al perfil del usuario -->
                                            <button type="submit" name="token" class="btn btn-success" <?php echo ($arregloUsuario['tipousuario'] != 1) ? 'disabled' : ''; ?>>Actualizar Datos</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /page content -->

<?php include "footer.php" ?>

<!-- Modal Evaluación de tickets -->
<div id="evaluationModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="formEvaluarTicket">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" style="font-size: 2rem;">Evaluar Atención del Ticket</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center" style="font-size: 1.2rem;">
          <input type="hidden" name="ticket_id" id="ticket_id_evaluar" value="">
          
          <p style="font-size: 2rem;"><strong>Folio del Ticket: </strong><?php echo $idTicketPendiente ?> <span id="folioTexto"></span></p>
          <p style="font-size: 2rem;"><strong>Agente Asignado: </strong><?php echo $agenteAsignado; ?></p>

          <hr>

        <div class="my-3">
            <label style="font-size: 5rem; margin: 0 45px;"><input type="radio" name="calificacion" value="1"> 😠</label>
            <label style="font-size: 5rem; margin: 0 45px;"><input type="radio" name="calificacion" value="2"> 😐</label>
            <label style="font-size: 5rem; margin: 0 45px;"><input type="radio" name="calificacion" value="3"> 😊</label>
        </div>


          <div id="motivoArea" style="display:none; margin-top: 15px;">
            <textarea name="motivo" class="form-control" rows="3" placeholder="¿Por qué la mala calificación?" style="font-size: 1.5rem;"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" style="font-size: 1.5rem;">Enviar evaluación</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script>
    $(document).ready(function() {
        // Abrir el modal y asignar ticket_id
        $("#openEvaluationModal").click(function() {
        $("#ticket_id_evaluar").val("<?php echo $idTicketPendiente; ?>");
        $("#evaluationModal").modal("show");
        });

        // Mostrar campo de motivo si se elige carita negativa
        $("input[name='calificacion']").on("change", function() {
        if ($(this).val() == "1") {
            $("#motivoArea").show();
        } else {
            $("#motivoArea").hide();
        }
        });

        // Enviar evaluación
        $("#formEvaluarTicket").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "action/evaluar_ticket.php",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
            alert("Gracias por tu evaluación.");
            $("#evaluationModal").modal("hide");
            location.reload();
            }
        });
        });
    });
</script>


<script>
    $(function(){
        $("input[name='file']").on("change", function(){
            var formData = new FormData($("#formulario")[0]);
            var ruta = "action/upload-profile.php";
            $.ajax({
                url: ruta,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos)
                {
                    $("#respuesta").html(datos);
                    //recarga la página para que se vea el cambio de foto de perfil al instante
                    location.reload()
                }
            });
        });
    });
</script>