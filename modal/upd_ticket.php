<?php
    $projects =mysqli_query($con, "select * from project");
    $priorities =mysqli_query($con, "select * from priority");
    $statuses =mysqli_query($con, "select * from status");
    $kinds =mysqli_query($con, "select * from kind");
    $categories =mysqli_query($con, "select * from category");
    $users = mysqli_query($con, "SELECT * FROM user WHERE tipousuario IN (2)");
?>
    <!-- Modal -->
    <div class="modal fade bs-example-modal-lg-udp" id="modalUpdTicket" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"> Editar Ticket</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-label-left input_mask" method="post" id="upd" name="upd">
                        <div id="result2"></div>

                        <input type="hidden" name="mod_id" id="mod_id">

                        <div class="form-group" style="display:none;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tipo
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="kind_id" required id="mod_kind_id">
                                      <?php foreach($kinds as $p):?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                      <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display:none;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Titulo<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" name="title" class="form-control" placeholder="Titulo" id="mod_title" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <textarea  name="description" id="mod_description" class="form-control col-md-7 col-xs-12" required
                              
                              <?php if($arregloUsuario['tipousuario'] == 0 || $arregloUsuario['tipousuario'] == 2): ?>
                                    style="pointer-events: none;" onclick="return false;" onkeydown="return false;"
                                <?php endif; ?>>
                                <!-- Funcion arriba para bloquear apartados en perfiles de usuario, solo se muestra a administrador y agente -->
                                   
                              </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Proyecto
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="project_id" required id="mod_project_id"
                                
                                <?php if($arregloUsuario['tipousuario'] == 0 || $arregloUsuario['tipousuario'] == 2): ?>
                                    style="pointer-events: none;" onclick="return false;" onkeydown="return false;"
                                <?php endif; ?>>
                                  <!-- Funcion arriba para bloquear apartados en perfiles de usuario, solo se muestra a administrador y agente -->

                                    <option selected="" value="">-- Selecciona --</option>
                                      <?php foreach($projects as $p):?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                      <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Categoria
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="category_id" required id="mod_category_id"

                                <?php if($arregloUsuario['tipousuario'] == 0 || $arregloUsuario['tipousuario'] == 2): ?>
                                    style="pointer-events: none;" onclick="return false;" onkeydown="return false;"
                                <?php endif; ?>>
                                  <!-- Funcion arriba para bloquear apartados en perfiles de usuario, solo se muestra a administrador y agente -->
                                   
                                    <option selected="" value="">-- Selecciona --</option>
                                      <?php foreach($categories as $p):?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                      <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Prioridad
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="priority_id" required id="mod_priority_id"

                                <?php if($arregloUsuario['tipousuario'] == 0 || $arregloUsuario['tipousuario'] == 2): ?>
                                    style="pointer-events: none;" onclick="return false;" onkeydown="return false;"
                                <?php endif; ?>>
                                  <!-- Funcion arriba para bloquear apartados en perfiles de usuario, solo se muestra a administrador y agente -->
                                   
                                    <option selected="" value="">-- Selecciona --</option>
                                  <?php foreach($priorities as $p):?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Estado
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select  class="form-control" name="status_id" required id="mod_status_id"
                                
                                    <?php if($arregloUsuario['tipousuario'] == 0): ?>
                                        style="pointer-events: none;" onclick="return false;" onkeydown="return false;"
                                    <?php endif; ?>>

                                    <option selected="" value="">-- Selecciona --</option>
                                  <?php foreach($statuses as $p):?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Nuevo contenedor para actualizar el agente asignado -->
                        <div class="<?php echo ($arregloUsuario['tipousuario'] == 1)?'visible':'hidden'; ?> > form-group">
                          <!-- Se agrega el cambio de arriba para que los agentes no les aparezca el apartado de cambiar agente y no hagan trampa-->
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Agente asignado
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select  class="form-control" name="asigned_id" required id="mod_asigned_id">
                                    <option selected="" value="">-- Selecciona --</option>
                                  <?php foreach($users as $p):?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                              <button id="upd_data" type="submit" class="btn btn-success" value="<?php echo $name; ?>" <?php echo ($arregloUsuario['tipousuario'] == 0) ? 'disabled' : ''; ?> >Guardar</button>
                            </div>
                        </div>
                    </form>      
                    
                    <h4><b>Comentarios</b></h4> <!-- Aquí el encabezado -->
                    <hr style='margin: 2px 0;'>
                    <div id="comments_section">
                      <!-- Aquí se cargarán los comentarios vía AJAX -->
                    </div>

                    <div  id="new_comment_section" class="<?php echo ($arregloUsuario['tipousuario'] == 0 || $arregloUsuario['tipousuario'] == 2)?'visible':'hidden'; ?> > form-group">
                      <!-- Se agrega el cambio de arriba para que solo los agentes y usuarios puedan realizar comentarios en el ticket-->
                        <label for="comment">Nuevo Comentario</label>
                        <textarea id="comment_text" class="form-control" rows="3" placeholder="Escribe tu comentario..."></textarea>
                        <br>
                        <button type="button" class="btn btn-primary" onclick="addComment()">Agregar Comentario</button>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div> <!-- /Modal -->
    

    