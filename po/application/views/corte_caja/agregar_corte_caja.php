<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"
  aria-hidden="true">&times;</button>
  <h4 class="modal-title">Corte Caja</h4>
</div>
<div class="modal-body">
  <!--div class="wrapper wrapper-content  animated fadeInRight"-->
  <div class="row" id="row1">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered " id="table_mov">
            <thead>
              <tr class="info">
                <th class="col-md-8">TIPO MOVIMINETO</th>
                <th class="col-md-4"><i>TOTAL</i></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>INGRESOS</td>
                <td>$<label class="pull-right" id="ingresos"><?=$ingresos?> </label></td>
              </tr>
              <tr>
                <td>ABONOS</td>
                <td>$<label class="pull-right" id="abonos"><?= $abonos?></label></td>
              </tr>
              <tr>
                <td>OTROS INGRESOS</td>
                <td>$<label class="pull-right" id="otros_ingresos"><?= $otros_ingresos?></label></td>
              </tr>
              <tr>
                <td>EGRESOS</td>
                <td>$<label class="pull-right" id="egresos"><?=$egresos ?></label></td>
              </tr>

              <tr>
                <td>SALDO CAJA</td>
                <td>$<label class="pull-right" id="total_efectivo"><?=$total_efectivo ?></label></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-md-12">
          <div class="form-group has-info single-line">
            <label>EFECTIVO EN CAJA </label> <input type='text'  class='form-control numeric' id='efectivo_caja' name='efectivo_caja'>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group has-info single-line">
            <label>OBSERVACIONES</label>
            <textarea class="form-control upper" id='observaciones' name='observaciones'rows="3"></textarea>
          </div>
        </div>
        <div class="form-actions col-lg-12">
          <button type="submit" id="btn_corte" class="btn btn-primary m-t-n-xs pull-right"> Guardar</button>
        </div>
      </div>
  </div>
  <!--/div-->
  <!--/div-->
</div>

<script type="text/javascript">
$(".numeric").numeric(
  {
    negative:false,
  }
);
$(".upper").blur(function() {
  $(this).val($(this).val().toUpperCase())
});
</script>
