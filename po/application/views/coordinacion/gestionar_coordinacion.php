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
          <h3 class="text-navy"><b><i class="fa fa-spinner"></i> Gestionar <?= $unidad ?> </b></h3>
        </div>
        <div class="ibox-content">
          <form name="formulario2" id="formulario2" enctype="multipart/form-data" autocomplete="off">
            <div class="row">

              <div class="form-group col-lg-12">
                <label>Jefe de Coordinacion</label>
                <select class="form-control select" name="jefe_unidad" id="jefe_unidad">
                  <option value="">Seleccione</option>
                  <?php foreach ($jefe_unidad as $rows1): ?>
                    <option value="<?= $rows1->id_colaborador; ?>" <?php if($rows1->id_colaborador==$id_colaborador1){ echo "selected";} ?>><?= $rows1->nombre." ".$rows1->apellido; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>


              </div>
              <div class="row">
                <div class="form-group col-lg-6">
                  <label>Colaboradores</label>
                  <select class="form-control select" name="colaborador" id="colaborador">
                    <option value="">Seleccione</option>
                    <?php foreach ($colaboradores as $rows2): ?>
                    <option value="<?= $rows2->id_colaborador; ?>"><?= $rows2->nombre." ".$rows2->apellido; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>


              </div>

              <div>
                <table class="table table-bordered table-hover" id="tablai">
                  <thead>
                    <tr>
                      <th class="col-lg-1 text-success font-bold sorting_asc">ID</th>
                      <th class="col-lg-10 text-success font-bold sorting_asc">COLABORADOR</th>
                      <th class="col-lg-1 text-success font-bold sorting_asc">ACCI&Oacute;N</th>
                    </tr>
                  </thead>
                  <tbody id="licolaborador">
                    <?php foreach ($colaboradores_unidades as $rows3): ?>
                      <tr >
                        <td class='id_colaborador'><?= $rows3->id_colaborador; ?></td>
                        <td class='nombre_colaborador'><?= $rows3->nombre." ".$rows3->apellido; ?></td>
                        <td class='text-center'><a class='lndelete btn'><i class='fa fa-trash'></i></a></td>
                      </tr>
                    <?php endforeach; ?>

                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="form-actions col-lg-12">
                  <input type="hidden"  id="id_unidad" name="id_unidad" value="<?= $id?>">
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
  <script src="<?= base_url(); ?>assets/js/funciones/funciones_coordinacion.js"></script>
