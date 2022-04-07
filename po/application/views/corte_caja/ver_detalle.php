<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"
  aria-hidden="true">&times;</button>
  <h4 class="modal-title">Ver detalle</h4>
</div>
<div class="modal-body">
  <!--div class="wrapper wrapper-content  animated fadeInRight"-->
  <div class="row" id="row1">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered " id="table_mov">
            <thead>
              <tr class="info">
                <th class="col-md-8">DETALLE</th>
                <th class="col-md-4"><i>TOTAL</i></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>FECHA</td>
                <td>$<label class="pull-right" id="ingresos"><?=$fecha?> </label></td>
              </tr>
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
              <tr>
                <td>EFECTIVO EN CAJA</td>
                <td>$<label class="pull-right" id="total_efectivo"><?=$efectivo_caja ?></label></td>
              </tr>
              <tr>
                <td>OBSERVACIONES</td>
                <td>$<label class="pull-right" id="total_efectivo"><?=$observaciones ?></label></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>
  <!--/div-->
  <!--/div-->
</div>
