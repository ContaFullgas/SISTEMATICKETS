<?php
    $title ="Evaluaciones | ";
    include "head.php";
    include "sidebar.php";
?>  
<div class="right_col" role="main"><!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="clearfix"></div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php
                    // include("modal/new_user.php");
                    // include("modal/upd_user.php");
                ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Evaluaciones</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <!-- form search -->
                    <form class="form-horizontal" role="form" id="datos_cotizacion">
                        <div class="form-group row">
                            <label for="q" class="col-md-2 control-label">Agente</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="q" placeholder="Nombre del agente" onkeyup='load(1);'>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-default" onclick='load(1);'>
                                    <span class="glyphicon glyphicon-search" ></span> Buscar</button>
                                <!-- <span id="loader"></span> -->
                            </div>
                        </div>
                    </form>   
                    <!-- end form search -->

                    <div class="x_content">
                        <div class="table-responsive">
                            <!-- ajax -->
                            <div id="resultados_ajax"></div><!-- Carga los datos ajax -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /page content -->

<?php include "footer.php" ?>

<script type="text/javascript" src="js/users.js"></script>

<script>
// Función para cargar los datos de las evaluaciones
function load(page){
    var q = $("#q").val();  // Captura lo que se escribe en el campo de búsqueda
    $("#resultados_ajax").fadeIn('slow');  // Muestra el área donde se cargarán los resultados

    $.ajax({
        url: 'ajax/evaluaciones_ajax.php',  // Archivo que contiene la consulta SQL
        data: { q: q },  // Datos para filtrar (si es que hay búsqueda)
        success:function(data){
            $("#resultados_ajax").html(data);  // Coloca los resultados en el contenedor
        }
    })
}

// Llamar a la función load(1) para cargar los resultados automáticamente al cargar la página
load(1);
</script>
