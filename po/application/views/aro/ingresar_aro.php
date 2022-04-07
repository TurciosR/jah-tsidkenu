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
          <h3 class="text-navy"><b><i class="fa fa-circle"></i> Ingresar Aro </b></h3>
        </div>
        <div class="ibox-content">
          <form name="formulario" id="formulario" autocomplete="off">
            <div class="row">
              <div class="form-group col-lg-3">
                <label>Codigo</label>
                <input type="text" placeholder="" class="form-control" readonly  id="codigo" name="codigo" value="<?=$codigo;?>">
              </div>
              <div class="form-group col-lg-3">
                <label>Marca</label>
                <input type="text" placeholder="" class="form-control" readonly  id="marca" name="marca" value="<?=$marca;?>">
              </div>
              <div class="form-group col-lg-3">
                <label>Casa</label>
                <input type="text" placeholder="" class="form-control" readonly  id="casa" name="casa" value="<?=$casa;?>">
              </div>
              <div class="form-group col-lg-3">
                <label>Numero de Aros</label>
                <input type="text" placeholder="" class="form-control"  id="existencia" name="existencia" value="1">
              </div>
              </div>
              <div class="row">
                <div class="form-actions col-lg-12">
                  <input type="hidden" id="process" value="ingresar">
                  <input type="hidden" id="id_aro" value="<?=$id;?>">
                  <input type="hidden" id="url" value="<?= base_url(); ?>">
                  <button type="button" id="submit" class="btn btn-primary m-t-n-xs pull-right"><i class="fa fa-save"></i> Guardar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= base_url(); ?>assets/js/funciones/funciones_aros.js"></script>
