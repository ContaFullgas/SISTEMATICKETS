<?php

    //arreglo de sesion que contiene los datos del usuario que inicia sesion, se extrae el id del usuario
    $userId = $_SESSION['user_id'];
    //se extraen los datos del usuario con una consulta por medio del id y se asignan a la variable MyUser
    $MyUser=mysqli_query($con, "select * from user where id = $userId");
    //Arreglo que almacena los datos del usuario que inicia sesion por medio de una consulta de tipo fetch
    $arregloUsuario = [];
    $arregloUsuario =mysqli_fetch_array($MyUser);

    // echo "<pre>";  // Formatea la salida para mejorar la visualización
    // print_r($arregloUsuario['tipousuario']);
    // echo "</pre>";
?>
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu"><!-- sidebar menu -->
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li class="<?php if(isset($active1)){echo $active1;}?>">
                        <a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>

                    <li class="<?php if(isset($active2)){echo $active2;}?>">
                        <a href="tickets.php"><i class="fa fa-ticket"></i> Tickets</a>
                    </li>

                    <!-- Clase que usa el arregloUsuario para ocultar las etiquetas de acuerdo al perfil del usuario -->
                    <li class="<?php if (isset($active3)) { echo $active3; } ?> <?php echo ($arregloUsuario['tipousuario'] == 1) ? 'visible' : 'hidden'; ?>">
                        <a href="projects.php"><i class="fa fa-list-alt"></i> Areas</a>
                    </li>

                    <!-- Clase que usa el arregloUsuario para ocultar las etiquetas de acuerdo al perfil del usuario -->
                    <li class="<?php if(isset($active4)){echo $active4;}?> <?php echo ($arregloUsuario['tipousuario'] == 1)?'visible':'hidden'; ?>">
                        <a href="categories.php"><i class="fa fa-align-left"></i> Servicios</a>
                    </li>
                    <!-- Clase que usa el arregloUsuario para ocultar las etiquetas de acuerdo al perfil del usuario -->
                    <li class="<?php if(isset($active5)){echo $active5;}?> <?php echo ($arregloUsuario['tipousuario'] == (1 || 2))?'visible':'hidden'; ?>">
                        <a href="reports.php"><i class="fa fa-area-chart"></i> Reportes</a>
                    </li>
                    <!-- Clase que usa el arregloUsuario para ocultar las etiquetas de acuerdo al perfil del usuario -->
                    <li class="<?php if(isset($active5)){echo $active5;}?> <?php echo ($arregloUsuario['tipousuario'] == 0)?'visible':'hidden'; ?>">
                        <a href="reports.php"><i class="fa fa-area-chart"></i> Historial</a>
                    </li>
                    <!-- Clase que usa el arregloUsuario para ocultar las etiquetas de acuerdo al perfil del usuario -->
                    <li class="<?php if(isset($active6)){echo $active6;}?> <?php echo ($arregloUsuario['tipousuario'] == 1)?'visible':'hidden'; ?>">
                        <a href="users.php"><i class="fa fa-users"></i> Usuarios</a>
                    </li>

                    <!-- Clase que usa el arregloUsuario para ocultar las etiquetas de acuerdo al perfil del usuario -->
                    <li class="<?php if(isset($active6)){echo $active6;}?> <?php echo ($arregloUsuario['tipousuario'] == 1)?'visible':'hidden'; ?>">
                        <a href="evaluaciones.php"><i class="fa fa-clipboard"></i> Evaluaciones</a>
                    </li>

                    <li class="<?php if(isset($active8)){echo $active8;}?>">
                        <a href="about.php"><i class="fa fa-child"></i> Sobre Mi</a>
                    </li>

                </ul>
            </div>
        </div><!-- /sidebar menu -->
    </div>
</div> 
     
    <div class="top_nav"><!-- top navigation -->
        <div class="nav_menu">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li class="">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="images/profiles/<?php echo $profile_pic;?>" alt=""><?php echo $name;?>
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                            <li><a href="dashboard.php"><i class="fa fa-user"></i> Mi cuenta</a></li>
                            <li><a href="action/logout.php"><i class="fa fa-sign-out pull-right"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div><!-- /top navigation -->    