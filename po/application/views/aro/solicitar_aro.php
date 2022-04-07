<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-12">
  </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox" id="main_view">
        <div class="ibox-title">
          <h3 class="text-navy"><b><i class="fa fa-circle"></i> Detalle Aro </b></h3>
        </div>
        <div class="ibox-content">
          <form name="formulario" id="formulario" autocomplete="off">
            <div class="row">
                <div class="form-group col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label>Codigo</label>
                                    <input type="text" placeholder="" class="form-control"  id="codigo" name="codigo" value="<?=$codigo;?>" readonly>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>Marca</label>
                                    <input type="text" placeholder="" class="form-control"  id="marca" name="marca" value="<?=$marca;?>" readonly>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>Casa</label>
                                    <input type="text" placeholder="" class="form-control"  id="casa" name="casa" value="<?=$casa;?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-6">
                    <?php
                    if (count($data)>0) {
                        $btn="";
                        foreach ($data as $row) {
                            if ($id_sucursal!=$row->sucursal){
                                $btn='<button type="button"  id_sur="'.$row->sucursal.'" id="solicitar" name="solicitar" class="btn btn-default m-t-n-xs pull-right" style="background: #ffffff26;">Solicitar</button>';
                            }else{
                                $filename = base_url()."Aros/aro_salida";
                                $btn='<a href="'.$filename."/".trim($codigo).'" id_sur="'.$row->sucursal.'" id="salida" name="salida" class="btn btn-danger m-t-n-xs pull-right" role="button" data-toggle="modal" data-target="#viewModal" data-refresh="true">Salida</a>';
                            }
                            $nombre = $row->nombre;
                            $existencia = $row->existencia;
                            if ($existencia>0) {
                                echo '<div class="panel panel-primary">';
                                echo '<div class="panel-heading" style="padding: 17px 19px;">';
                                echo '<span class="label pull-left" style="background: #ffffff; color:#1c84c6; padding:0px 8px; margin-top:-7px;margin-left: -7px;margin-right: 11px;">';
                                echo '<h3>' . $existencia . '</h3>';
                                echo '</span>' . $nombre . $btn . '</div>';
                                echo '</div>';
                            }

                        }
                    }
                    ?>
                </div>

              </div>
              <div class="row">
                <div class="form-actions col-lg-12">
                  <input type="hidden" id="process" value="edited">
                  <input type="hidden" id="id_aro" value="<?=$id;?>">
                  <input type="hidden" id="url" value="<?= base_url(); ?>">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class='modal fade' id='viewModal' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-md'>
            <div class='modal-content modal-md'>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
  </div>

  <script src="<?= base_url(); ?>assets/js/funciones/funciones_aros.js"></script>
