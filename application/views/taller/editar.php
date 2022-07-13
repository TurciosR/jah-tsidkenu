<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox" id="main_view">
        <div class="ibox-title">
          <h3 class="text-success"><b><i class="mdi mdi-plus"></i> Editar Venta</b></h3>
        </div>
        <div class="ibox-content">
          <?php
          if($row_ap!=NULL):
            if($row_ap->vigente==1):
              if( $row_ap->id_usuario==$id_usuario ):?>
          <form id="form_edit" novalidate>
            <input type="hidden" id="id_trabajo_taller" name="id_trabajo_taller" value="<?=$row->id_trabajo_taller ?>">
            <input type="hidden" id="data_ingreso" name="data_ingreso" value="">
            <input type="hidden" id="proceso" name="proceso" value="editar">
            <input type="hidden" id="id_cliente" name="id_cliente" value="<?=$row->id_cliente?>">
            <input type="hidden" id="porc_clasifica" name="porc_clasifica" value="">
            <div class="row">



              <div class="col-lg-6">
                <div class="form-group">
                    <label for="cliente">Cliente<span class="text-danger">*</span></label>
                    <?php if($row_clientes!=NULL):?>
                  <select class="form-group select usage clientes" name="client" id="client">

                        <?php foreach ($row_clientes as $rc): ?>
                            <option value="<?=$rc->id_cliente?>"
                                <?php if($rc->id_cliente==$row->id_cliente) echo "selected"; ?>>
                                  <?=$rc->nombre?></option>
                        <?php endforeach; ?>

                    </select>
                      <?php endif; ?>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="form-group">
                    <label for="fecha">Fecha Venta<span class="text-danger">*</span></label>
                  <input type="text" name="fecha" id="fecha" class="form-control datepicker"
                  placeholder="Seleccione una fecha" value="<?=d_m_y($row->fecha)?>"
                  required data-parsley-trigger="change">
                </div>
              </div>
              <div class="form-actions col-sm-3">
                <label for="fecha">Guardar Venta</label>
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>" id="csrf_token_id">
                <button style="width:100%" type="submit" id="btn_add" name="btn_add"
                class="btn btn-success float-right"><i
                class="mdi mdi-content-save"></i>
                Guardar Venta
              </button>
              </div>
              <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?php echo $id_sucursal;?>">
            </div>
            <div class="row">
              <div class="col-lg-3" hidden>
                <div class="form-group">
                  <select data-parsley-trigger="change" style="width:100%" required class="select2" id="sucursal" name="sucursal">
                    <?php foreach ($sucursal as $key): ?>
                      <option <?php if($id_sucursal==$key->id_sucursal){echo "selected";} ?> value="<?=$key->id_sucursal ?>"><?=$key->nombre." ".$key->direccion ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
          </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group has-info">
                  <div id="scrollable-dropdown-menu">
                    <input type="text" id="producto" name="producto"  class="form-control" placeholder="Ingrese la Descripción de producto" data-provide="typeahead">
                    <input type="hidden" id="id_producto" name="id_producto">
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <input type="text" name="total1" id="total1" readonly class="form-control text-center" value="<?=round($row->total,2) ?>">
                  <input type="hidden" name="total" id="total"  value="<?=round($row->total,2) ?>">
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <input type="text" name="items" id="items" readonly class="form-control text-center itemss"
                  placeholder="items" value=""
                  data-parsley-trigger="change">
                  <input type="hidden" name="item1" id="item1" class="itemss">
                </div>
              </div>
            </div>

              <div class="row  table-wrapper-scroll-y my-custom-scrollbar">
              <div class="col-lg-12">
                  <table class="table-striped">
                    <thead>
                      <tr>
                        <th style="width:5%;">No.</th>
                        <th style="width:25%;">Descripcion</th>
                        <th style="width:10%;">Stock</th>
                        <th style="width:10%;">Cantidad</th>
                        <th style="width:10%;">Estado</th>
                        <th style="width:10%;">Precio</th>
                        <th style="width:10%;">Descto</th>
                        <th style="width:10%;">Subtotal</th>
                        <th style="width:10%;">Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="table_producto">
                      <?php if ($detalles!=NULL): ?>
                      <?php foreach ($detalles as $key):
                        $descrip=$key->nombre." ".$key->marca." ".$key->modelo." ".$key->color;
                         ?>
                        <tr>
                				<td><?=$key->id_producto ?></td>
                				<td><input type='hidden' class='id_producto' value='<?=$key->id_producto ?>'> <input type='hidden' class='id_s' value='<?=$key->id_stock ?>'> <input type='hidden' class='nombre' value='<?=$descrip?>'><?=$descrip;?></td>
                        <td><input type='hidden' class='color' value='<?=$key->id_color ?>'><input type='hidden' class='stock' value='<?=$key->stock; ?>' style='width:100%;'><?=$key->stock; /*$key->stock+$key->reservado*/ ?></td>
                				<td><input type='text' class='form-control cantidad numeric' value='<?=$key->cantidad ?>' style='width:100%;'></td>
                				<td><input type='hidden' class='form-control costo decimal' value='<?=$key->costo ?>' style='width:100%;'> <?=$key->estado ?></td>
                				<td><?= $key->precios ?></td>
                        <td><input type='text' class='form-control descuento' readonly value='<?=$key->descuento ?>' style='width:100%;'></td>
                				<td><input type='text' class='form-control subtotal' readonly value='<?=$key->subtotal ?>' style='width:100%;'></td>
                				<td class='text-center'><a class='btn btn-danger delete_tr' style='color: white'><i class='mdi mdi-trash-can'></i></a></td>
                			  </tr>
                      <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                    <tbody id="table_servicio">
                    <?php if ($detalleservicios!=NULL): ?>
                      <?php foreach ($detalleservicios as $key): ?>

                        <tr>
                        <td><?=$key->id_producto ?></td>
                        <td><input type='hidden' class='id_producto' value='<?=$key->id_producto ?>'> <input type='hidden' class='id_s' value='<?=$key->id_stock ?>'> <input type='hidden' class='nombre' value='<?=$key->nombre ?>'><?=$key->nombre."(SERVICIO)" ?></td>
                        <td><input type='hidden' class='color' value='<?=$key->id_color ?>'><input type='hidden' class='stock' value='-' style='width:100%;'>-</td>
                        <td><input type='text' class='form-control cantidad numeric' value='<?=$key->cantidad ?>' style='width:100%;'></td>
                        <td><input type='hidden' class='form-control costo decimal' value='<?=$key->costo ?>'<input type='hidden' class='form-control precio_minimo decimal' value='<?=$key->precio_minimo ?>' style='width:100%;'>-</td>
                        <td><input type="hidden" class="form-control precio_sugerido decimal" value='<?=$key->precio_sugerido ?>' style='width:100%;''><input type='text' class='form-control precio_base decimal'  value='<?=$key->precio_fin ?>' style='width:100%;''></td>
                        <td><input type='text' class='form-control descuento' readonly value='<?=$key->descuento ?>' style='width:100%;'></td>
                        <td><input type='text' class='form-control subtotal' readonly value='<?=$key->subtotal ?>' style='width:100%;'></td>
                        <td class='text-center'><a class='btn btn-danger delete_tr' style='color: white'><i class='mdi mdi-trash-can'></i></a></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row" id="totals">
                <table class="table invoice-total">
                <tbody>
                <tr class="higrow">
                    <td><strong>TOTAL VENTA:</strong></td>
                    <td class="total_final"><?=round($row->total,2) ?></td>
                </tr>
                </tbody>
              </table>
              </div>

          </form>
        <?php  else:?>
          <div></div>
            <div class='alert alert-warning text-center' style='font-weight: bold;'>
              <label style='font-size: 15px;'>Ya existe una apertura de caja realizada por "<?=$usuario_ap->nombre?>"!!</label>
              <br>
              <label style='font-size: 15px;'>Debe de realizar el corte con el usuario que hizo la  apertura vigente, para poder iniciar una nueva apertura de caja.</label>

            </div>
         <?php endif;?>
        <?php  else:
          redirect("caja/apertura");
         endif;
       else:
         redirect("caja/apertura");
       endif;?>
        </div>

      </div>
      <div class="ibox" style="display: none;" id="divh">
        <div class="ibox-content text-center">
          <div class="row">
            <div class="col-lg-12">
              <h2 class="text-danger blink_me">Espere un momento, procesando su solicitud!</h2>
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
