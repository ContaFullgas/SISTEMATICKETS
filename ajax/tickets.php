<?php

    session_start();  // Esto es obligatorio para acceder a $_SESSION

    include "../config/config.php";//Contiene funcion que conecta a la base de datos
    
    //arreglo de sesion que contiene los datos del usuario que inicia sesion, se extrae el id del usuario
    $userId = $_SESSION['user_id'];
    //se extraen los datos del usuario con una consulta por medio del id y se asignan a la variable MyUser
    $MyUser=mysqli_query($con, "select * from user where id = $userId");
    //Arreglo que almacena los datos del usuario que inicia sesion por medio de una consulta de tipo fetch
    $arregloUsuario = [];
    $arregloUsuario =mysqli_fetch_array($MyUser);

    $action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if (isset($_GET['id'])){
        $id_del=intval($_GET['id']);
        $query=mysqli_query($con, "SELECT * from ticket where id='".$id_del."'");
        $count=mysqli_num_rows($query);

            if ($delete1=mysqli_query($con,"DELETE FROM ticket WHERE id='".$id_del."'")){
?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Aviso!</strong> Datos eliminados exitosamente.
            </div>
        <?php 
            }else {
        ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
                </div>
    <?php
            } //end else
        } //end if
    ?>

<?php
    if($action == 'ajax'){
        // escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
         $aColumns = array('title');//Columnas de busqueda

        //Código antiguo
        //  $sTable = "ticket";
        //  $sWhere = "";
        // if ( $_GET['q'] != "" )
        // {
        //     $sWhere = "WHERE (";
        //     for ( $i=0 ; $i<count($aColumns) ; $i++ )
        //     {
        //         $sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
        //     }
        //     $sWhere = substr_replace( $sWhere, "", -3 );
        //     $sWhere .= ')';
        // }
        // $sWhere.=" order by created_at desc";


        //Nuevo código para filtrar los tickets por nivel, usuario, agente o administrador
        $sTable = "ticket";
        $sWhere = "WHERE 1=1"; // Inicializamos la cláusula WHERE

        // Filtrar según el tipo de usuario
        if ($arregloUsuario['tipousuario'] == 2) {
            // Si es agente (2), solo ve los tickets asignados a él
            $sWhere .= " AND asigned_id = " . intval($userId);
        } elseif ($arregloUsuario['tipousuario'] == 0) {
            // Si es usuario (0), solo ve los tickets que él creó
            $sWhere .= " AND user_id = " . intval($userId);
        }

        //si el usuario es administrador (1) no hay ningun filtro, puede ver todos

        // Agregar búsqueda si se está usando el parámetro 'q'
        if ($_GET['q'] != "") {
            $sWhere .= " AND (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        $sWhere .= " ORDER BY created_at DESC";

        include 'pagination.php'; //include pagination file
        //pagination variables
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 10; //how much records you want to show
        $adjacents  = 4; //gap between pages after number of adjacents
        $offset = ($page - 1) * $per_page;
        //Count the total number of row in your table*/
        $count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
        $row= mysqli_fetch_array($count_query);
        $numrows = $row['numrows'];
        $total_pages = ceil($numrows/$per_page);
        $reload = './expences.php';
        //main query to fetch the data
        $sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
        $query = mysqli_query($con, $sql);
        //loop through fetched data
        if ($numrows>0){
            
            ?>
            <table class="table table-striped jambo_table bulk_action">
                <thead>
                    <tr class="headings">
                        <th class="column-title">Asunto </th>
                        <th class="column-title">Proyecto </th>
                        <th class="column-title">Prioridad </th>
                        <th class="column-title">Estado </th>
                        <th class="column-title">Agente asignado </th>
                        <th>Fecha</th>
                        <th class="column-title">Archivo</th> <!-- Nueva columna para descargar el archivo -->
                        <th class="column-title no-link last"><span class="nobr"></span></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                        while ($r=mysqli_fetch_array($query)) {
                            $id=$r['id'];
                            $created_at=date('d/m/Y', strtotime($r['created_at']));
                            $description=$r['description'];
                            $title=$r['title'];
                            $project_id=$r['project_id'];
                            $priority_id=$r['priority_id'];
                            $status_id=$r['status_id'];
                            $kind_id=$r['kind_id'];
                            $category_id=$r['category_id'];
                            //para poner agente
                            $user=$r['asigned_id'];
                            

                            $sql = mysqli_query($con, "select * from project where id=$project_id");
                            if($c=mysqli_fetch_array($sql)) {
                                $name_project=$c['name'];
                            }

                            $sql = mysqli_query($con, "select * from priority where id=$priority_id");
                            if($c=mysqli_fetch_array($sql)) {
                                $name_priority=$c['name'];
                            }

                            $sql = mysqli_query($con, "select * from status where id=$status_id");
                            if($c=mysqli_fetch_array($sql)) {
                                $name_status=$c['name'];
                            }

                            //Para obtener el nombre del servicio almacenado en categorias
                            $sql = mysqli_query($con, "select * from category where id=$category_id");
                            if($c=mysqli_fetch_array($sql)) {
                                $name_category=$c['name'];
                            }

                            // Obtener la ruta del archivo desde la tabla 'rutadearchivos' y poner un boton para descargarlos
                            //se agrego un archivo descargas.php para forzar la descarga del archivo
                            $archivo_query = mysqli_query($con, "SELECT rutadearchivos FROM rutadearchivos WHERE ticket_id=$id");
                            if ($archivo_data = mysqli_fetch_array($archivo_query)) {
                                $archivo_url = "descargar.php?file=" . urlencode($archivo_data['rutadearchivos']);
                                $archivo_link = "<a href='$archivo_url' class='btn btn-primary btn-sm'>Descargar archivo</a>";
                            } else {
                                $archivo_link = "<span class='text-muted'>Sin archivos adjuntos</span>";
                            }

                            
                            //Para agregar un agente
                            //Hace la consulta de acuerdo al ID que tenga en la columna asigned_id, si no encuentra nada muestra no asignado
                            $sql = mysqli_query($con, "SELECT IFNULL((SELECT name FROM user WHERE id=$user), 'NO ASIGNADO') AS name_user");
                            // Verificar si la consulta fue exitosa
                            if ($sql) {
                                $c = mysqli_fetch_array($sql);
                                $name_user = $c['name_user'];
                            } else {
                                $name_user = "NO ASIGNADO";
                            }

                ?>
                    <input type="hidden" value="<?php echo $id;?>" id="id<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $title;?>" id="title<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $description;?>" id="description<?php echo $id;?>">

                    <!-- me obtiene los datos -->
                 <input type="hidden" value="<?php echo $kind_id;?>" id="kind_id<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $project_id;?>" id="project_id<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $category_id;?>" id="category_id<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $priority_id;?>" id="priority_id<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $status_id;?>" id="status_id<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $user;?>" id="asigned_id<?php echo $id;?>">
                    


                    <tr class="even pointer">
                        <td><?php echo $name_category;?></td>
                        <td><?php echo $name_project; ?></td>
                        <td><?php echo $name_priority; ?></td>
                        <td><?php echo $name_status;?></td>
                        <td><?php echo $name_user;?></td>
                        <td><?php echo $created_at;?></td>
                        <td><?php echo $archivo_link; ?></td> <!-- Enlace de descarga de archivos-->
                        <td ><span class="pull-right">

                        <!-- Aqui se agregaron validaciones con clases para evitar que un usuario pueda hacer modificaciones a los tickets -->
                        <a href="#" class="<?php echo ($arregloUsuario['tipousuario'] == 1 || $arregloUsuario['tipousuario'] == 2)?'visible':'hidden'; ?> > btn btn-default" title='Editar producto' onclick="obtener_datos('<?php echo $id;?>');" data-toggle="modal" data-target=".bs-example-modal-lg-udp"><i class="glyphicon glyphicon-edit"></i></a> 
                        <a href="#" class="<?php echo ($arregloUsuario['tipousuario'] == 1 || $arregloUsuario['tipousuario'] == 2)?'visible':'hidden'; ?> > btn btn-default" title='Borrar producto' onclick="eliminar('<?php echo $id; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span></td>
                    </tr>
                <?php
                    } //en while
                ?>
                <tr>
                    <td colspan=8><span class="pull-right">
                        <?php echo paginate($reload, $page, $total_pages, $adjacents);?>
                    </span></td>
                </tr>
              </table>
            </div>
            <?php
        }else{
           ?> 
            <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Aviso!</strong> No hay datos para mostrar!
            </div>
        <?php    
        }
    }
?>