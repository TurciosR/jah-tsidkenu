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
          <h3 class="text-navy"><b><i class="fa fa-user"></i> Nuevo Examen </b></h3>
        </div>
        <div class="ibox-content">
          <div class="panel panel-primary">
            <div class="panel-heading">Datos del Cliente</div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-lg-6">
                  <label>Nombre de Cliente</label>
                  <input type="text" id="nombre" name="nombre"  value="<?php echo $nombre; ?>" style="width:100% !important" class=" form-control " style="border-radius:1px" readonly>
                  <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>">
                </div>
                <div class="form-group col-lg-2">
                  <label>Edad</label>
                  <input type="text" placeholder="" class="form-control"  id="edad" name="edad" value="<?php echo $edad; ?>">
                </div>
                <div class="form-group col-lg-2 ">
                  <label>Sexo</label>
                  <select class="form-control select" name="sexo" id="sexo" style="width:100%;" readonly>
                    <?php if ($sexo=="MASCULINO") {?><option value="MASCULINO" >MASCULINO</option><?php } ?>
                    <?php if ($sexo=="FEMENINO") {?><option value="FEMENINO" >FEMENINO</option><?php } ?>
                  </select>
                </div>
                  <div class="form-group col-lg-2 ">
                  <label>Sucursal</label>
                  <select class="form-control select" name="sucursal" id="sucursal" style="width:100%;" readonly>
                    <option value="1" >Jade Oriental</option>
                    <option value="2" >Punto Óptico Matriz</option>
                    <option value="3" >Punto Óptico Sucursal</option>
                    <option value="4" >Home</option>
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
                    <input type="text" placeholder="" id="esfd" name="esfd"  class="form-control decimalp tam" value="" required >
                  </div>
                </div>
                <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                    <input type="text" placeholder="" class="form-control " value="CIL" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                  </div>
                </div>
                <div  class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                    <input type="text" placeholder="" id="cild" name="cild" class="form-control decimalp tam" value="" required>
                  </div>
                </div>
                <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                    <input type="text" placeholder="" class="form-control" value="EJE" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                  </div>
                </div>
                <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                    <input type="text" placeholder="" id="ejed" name="ejed" class="form-control numeric tam" value="" required>
                  </div>
                </div>
                <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                    <input type="text" placeholder="" class="form-control" value="ADI" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                  </div>
                </div>
                <div class="camb" style="width: 10%; padding-right: 0 !important; padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                    <input type="text" placeholder="" id="adid" name="adid " class="form-control decimalp tam" value="" required>
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
                    <input type="text" placeholder=""  id="esfi" name="esfi" class="form-control decimalp tam" value="" required >
                  </div>
                </div>
                <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                    <input type="text" placeholder="" class="form-control" value="CIL" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                  </div>
                </div>
                <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                    <input type="text" placeholder="" id="cili" name="cili" class="form-control decimalp tam" value="" required>
                  </div>
                </div>
                <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                    <input type="text" placeholder="" class="form-control" value="EJE" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                  </div>
                </div>
                <div class="camb" style="width: 10%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                    <input type="text" placeholder="" id="ejei" name="ejei" class="form-control numeric tam" value="" required>
                  </div>
                </div>
                <div class="camb" style="width: 9%; padding-right: 0 !important;  padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="background-color: #1c84c6;">
                    <input type="text" placeholder="" class="form-control" value="ADI" readonly style="background-color: #1c84c6; color:white;border: 1px solid #1c84c6;">
                  </div>
                </div>
                <div class="camb" style="width: 10%; padding-right: 0 !important; padding-left: 0 !important; display: inline-block;">
                  <div class="form-group has-info single-line" style="padding: 6px 3px !important;">
                    <input type="text" placeholder="" id="adii" name="adii" class="form-control decimalp tam" value="" required>
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
                  <input type="text" id="di" name="di" class=" form-control decimal " >
                </div>
                <div class="form-group col-lg-6">
                  <label>A.D</label>
                  <input type="text" id="ad" name="ad" class=" form-control decimal">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-lg-6">
                  <label>Material</label>
                  <input type="text" id="color_lente" name="color_lente" class=" form-control ">
                </div>
                <div class="form-group col-lg-6">
                  <label>Tipo de lente</label>
                  <input type="text" id="bif" name="bif"  class=" form-control ">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-lg-6">
                  <label>Aro</label>
                  <input type="text" id="aro" name="aro" class=" form-control">
                </div>
                <div class="form-group col-lg-6">
                  <label>Color Aro</label>
                  <input type="text" id="color_aro" name="color_aro" class=" form-control " >
                </div>
              </div>
              <div class="row">
                <div class="form-group col-lg-12">
                  <label>Tamaño</label>
                  <input type="text" id="tamanio" name="tamanio"  class=" form-control " >
                </div>
              </div>
              <div class="row">
                <div class="form-group col-lg-12">
                  <label>Observaciones</label>
                  <input type="text" id="observaciones" name="observaciones"  class=" form-control" >
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-actions col-lg-12">
              <button type="hidden" id="process" value="insert">
                <input type="hidden" id="url" value="<?= base_url(); ?>">
                <button type="button" id="guardar_examen" class="btn btn-primary m-t-n-xs pull-right"><i class="fa fa-save"> </i> Guardar </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= base_url(); ?>assets/js/funciones/funciones_examen.js"></script>
