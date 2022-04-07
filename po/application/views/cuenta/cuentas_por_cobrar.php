<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: Wed, 1 Jan 2020 00:00:00 GMT"); // Anytime in the past

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
          <h3 class="text-navy"><b><i class="fa fa-list"></i> AGREGAR CUENTA </b></h3>
        </div>
        <div class="ibox-content">
          <div class="panel panel-primary">
            <div class="panel-heading">Datos Cuenta</div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-lg-4">
                  <div id="a">
                    <label>Nombre de Cliente</label>
                    <div id="scrollable-dropdown-menu">
                      <input type="text" id="producto_buscar" name="producto_buscar"  style="width:100% !important" class=" form-control usage typeahead" placeholder="Ingrese nombre del cliente" data-provide="typeahead" style="border-radius:0px">
                      <input type="hidden" name="id_cliente" id="id_cliente" value="">
                    </div>
                  </div>
                </div>
                <div class="form-group col-lg-4">
                  <label>Edad</label>
                  <input type="text" placeholder="" class="form-control"  id="edad" name="edad" value="" readonly>
                </div>
                <div class="form-group col-lg-4">
                  <label>Sexo</label>
                  <input type="text" placeholder="" class="form-control"  id="sexo" name="sexo" value="" readonly>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-lg-4">
                  <label>N° de Documento</label>
                  <input type="text" placeholder="" class="form-control"  id="num_doc" name="num_doc" value="">
                </div>
                <div class="form-group col-lg-4">
                  <label>Abono</label>
                  <input type="text" placeholder="" class="form-control"  id="abono" name="abono" value="">
                </div>
                <div class="form-group col-lg-4">
                  <label>Fecha</label>
                  <input type="text" readonly placeholder="" style="text-align: center;" class="form-control datepicker"  id="fecha_add" name="fecha_add" value="<?=date("d-m-Y") ?>">
                </div>
                <div class="form-group col-lg-4">
                  <label>Nombre Empresa</label>
                  <input type="text" placeholder="" class="form-control"  id="empresa" name="empresa" value="">
                </div>
              </div>
            </div>
          </div>

          <div class="panel panel-primary cofcff" >
            <div class="panel-heading">Detalle Credito</div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group has-info single-line">
                    <label class="control-label">Cantidad</label>
                    <input type="text" placeholder="" class="form-control numeric" name="cant" id="cant">
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group has-info single-line">
                    <label class="control-label">Descripcion</label>
                    <input type="text" placeholder="" class="form-control upper" name="desc" id="desc">
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group has-info single-line">
                    <label class="control-label">Precio</label>
                    <input type="text" placeholder="" class="form-control decimal" name="precio" id="precio">
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group has-info table-responsive">
                    <table class="table table-condensed table-striped">
                      <thead class="thead-inverse">
                        <tr>
                          <th class="info thick-line col-lg-1"><strong>Cant.</strong></th>
                          <th class="info thick-line col-lg-6"><strong>Descripcion</strong></th>
                          <th class="info thick-line col-lg-2"><strong>Precio</strong></th>
                          <th class="info thick-line col-lg-2"><strong>Subtotal</strong></th>
                          <th class="info thick-line col-lg-1"><strong>Opciones</strong></th>
                        </tr>
                      </thead>
                      <tbody id="appde">

                      </tbody>
                      <tfoot>
                        <tr>
                          <td class="thick-line"></td>
                          <td class="thick-line"></td>
                          <td class="thick-line "><strong>Total:</strong></td>
                          <td  class="thick-line" ><strong id='total'></strong></td>
                          <td class="thick-line"></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-actions col-lg-12">
              <button type="hidden" id="process" value="insert">
                <button type="button" id="submit" class="btn btn-primary m-t-n-xs pull-right"> Guardar<i class="fa fa-save"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= base_url("assets/js/funciones/funciones_cuenta.js?a=".rand(1,999)); ?>"></script>
