<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="modal-header">
  <div class="col-lg-1">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
  </div>
  <div class="col-lg-11">
    <h4 class="modal-title">Detalles</h4>
    <small class="font-bold"></small>
  </div>
</div>
<div class="modal-body">
  <div class="row">
      <div class="col-lg-12">
          <div class="form-group">
            <h3>Precios Contado</h3>
            <table class="table table-stripped">
              <thead>
                <td>Descripción</td>
                <td>Precio</td>
              </thead>
              <tbody>
                <?php foreach ($precios as $key): ?>
                  <tr>
                    <td><?=$key->descripcion ?></td>
                    <td style="text-align:right"><?=$key->precio_venta ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

          </div>
      </div>
  </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
</div>
