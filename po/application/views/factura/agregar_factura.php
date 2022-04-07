<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $_SESSION['id_sucursal'];  ?>">
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-12">
  </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox" id="main_view">
        <div class="ibox-title">
          <h3 class="text-navy"><b><i class="fa fa-list"></i> AGREGAR FACTURA </b></h3>
        </div>
        <div class="ibox-content">
          <div class="panel panel-primary">
            <div class="panel-heading">Datos Factura</div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-lg-4">
                  <label>Tipo Documento</label>
                  <select class="form-control select" name="tipo" id="tipo">
                    <option value="">Seleccione</option>
                    <option value="COF">Facturas</option>
                    <option value="CCF">Créditos Fiscales</option>
                    <option value="ABONO">Notas de Abono</option>
                  </select>
                </div>
                <div class="form-group col-lg-4">
                  <label>N° de Documento</label>
                  <input type="text" placeholder="" class="form-control"  id="num_doc" name="num_doc" value="">
                </div>
                <div class="form-group col-lg-4 ccf" hidden>
                  <div class="form-check" style="margin-top: 25px;">
                    <label>
                      <input type="checkbox" name="check" id="retencion_bol"> <span class="label-text">Retención</span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-lg-4">
                  <div id="a">
                    <label>Nombre de Cliente</label>
                    <div id="scrollable-dropdown-menu">
                      <input type="text" id="producto_buscar" name="producto_buscar"  style="width:100% !important" class=" form-control usage typeahead" placeholder="Ingrese nombre del cliente" data-provide="typeahead" style="border-radius:0px" readonly>
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
              <div class="row ccf" hidden>
                <div class="form-group col-lg-6 ">
                  <label>NIT</label>
                  <input type="text" placeholder="" class="form-control nit"  id="nit" name="nit" value="">
                </div>
                <div class="form-group col-lg-6">
                  <label>NRC</label>
                  <input type="text" placeholder="" class="form-control"  id="nrc" name="nrc" value="">
                </div>
              </div>


              <div class="row abono" hidden>
                <div class="form-group col-lg-4">
                  <label>Saldo Anterior</label>
                  <input type="text" placeholder="" class="form-control decimal"  id="saldo_anterior" name="saldo_anterior" value="" readonly>
                </div>
                <div class="form-group col-lg-4">
                  <label>Abono Hoy</label>
                  <input type="text" placeholder="" class="form-control decimal"  id="abono_hoy" name="abono_hoy" value="">
                </div>
                <div class="form-group col-lg-4">
                  <label>Saldo Actual</label>
                  <input type="text" placeholder="" class="form-control decimal"  id="saldo_actual" name="saldo_actual" value="" readonly>
                </div>
                <input type="hidden" id="id_cuenta" name="id_cuenta" value="">
                <input type="hidden" id="abono_anterior" name="abono_anterior" value="">
              </div>
              <div class="row">
                <div class="form-group col-lg-4 ">
                  <label>Fecha</label>
                  <input type="text" placeholder="" class="form-control datepicker"  id="fecha_actual" name="fecha_actual" value="<?=$fecha_actual  ?>" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-primary cofcff" hidden>
            <div class="panel-heading">Detalle Factura</div>
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
                          <th class="info thick-line col-lg-1"><strong>Lineas</strong></th>
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
                <input type="hidden" name="iva_input" id="iva_input" value="<?= $iva ?>">
                <input type="hidden" name="retencion_input" id="retencion_input" value="<?= $retencion ?>">
                <button type="button" id="submit" class="btn btn-primary m-t-n-xs pull-right"> Guardar<i class="fa fa-save"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class='modal fade' id='creditos_activos' style="overflow:hidden;" role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' data-controls-modal="your_div_id" data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog modal-md'>
      <div class='modal-content modal-md'>
        <div class="wrapper wrapper-content  animated fadeInRight">
        	<div class="row">
        		<div class="col-lg-12">
        			<div class="ibox" id="main_view">
        				<div class="ibox-title">
        					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        					<h3 class="text-navy"><b><i class="fa fa-plus-circle"></i> CREDITOS ACTIVOS</b></h3>
        				</div>
        				<div class="ibox-content">
        					<div class="row " >
        						<div  class="form-group col-md-12">
        							<label>CLIENTE</label>
        							<input type="text"  class="form-control"  id="cliente_credito"  value=""  readonly>
        						</div>

        					</div>
        					<div class="row">
        						<div class="col-lg-12">
        							<table class="table table-condensed table-striped" id="inventable">

        								<thead class="thead-inverse">
        									<tr>
        										<th class='info thick-line col-lg-2'><strong>FECHA</strong></th>
        										<th class='info thick-line col-lg-2'><strong>MONTO</strong></th>
        										<th class='info thick-line col-lg-2'><strong>SALDO</strong></th>
        										<th class='info thick-line col-lg-2'><strong>ACCIÓN</strong></th>
        									</tr>
        								</thead>
        								<tbody id="lista_creditos">

        								</tbody>
        							</table>

        						</div>

        					</div>


        				</div>
        			</div>
        		</div>
        	</div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <script src="<?= base_url(); ?>assets/js/funciones/funciones_factura.js"></script>
