<?php
$title = "Reportes | ";
include "head.php";
include "sidebar.php";

// Primero, obtenemos el tipo de usuario de la base de datos
$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($con, "SELECT tipousuario FROM user WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($user_query);
$user_type = $user_data['tipousuario'];

$projects = mysqli_query($con, "SELECT * FROM project");
$priorities = mysqli_query($con, "SELECT * FROM priority");
$statuses = mysqli_query($con, "SELECT * FROM status");
$kinds = mysqli_query($con, "SELECT * FROM kind");

?>

<div class="right_col" role="main"><!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="clearfix"></div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Usuarios</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <!-- form search -->
                    <form class="form-horizontal" role="form">
                        <input type="hidden" name="view" value="reports">
                        <div class="form-group">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-male"></i></span>
                                    <select name="project_id" class="form-control">
                                        <option value="">PROJECTO</option>
                                        <?php foreach ($projects as $p) : ?>
                                            <option value="<?php echo $p['id']; ?>" <?php if (isset($_GET["project_id"]) && $_GET["project_id"] == $p['id']) { echo "selected"; } ?>><?php echo $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-support"></i></span>
                                    <select name="priority_id" class="form-control">
                                        <option value="">PRIORIDAD</option>
                                        <?php foreach ($priorities as $p) : ?>
                                            <option value="<?php echo $p['id']; ?>" <?php if (isset($_GET["priority_id"]) && $_GET["priority_id"] == $p['id']) { echo "selected"; } ?>><?php echo $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon">INICIO</span>
                                    <input type="date" name="start_at" value="<?php if (isset($_GET["start_at"]) && $_GET["start_at"] != "") { echo $_GET["start_at"]; } ?>" class="form-control" placeholder="Palabra clave">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon">FIN</span>
                                    <input type="date" name="finish_at" value="<?php if (isset($_GET["finish_at"]) && $_GET["finish_at"] != "") { echo $_GET["finish_at"]; } ?>" class="form-control" placeholder="Palabra clave">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon">ESTADO</span>
                                    <select name="status_id" class="form-control">
                                        <option value="">SELECCIONAR</option>
                                        <?php foreach ($statuses as $p) : ?>
                                            <option value="<?php echo $p['id']; ?>" <?php if (isset($_GET["status_id"]) && $_GET["status_id"] == $p['id']) { echo "selected"; } ?>><?php echo $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon">TIPO</span>
                                    <select name="kind_id" class="form-control">
                                        <option value="">SELECCIONAR</option>
                                        <?php foreach ($kinds as $p) : ?>
                                            <option value="<?php echo $p['id']; ?>" <?php if (isset($_GET["kind_id"]) && $_GET["kind_id"] == $p['id']) { echo "selected"; } ?>><?php echo $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-primary btn-block">Procesar</button>
                            </div>
                            <div class="col-lg-6">
                                <!-- Este botón manda el mismo form a export_report.php con método GET -->
                                <br>
                                <!-- Input oculto para copiar los datos de los filtros del dataTable y luego pasarlos por GET al php para que realice de forma correcta la exportacion: export_report.php -->
                                <input type="hidden" name="q" id="dtGlobalSearch" value="">
                                <button class="btn btn-success btn-block"
                                        type="submit"
                                        formmethod="get"
                                        formaction="export_report.php">
                                Exportar a Excel
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- end form search -->

                    <?php
                    $sql = "SELECT * FROM ticket WHERE 1=1";

                    if ($user_type == 2) { $sql .= " AND asigned_id = $user_id"; }
                    elseif ($user_type == 0) { $sql .= " AND user_id = $user_id"; }

                    if (!empty($_GET["status_id"]))   $sql .= " AND status_id = " . (int)$_GET["status_id"];
                    if (!empty($_GET["kind_id"]))     $sql .= " AND kind_id = " . (int)$_GET["kind_id"];
                    if (!empty($_GET["project_id"]))  $sql .= " AND project_id = " . (int)$_GET["project_id"];
                    if (!empty($_GET["priority_id"])) $sql .= " AND priority_id = " . (int)$_GET["priority_id"];

                    if (!empty($_GET["start_at"]) && !empty($_GET["finish_at"])) {
                    $start = $_GET["start_at"] . " 00:00:00";
                    $end   = $_GET["finish_at"] . " 23:59:59";
                    $sql  .= " AND (created_at >= '$start' AND created_at <= '$end')";
                    }

                    $sql .= " ORDER BY id asc";

                    $users = mysqli_query($con, $sql);


                    if (mysqli_num_rows($users) > 0) {
                        $_SESSION["report_data"] = $users;
                    ?>
                        <div class="x_content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tablaReportes">
                                    <thead>
                                        <th>Folio</th>
                                        <th>Agente</th>
                                        <th>Proyecto</th>
                                        <th>Tipo</th>
                                        <th>Categoria</th>
                                        <th>Prioridad</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Ultima Actualizacion</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($users as $user) {
                                            $project_id = $user['project_id'];
                                            $priority_id = $user['priority_id'];
                                            $kind_id = $user['kind_id'];
                                            $category_id = $user['category_id'];
                                            $status_id = $user['status_id'];

                                            $status = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM status WHERE id=$status_id"));
                                            $category = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM category WHERE id=$category_id"));
                                            $kind = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM kind WHERE id=$kind_id"));
                                            $project = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM project WHERE id=$project_id"));
                                            $priority = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM priority WHERE id=$priority_id"));

                                            ?>
                                            <tr>
                                                <td><?php echo (int)$user['id']; ?></td> <!-- FOLIO -->
                                                 <?php
                                                    // Obtener nombre del agente asignado
                                                    $agent_name = 'No asignado';
                                                    if (!empty($user['asigned_id'])) {
                                                    $agent_id = (int)$user['asigned_id'];
                                                    $agentRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT name FROM user WHERE id = $agent_id"));
                                                    if ($agentRow) { $agent_name = $agentRow['name']; }
                                                    }
                                                ?>
                                                <td><?php echo htmlspecialchars($agent_name); ?></td> <!-- AGENTE (nuevo) -->
                                                <td><?php echo $project['name'] ?></td>
                                                <td><?php echo $kind['name'] ?></td>
                                                <td><?php echo $category['name']; ?></td>
                                                <td><?php echo $priority['name']; ?></td>
                                                <td><?php echo $status['name']; ?></td>
                                                <td><?php echo $user['created_at']; ?></td>
                                                <td><?php echo $user['updated_at']; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    } else {
                        echo "<p class='alert alert-danger'>No hay tickets</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div><!-- /page content -->

<?php include "footer.php" ?>

<!-- Estilos, utilidades y configuraciones para tabla -->
<script>
$(function(){
  if ($.fn.DataTable) {
    $('#tablaReportes').DataTable({
    order: [[0, 'desc']],          // Folio ascendente
    pageLength: 10,               // 10 filas por página
    lengthMenu: [[10,25,50,-1], [10,25,50,'Todos']],
    stateSave: true,              // recuerda el estado (orden, página, etc.)
    stateLoadParams: function (settings, data) {
        data.length = 10; // fuerza 10 aunque exista estado guardado
    },
    language: {
        decimal: ",",
        thousands: ".",
        processing: "Procesando...",
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ registros",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        loadingRecords: "Cargando...",
        zeroRecords: "No se encontraron resultados",
        emptyTable: "No hay datos disponibles en la tabla",
        paginate: {
        first: "Primero",
        previous: "Anterior",
        next: "Siguiente",
        last: "Último"
        }
    }
    });
  }
});

$(function(){
  var t = $('#tablaReportes').DataTable();
  // Cada vez que cambia la búsqueda de DataTables, guardamos el texto en el hidden:
  t.on('search.dt', function(){
    $('#dtGlobalSearch').val(t.search());
  });
  // Al cargar, por si ya había algo buscado (stateSave)
  $('#dtGlobalSearch').val(t.search());
});
</script>
