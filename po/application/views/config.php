<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content  animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox" id="main_view">
        <div class="ibox-title">
          <h3 class="text-navy"><b><i class="fa fa-pencil"></i> <?= $titulo; ?></b></h3>
        </div>
        <div class="ibox-content">
          <form name="formulario" id="formulario" novalidate="novalidate">
            <div class="row">
              <div class="form-group col-lg-6"><label for="">Nombre Empresa</label>
                <input name="nombre" id="nombre" class="form-control upper" placeholder="Ingrese el nombre de la empresa" value="<?= $nombre_empresa ?>" type="text">
              </div>
              <div class="form-group col-lg-6"><label for="">Direccion</label>
                <input name="direccion" id="direccion" class="form-control upper" placeholder="Ingrese la direccion de la empresa" value="<?= $direccion_empresa ?>" type="text">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-4"><label for="">Telefono</label>
                <input name="telefono" id="telefono" class="form-control tel" placeholder="Ingrese el telefono de la empresa" value="<?= $telefono_empresa ?>" type="text">
              </div>
              <div class="form-group col-lg-4"><label for="">IVA</label>
                <input name="iva" id="iva" class="form-control numeric" placeholder="Ingrese el porcentaje de iva" value="<?= $iva ?>" type="text">
              </div>
              <div class="form-group col-lg-4"><label for="">Retencion</label>
                <input name="retencion" id="retencion" class="form-control numeric" placeholder="Ingrese porcentaje de retención" value="<?= $retencion ?>" type="text">
              </div>
            </div>
            <div class="row">
              <!-- <div class="form-group col-lg-8"><label for="">Logo Empresa</label>
                <div class="file-loading">
                  <input id="fileinput" name="fileinput" type="file" multiple accept="image/*">
                </div>
              </div>
              <div class="form-group col-lg-4"><label for="">Logo Actual</label>
                <img src="<?=$logo_empresa?>" alt="" class="img img-responsive" width="250px;">
              </div>
            </div>-->
            <div class="row">
              <div class="form-actions col-lg-12">
                <input id="proccess" name="proccess" value="edited" type="hidden"><button type="submit" id="btn_edit" name="btn_edit" class="btn btn-primary m-t-n-xs pull-right"><i class="fa fa-save"></i> Guardar Cambios</button> </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= base_url(); ?>assets/js/funciones/<?= $urljs; ?>"></script>
