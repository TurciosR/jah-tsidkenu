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
          <h3 class="text-navy"><b><i class="fa fa-plus-circle"></i> Nuevo Examen </b></h3>
        </div>
        <div class="ibox-content">
          <form name="formulario" id="formulario" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
              <div class="form-group col-lg-6">
                <label>Nombre</label>
                <input type="text" placeholder="" class="form-control"  id="nombre" name="nombre" value="">
              </div>
              <!--div class="form-group col-lg-6">
                <label>Gerente</label>
                <select class="form-control select" name="coordinador" id="coordinador">
                  <option value="">Seleccione</option>
                  <?php foreach ($coordinador as $rows1): ?>
                    <option value="<?= $rows1->id_colaborador; ?>"><?= $rows1->nombre." ".$rows1->apellido; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>


              </div>
              <div class="row">
                <div class="form-group col-lg-6">
                  <label>Unidad</label>
                  <select class="form-control select" name="unidad" id="unidad">
                    <option value="">Seleccione</option>
                    <?php foreach ($unidades as $rows2): ?>
                    <option value="<?= $rows2->id_unidad; ?>"><?= $rows2->nombre; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div-->


              </div>

              <div>
                <table class="table table-bordered table-hover" id="tablai">
                  <thead>
                    <tr>
                      <th class="col-lg-1 text-success font-bold sorting_asc">ID</th>
                      <th class="col-lg-10 text-success font-bold sorting_asc">UNIDAD</th>
                      <th class="col-lg-1 text-success font-bold sorting_asc">ACCI&Oacute;N</th>
                    </tr>
                  </thead>
                  <tbody id="liunidad">

                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="form-actions col-lg-12">
                  <button type="submit" id="submit1" class="btn btn-primary m-t-n-xs pull-right"><i
                    class="fa fa-save"></i> Guardar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="ibox" style="display: none;" id="divh">
          <div class="row">
            <div class="col-lg-12">
              <div class="ibox float-e-margins">
                <div class="ibox-content text-center">
                  <h2 class="text-danger blink_me">Espere un momento, Procesando Solicitud !!!</h2>
                  <section class="sect">
                    <div id="loader">
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= base_url(); ?>assets/js/funciones/funciones_gerencia.js"></script>
