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
            <table class="table table-stripped">
              <?php
              switch ($process) {
                case 'cargas':
                  ?>
                  <thead>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo</th>
                    <th>Subtotal</th>
                  </thead>
                  <tbody>
                    <?php if ($rows!=NULL):
                    foreach ($rows as $key): ?>
                      <tr>
                        <td><?=$key->modelo." ".$key->color ?></td>
                        <td style="text-align:right"><?=$key->cantidad ?></td>
                        <td style="text-align:right"><?=number_format($key->costo,2) ?></td>
                        <td style="text-align:right"><?=number_format($key->subtotal,2) ?></td>
                      </tr>
                    <?php endforeach;
                    endif ?>
                    <?php if ($rowserv!=NULL):
                    foreach ($rowserv as $key1): ?>
                      <tr>
                        <td><?=$key1->nombre ?></td>
                        <td style="text-align:right"><?=$key1->cantidad ?></td>
                        <td style="text-align:right"><?=number_format($key1->costo,2) ?></td>
                        <td style="text-align:right"><?=number_format($key1->subtotal,2) ?></td>
                      </tr>
                    <?php endforeach;
                    endif ?>
                  </tbody>
                  <?php
                  break;
                case 'descarga':
                ?>
                <thead>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Costo</th>
                  <th>Precio</th>
                  <th>Subtotal</th>
                </thead>
                <tbody>
                  <?php foreach ($rows as $key): ?>
                    <tr>
                      <td><?=$key->modelo." ".$key->color ?></td>
                      <td style="text-align:right"><?=$key->cantidad ?></td>
                      <td style="text-align:right"><?=number_format($key->costo,2) ?></td>
                      <td style="text-align:right"><?=number_format($key->precio,2) ?></td>
                      <td style="text-align:right"><?=number_format($key->subtotal,2) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <?php
                  break;
                case 'venta':
                ?>
                <thead>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Subtotal</th>
                </thead>
                <tbody>
                  <?php if ($rows!=0):
                  foreach ($rows as $key): ?>
                    <tr>
                      <td><?=$key->modelo." ".$key->color ?></td>
                      <td style="text-align:right"><?=$key->cantidad ?></td>
                      <td style="text-align:right"><?=number_format($key->precio,2) ?></td>
                      <td style="text-align:right"><?=number_format($key->subtotal,2) ?></td>
                    </tr>
                <?php endforeach;
                endif ?>
                <?php if ($rowserv!=NULL):
                foreach ($rowserv as $key): ?>
                  <tr>
                    <td><?=$key->nombre ?></td>
                    <td style="text-align:right"><?=$key->cantidad ?></td>
                      <td style="text-align:right"><?=number_format($key->precio_fin,2) ?></td>
                    <td style="text-align:right"><?=number_format($key->subtotal,2) ?></td>
                  </tr>
                <?php endforeach;
                endif ?>
                </tbody>
                <?php
                  break;

                  case 'descarga':
                  ?>
                  <thead>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                  </thead>
                  <tbody>
                    <?php foreach ($rows as $key): ?>
                      <tr>
                        <td><?=$key->nombre ?></td>
                        <td style="text-align:right"><?=$key->cantidad ?></td>
                        <td style="text-align:right"><?=number_format($key->costo,2) ?></td>
                        <td style="text-align:right"><?=number_format($key->precio,2) ?></td>
                        <td style="text-align:right"><?=number_format($key->subtotal,2) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <?php
                    break;
                  case 'traslado':
                  ?>
                  <thead>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                  </thead>
                  <tbody>
                    <?php foreach ($rows as $key): ?>
                      <tr>
                        <td><?=$key->modelo." ".$key->color ?></td>
                        <td style="text-align:right"><?=$key->cantidad ?></td>
                        <td style="text-align:right"><?=number_format($key->costo,2) ?></td>
                        <td style="text-align:right"><?=number_format($key->precio,2) ?></td>
                        <td style="text-align:right"><?=number_format($key->subtotal,2) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <?php
                    break;
                    case 'ajuste':
                    ?>
                    <thead>
                      <th>Producto</th>
                      <th>Sistema</th>
                      <th>Manual</th>
                      <th>Costo</th>
                      <th>Precio</th>
                      <th>Subtotal</th>
                    </thead>
                    <tbody>
                      <?php foreach ($rows as $key): ?>
                        <tr>
                          <td><?=$key->modelo." ".$key->color ?></td>

                          <td style="text-align:right"><?=$key->stock_anterior ?></td>
                          <td style="text-align:right"><?=$key->cantidad ?></td>
                          <td style="text-align:right"><?=number_format($key->costo,2) ?></td>
                          <td style="text-align:right"><?=number_format($key->precio,2) ?></td>
                          <td style="text-align:right"><?=number_format($key->subtotal,2) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <?php
                      break;

                default:
                  // code...
                  break;
              }
               ?>
            </table>
          </div>
      </div>
  </div>
</div>
<div class="modal-footer">
  <?php if ($process=="venta"): ?>
  <input type="hidden" name="id_tr" id="id_tr" value="<?=$id;?>">
  <button type="button" class="btn btn-success printicket" id="btn_printicket" name="btn_printicket"><i class="mdi mdi-printer"></i> Reimprimir</button>
  <?php endif; ?>
    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
</div>
