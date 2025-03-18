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

?>
    <div class="right_col" role="main"> <!-- page content -->
        <div class="">
            <div class="page-title">
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