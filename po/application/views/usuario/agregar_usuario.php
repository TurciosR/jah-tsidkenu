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
          <h3 class="text-navy"><b><i class="fa fa-user-plus"></i> Agregar Usuario </b></h3>
        </div>
        <div class="ibox-content">
          <form name="formulario" id="formulario" autocomplete="off">
            <div class="row">
              <div class="form-group col-lg-3">
                <label>Nombre</label>
                <input type="text" placeholder="" class="form-control"  id="nombre" name="nombre" value="">
              </div>
              <div class="form-group col-lg-3">
                <label>Usuario</label>
                <input type="text" placeholder="" class="form-control"  id="usuario" name="usuario" value="">
              </div>
              <div class="form-group col-lg-3">
                <label>Clave</label>
                <input type="password" placeholder="" class="form-control"  id="clave" name="clave" value="">
              </div>
              <div class="form-group col-lg-3">
                <label>Tipo</label>
                <select class="form-control select" name="tipo2" id="tipo2" style="width:100%;">
                  <option value="">Selecione</option>
                  <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                  <option value="OPTOMETRISTA">OPTOMETRISTA</option>
                  <option value="SECRETARIA">SECRETARIA</option>
                </select>
              </div>
              </div>
              <div class="row">
                <div class="form-actions col-lg-12">
                  <input type="hidden" id="process" value="insert">
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
  <script src="<?= base_url(); ?>assets/js/funciones/funciones_usuarios.js"></script>
