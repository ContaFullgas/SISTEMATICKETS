<?php
    $projects =mysqli_query($con, "select * from project");
    $priorities =mysqli_query($con, "select * from priority");
    $statuses =mysqli_query($con, "select * from status");
    $kinds =mysqli_query($con, "select * from kind");
    $categories =mysqli_query($con, "select * from category");

?>

    <div> <!-- Modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg-add"><i class="fa fa-plus-circle"></i> Agregar Ticket</button>
    </div>
    <div class="modal fade bs-example-modal-lg-add" tabindex="-1" role="dialog" aria-hidden="true" id='modalNuevoTicket'>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Ticket</h4>
                </div>
                <div class="modal-body">
                    
                    <form class="form-horizontal form-label-left input_mask" method="post" id="add" name="add">
                        <div id="result"></div>
                        <!-- elemento oculto porque no es relevante, ya que el tipo siempre sera ticket -->
                        <div class="form-group" style="display: none;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tipo:
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="kind_id" style="pointer-events: none;">
                                      <?php foreach($kinds as $p):?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                      <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- elemento oculto porque no es relevante, ya que no se utliza esta variable -->
                        <div class="form-group" style="display: none;">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Titulo:<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <!-- Al input le pongo un valor de un espacio, porque si se queda vacio el programa marca error y no deja ingresar el ticket -->
                              <input type="text" name="title" class="form-control" placeholder="Titulo" value=" ">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Servicio:
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="category_id" required>
                                    <option selected="" value="">-- Selecciona --</option>
                                      <?php foreach($categories as $p):?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                      <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- area o departamento que tiene la incidencia -->
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Area:
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="project_id" required>
                                    <option selected="" value="">-- Selecciona --</option>
                                      <?php foreach($projects as $p):?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                      <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Incidencia detallada:<span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <textarea name="description" class="form-control col-md-7 col-xs-12"  placeholder="Descripción" required></textarea>
                            </div>
                        </div>
                        
                        <!-- Clase para ocultar apartado a usuarios -->
                        <div class="<?php echo ($arregloUsuario['tipousuario'] == 1)?'visible':'hidden'; ?> form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Prioridad:
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="priority_id" >
                                    <option selected="" value="">-- Selecciona --</option>
                                  <?php foreach($priorities as $p):?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- Clase para ocultar apartado a usuarios -->
                        <div class="<?php echo ($arregloUsuario['tipousuario'] == 1)?'visible':'hidden'; ?> form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Estado:
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="status_id" >
                                    <option selected="" value="">-- Selecciona --</option>
                                  <?php foreach($statuses as $p):?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- parte para adjuntar archivos y guardarlos en una ruta en el servidor y su direccion en la base de datos para consultarlo despues -->
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="imagen">Archivo comprimido:</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input id="imagen" name="imagen[]" size="30" type="file" class="form-control" accept=".zip, .rar" style="border: none;" />
                            </div>
                        </div>

                                    <!-- ultimo cambio -->
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                              <button id="save_data" type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </div>    
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div> <!-- /Modal -->