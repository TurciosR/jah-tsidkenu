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
          <h3 class="text-navy"><b><i class="fa fa-plus-circle"></i> Agregar Cliente </b></h3>
        </div>
        <div class="ibox-content">
          <form name="formulario" id="formulario" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
              <div class="form-group col-lg-9">
                <label>Nombre</label>
                <input type="text" placeholder="" class="form-control upper"  id="nombre" name="nombre" value="">
              </div>
              <div class="form-group col-lg-3" hidden>
                <label>Fecha de Nacimiento</label>
                <input type="text" placeholder="" class="form-control datepicker"  id="edad" name="edad" value="<?= date("d-m-Y"); ?>">
              </div>
              <div class="form-group col-lg-3">
                <label>Edad</label>
                <input type="text" placeholder="" class="form-control numeric"  id="vistaEdad" name="vistaEdad" value="">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-6">
                <label>Sexo</label>
                <select class="form-control select" name="sexo" id="sexo">
                  <option value="">Seleccione</option>
                  <option value="MASCULINO">MASCULINO</option>
                  <option value="FEMENINO">FEMENINO</option>

                </select>
              </div>
              <div class="form-group col-lg-6">
                <label>DUI</label>
                <input type="text" placeholder="" class="form-control dui"  id="dui" name="dui" value="">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-6">
                <label>NIT</label>
                <input type="text" placeholder="" class="form-control nit"  id="nit" name="nit" value="">

              </div>
              <div class="form-group col-lg-6">
                <label>NRC</label>
                <input type="text" placeholder="" class="form-control"  id="nrc" name="nrc" value="">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-6">
                <label>Dirección</label>
                <input type="text" placeholder="" class="form-control upper"  id="direccion" name="direccion" value="">
              </div>
              <div class="form-group col-lg-6">
                <label>Departamento</label>
                <select class="form-control select" name="departamento" id="departamento">
                  <option value="">Seleccione</option>
                  <?php foreach ($departamento as $rows1): ?>
                    <option value="<?= $rows1->id_departamento; ?>"><?= $rows1->nombre_departamento;?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-lg-6">
                <label>Municipio</label>
                <select class="form-control select" name="municipio" id="municipio">
                  <option value="">Seleccione</option>

                </select>
              </div>
              <div class="form-group col-lg-6">
                <label>Teléfono</label>
                <input type="text" placeholder="" class="form-control tel"  id="telefono" name="telefono" value="">

              </div>
            </div>
            <div class="row">
              <div class="form-actions col-lg-12">
                <button type="submit" id="submit1" class="btn btn-primary m-t-n-xs pull-right"><i
                  class="fa fa-save"></i> Guardar
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
<script src="<?= base_url(); ?>assets/js/funciones/funciones_cliente.js"></script>
