<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content  animated fadeInRight">
  <div class="row" id="row1">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class='ibox-title'>
          <div class='row'>
            <div class='col-lg-2'>
              <a data-toggle='modal' href='Caja/agregar_ingreso_view' data-target='#viewModal' data-refresh='true' class='btn btn-primary m-t-n-xs'><i class='fa fa-plus icon-large'></i> Agregar ingreso</a>
            </div><div class='col-lg-2'>
              <a data-toggle='modal' href='Caja/agregar_egreso_view' data-target='#salidaModal' data-refresh='true' class='btn btn-primary m-t-n-xs'><i class='fa fa-plus icon-large'></i> Agregar Egreso</a>
            </div>
          </div>
        </div>
        <div class="ibox-content">
          <!--load datables estructure html-->
          <header>
            <h3 class="text-navy"><i class="<?=$icono;?>"></i> <?=$nombre_archivo?></h3>
          </header>
          <section>
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover table-checkable datatable" id="editable2">
                <thead class="thead-dark">
                  <tr>
                    <?php foreach ($tabla as $key => $value): ?>
                      <th class='col-md-<?=$value?> text-success font-bold'><?= $key ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!--div class='ibox-content'-->
          </section>

          <!--Show Modal Popups View & Delete -->
        </div>
        <!--div class='ibox-content'-->
      </div>
      <!--<div class='ibox float-e-margins' -->
    </div>
    <!--div class='col-lg-12'-->
  </div>
  <!--div class='row'-->
</div>

<!--Show Modal Popups View & Delete -->
<div class='modal fade' id='viewModal' style="overflow:hidden;" role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog modal-sm'>
    <div class='modal-content modal-sm'></div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class='modal fade' id='salidaModal' style="overflow:hidden;" role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog modal-sm'>
    <div class='modal-content modal-sm'></div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class='modal fade' id='editEModal' style="overflow:hidden;" role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog  modal-sm'>
    <div class='modal-content modal-sm'></div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class='modal fade' id='deleteModal' style="overflow:hidden;"  role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'></div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script src="<?= base_url(); ?>assets/js/funciones/<?=$urljs; ?>"></script>
