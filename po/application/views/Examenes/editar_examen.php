<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-12">
  </div>
</div>
<style>
    .tam{
        font-size: 19px;
        font-weight: bold;
    }
    @media (max-width: 768px) {
        .camb {
            display: inline !important;
        }
    }

</style>
<div class="wrapper wrapper-content  animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox" id="main_view">
        <div class="ibox-title">
          <h3 class="text-navy"><b><i class="fa fa-folder"></i> Expediente</b></h3>
        </div>
        <div class="ibox-content">
          <div class="panel panel-info">
            <div class="panel-heading">DATOS DEL CLIENTE</div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-lg-6">
                  <div id="a">
                    <label>Nombre de Cliente</label>
                    <div id="scrollable-dropdown-menu">
                      <input type="text" id="nombre" name="nombre"  value="<?php echo $nombre; ?>" style="width:100% !important" class=" form-control " style="border-radius:1px" readonly>
                      <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>">
                    </div>
                  </div>
                </div>
                <div class="form-group col-lg-3">
                  <label>Edad</label>
                  <input type="text" placeholder="" class="form-control"  id="edad" name="edad" value="<?php echo $edad; ?>">
                </div>
                <div class="form-group col-lg-3 ">
                  <label>Sexo</label>
                  <select class="form-control select" name="sexo" id="sexo" style="width:100%;" readonly>
                    <?php if ($sexo=="MASCULINO") {?><option value="MASCULINO" >MASCULINO</option><?php } ?>
                    <?php if ($sexo=="FEMENINO") {?><option value="FEMENINO" >FEMENINO</option><?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <?php
          foreach ($datos_consulta as $data2) {
            $id_examen = $data2->id;
            $esfd = $data2->esfd;
            $cild = $data2->cild;
            $ejed = $data2->ejed;
            $adid = $data2->adid;
            $esfi = $data2->esfi;
            $cili = $data2->cili;
            $ejei = $data2->ejei;
            $adii = $data2->adii;
            $di = $data2->di;
            $ad = $data2->ad;
            $color_lente = $data2->color_lente;
            $bif = $data2->bif;
            $aro = $data2->aro;
            $tamanio = $data2->tamanio;
            $color_aro = $data2->color_aro;
            $observaciones = $data2->observaciones;
            $fecha = $data2->fecha;
            $nombre_sucursal = $data2->nombre_sucursal;
            $optometrista = $data2->optometrista;
            $id_sur = $data2->id_sur;
            ?>
            <div class="panel panel-info">
              <div class="panel-heading" data-toggle="collapse" href="#consulta<?= $id_examen ?>">
                <h4 class="panel-title">
                  <a>CONSULTA <?=$fecha."  -  ".$nombre_sucursal."  -  ".$optometrista?></a>
                </h4>
              </div>
              <div id="consulta<?= $id_examen ?>" class="panel-collapse collapse">
                <div class="panel-body">
                  <div class="panel panel-primary cofcff">
                    <div class="panel-heading">Sucursal</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <select class="select" style="width:100%" id="suce<?= $id_examen ?>" name="suce<?= $id_examen ?>">
                            <?php foreach ($sucursal as $keys): ?>
                              <option <?php if ($id_sur==$keys->id) { echo " selected ";} ?> value="<?=$keys->id ?>"><?=$keys->nombre ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-primary cofcff">
                    <div class="panel-heading">Datos del Examen</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="camb" style="width: 20%; padding-right: 0 !important; display: inline-block;">
                            <div class="form-group has-info single-line" style="background-color: #120F0F;">
                            <input type="text" placeholder="" class="form-control" value="OJO DERECHO" readonly style="background-color: #120F0F; color:white;border: 1px solid #120F0F;">
                          </div>
                        </div>
                        <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                            <input type="text" placeholder="" class="form-control" value="ESF" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                          </div>
                        </div>
                        <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                            <input type="text" placeholder="" id="esfd<?= $id_examen ?>" name="esfd<?= $id_examen ?>" class="form-control decimalp tam" value="<?php echo $esfd; ?>" >
                          </div>
                        </div>
                        <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                            <input type="text" placeholder="" class="form-control" value="CIL" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                          </div>
                        </div>
                        <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                            <input type="text" placeholder="" id="cild<?= $id_examen ?>" name="cild<?= $id_examen ?>" class="form-control decimalp tam" value="<?php echo $cild; ?>">
                          </div>
                        </div>
                        <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                            <input type="text" placeholder="" class="form-control" value="EJE" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                          </div>
                        </div>
                        <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                            <input type="text" placeholder="" id="ejed<?= $id_examen ?>" name="ejed<?= $id_examen ?>" class="form-control numeric tam" value="<?php echo $ejed; ?>">
                          </div>
                        </div>
                        <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                            <input type="text" placeholder="" class="form-control" value="ADI" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                          </div>
                        </div>
                        <div class="camb" style="width: 10%; padding-right: 0 !important; padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                            <input type="text" placeholder="" id="adid<?= $id_examen ?>" name="adid<?= $id_examen ?>" class="form-control decimalp tam" value="<?php echo $adid; ?>">
                          </div>
                        </div>
                      </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-12">
                        <div class="camb" style="width: 20%; padding-right: 0 !important; display: inline-block;">
                            <div class="form-group has-info single-line" style="background-color: #120F0F;">
                            <input type="text" placeholder="" class="form-control" value="OJO IZQUIERDO" readonly style="background-color: #120F0F; color:white;border: 1px solid #120F0F;">
                          </div>
                        </div>
                        <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                            <input type="text" placeholder="" class="form-control" value="ESF" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                          </div>
                        </div>
                        <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                            <input type="text" placeholder="" id="esfi<?= $id_examen ?>" name="esfi<?= $id_examen ?>" class="form-control decimalp tam" value="<?php echo $esfi; ?>" >
                          </div>
                        </div>
                        <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                            <input type="text" placeholder="" class="form-control" value="CIL" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                          </div>
                        </div>
                        <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                            <input type="text" placeholder="" id="cili<?= $id_examen ?>" name="cili<?= $id_examen ?>" class="form-control decimalp tam" value="<?php echo $cili; ?>">
                          </div>
                        </div>
                        <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                            <input type="text" placeholder="" class="form-control" value="EJE" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                          </div>
                        </div>
                        <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                            <input type="text" placeholder="" id="ejei<?= $id_examen ?>" name="ejei<?= $id_examen ?>" class="form-control numeric tam" value="<?php echo $ejei; ?>">
                          </div>
                        </div>
                        <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                            <input type="text" placeholder="" class="form-control" value="ADI" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                          </div>
                        </div>
                        <div class="camb" style="width: 10%; padding-right: 0 !important; padding-left: 0 !important; display: inline-block;">
                          <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                            <input type="text" placeholder="" id="adii<?= $id_examen ?>" name="adii<?= $id_examen ?>" class="form-control decimalp tam" value="<?php echo $adii; ?>">
                          </div>
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-primary cofcff">
                    <div class="panel-heading">Otros Datos</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="form-group col-lg-6">
                          <label>D.I</label>
                          <input type="text" id="di<?= $id_examen ?>" name="di<?= $id_examen ?>" class=" form-control decimal" value="<?php echo $di; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>A.D</label>
                          <input type="text" id="ad<?= $id_examen ?>" name="ad<?= $id_examen ?>" class=" form-control decimal" value="<?php echo $ad; ?>" >
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-6">
                          <!-- <label>Color Lente</label> -->
                          <label>Material</label>
                          <input type="text" id="color_lente<?= $id_examen ?>" name="color_lente<?= $id_examen ?>" class=" form-control " value="<?php echo $color_lente; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <!-- <label>BIF</label> -->
                          <label>Tipo de lente</label>
                          <input type="text" id="bif<?= $id_examen ?>" name="bif<?= $id_examen ?>"  class=" form-control " value="<?php echo $bif; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-6">
                          <label>Aro</label>
                          <input type="text" id="aro<?= $id_examen ?>" name="aro<?= $id_examen ?>" class=" form-control" value="<?php echo $aro; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Color Aro</label>
                          <input type="text" id="color_aro<?= $id_examen ?>" name="color_aro<?= $id_examen ?>" class=" form-control " value="<?php echo $color_aro; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-12">
                          <label>Tamaño</label>
                          <input type="text" id="tamanio<?= $id_examen ?>" name="tamanio<?= $id_examen ?>"  class=" form-control " value="<?php echo $tamanio; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-12">
                          <label>Observaciones</label>
                          <input type="text" id="observaciones<?= $id_examen ?>" name="observaciones<?= $id_examen ?>"  class=" form-control " value="<?php echo $observaciones; ?>" >
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-actions col-lg-12">
                      <a href='<?=base_url("Examen/imprimir_examen/".MD5($id_examen)); ?>' target="_blank" role='button' class="btn btn-primary m-t-n-xs pull-left" ><i class='fa fa-file-pdf-o' ></i> PDF</a>

                      <button  id_examen="<?=MD5($id_examen); ?>" id_sur="<?=$id_sur;?>" style="margin-left:10px;"   role='button' class="p_exam btn btn-primary m-t-n-xs pull-left" ><i class='fa fa-print' ></i> Imprimir</button>
                      <button  id_examen="<?=MD5($id_examen); ?>" id_sur="<?=$id_sur;?>" style="margin-left:10px;"   role='button' class="p_exam2 btn btn-primary m-t-n-xs pull-left" ><i class='fa fa-print' ></i> Imprimir En Formato</button>

                      <button type="button"  data="<?= $id_examen ?>" class="btn btn-primary m-t-n-xs pull-right editar_examen"> <i class="fa fa-save"></i>Guardar </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?= base_url(); ?>assets/js/funciones/funciones_examen.js?<?=rand(0,999)?>"></script>
