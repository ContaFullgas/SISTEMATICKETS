<?php
    $title ="Tickets | ";
    include "head.php";
    include "sidebar.php";
?>

    <div class="right_col" role="main"><!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="clearfix"></div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php
                        include("modal/new_ticket.php");
                        include("modal/upd_ticket.php");
                    ?>
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Gastos</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        
                        <!-- form seach -->
                        <form class="form-horizontal" role="form" id="gastos">
                            <div class="form-group row">
                                <label for="q" class="col-md-2 control-label">Nombre/Asunto</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="q" placeholder="Nombre del ticket" onkeyup='load(1);'>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-default" onclick='load(1);'>
                                        <span class="glyphicon glyphicon-search" ></span> Buscar</button>
                                    <span id="loader"></span>
                                </div>
                            </div>
                        </form>     
                        <!-- end form seach -->


                        <div class="x_content">
                            <div class="table-responsive">
                                <!-- ajax -->
                                    <div id="resultados"></div><!-- Carga los datos ajax -->
                                    <div class='outer_div'></div><!-- Carga los datos ajax -->
                                <!-- /ajax -->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /page content -->

<?php include "footer.php" ?>

<script type="text/javascript" src="js/ticket.js"></script>
<script type="text/javascript" src="js/VentanaCentrada.js"></script>
<script>

// $("#add").submit(function(event) {
//   $('#save_data').attr("disabled", true);
  
//  var parametros = $(this).serialize();
//      $.ajax({
//             type: "POST",
//             url: "action/addticket.php",
//             data: parametros,
//              beforeSend: function(objeto){
//                 $("#result").html("Mensaje: Cargando...");
//               },
//             success: function(datos){
//             $("#result").html(datos);
//             $('#save_data').attr("disabled", false);
//             load(1);
//           }
//     });
//   event.preventDefault();

//   var formData = new FormData($(this)[0]); // Crear un objeto FormData con los datos del formulario
    
//     $.ajax({
//         type: "POST",
//         url: "action/addticket.php", // El archivo PHP que procesará la subida del archivo
//         data: formData,
//         contentType: false, // No especificamos el tipo de contenido porque FormData lo maneja automáticamente
//         processData: false, // No procesamos los datos ya que FormData lo maneja
//         beforeSend: function() {
//             $("#result").html("Mensaje: Cargando...");
//         },
//         success: function(response) {
//             $("#result").html(response); // Muestra la respuesta de PHP (si la subida fue exitosa o no)
//             $('#save_data').attr("disabled", false); // Habilita nuevamente el botón
//         }
//     });
    
//     event.preventDefault(); // Previene que el formulario se envíe de manera tradicional (recargando la página)

// })


//Se modifico para poder adjuntar el archivo y guardarlo en el servidor y su url en la base de datos
$("#add").submit(function(event) {
    event.preventDefault(); // Evita el envío tradicional del formulario
    $('#save_data').attr("disabled", true); // Deshabilita el botón para evitar múltiples envíos

    var formData = new FormData($(this)[0]); // Crear un objeto FormData con los datos del formulario

    $.ajax({
        type: "POST",
        url: "action/addticket.php", // Archivo PHP que procesará la solicitud
        data: formData,
        contentType: false, // No especificamos el tipo de contenido porque FormData lo maneja automáticamente
        processData: false, // No procesamos los datos ya que FormData lo maneja
        beforeSend: function() {
            $("#result").html("Mensaje: Cargando...");
        },
        success: function(response) {
            $("#result").html(response); // Muestra la respuesta del servidor
            $('#save_data').attr("disabled", false); // Habilita nuevamente el botón
            load(1); // Recargar la tabla con los datos nuevos
        }
    });
});



$( "#upd" ).submit(function( event ) {
  $('#upd_data').attr("disabled", true);
  
 var parametros = $(this).serialize();
     $.ajax({
            type: "POST",
            url: "action/updticket.php",
            data: parametros,
             beforeSend: function(objeto){
                $("#result2").html("Mensaje: Cargando...");
              },
            success: function(datos){
            $("#result2").html(datos);
            $('#upd_data').attr("disabled", false);
            load(1);
          }
    });
  event.preventDefault();
})

    function obtener_datos(id){
        var description = $("#description"+id).val();
        var title = $("#title"+id).val();
        var kind_id = $("#kind_id"+id).val();
        var project_id = $("#project_id"+id).val();
        var category_id = $("#category_id"+id).val();
        var priority_id = $("#priority_id"+id).val();
        var status_id = $("#status_id"+id).val();
        var asigned_id = $("#asigned_id"+id).val();
            $("#mod_id").val(id);
            $("#mod_title").val(title);
            $("#mod_description").val(description);
            $("#mod_kind_id").val(kind_id);
            $("#mod_project_id").val(project_id);
            $("#mod_category_id").val(category_id);
            $("#mod_priority_id").val(priority_id);
            $("#mod_status_id").val(status_id);
            $("#mod_asigned_id").val(asigned_id);
        }

// codigo nuevo para manejar la subida de archivos
// $("#add").submit(function(event) {
//     $('#save_data').attr("disabled", true); // Deshabilita el botón para evitar envíos múltiples
    
//     var formData = new FormData($(this)[0]); // Crear un objeto FormData con los datos del formulario
    
//     $.ajax({
//         type: "POST",
//         url: "action/addticket.php", // El archivo PHP que procesará la subida del archivo
//         data: formData,
//         contentType: false, // No especificamos el tipo de contenido porque FormData lo maneja automáticamente
//         processData: false, // No procesamos los datos ya que FormData lo maneja
//         beforeSend: function() {
//             $("#result").html("Mensaje: Cargando...");
//         },
//         success: function(response) {
//             $("#result").html(response); // Muestra la respuesta de PHP (si la subida fue exitosa o no)
//             $('#save_data').attr("disabled", false); // Habilita nuevamente el botón
//         }
//     });
    
//     event.preventDefault(); // Previene que el formulario se envíe de manera tradicional (recargando la página)
// });


</script>