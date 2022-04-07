<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox" id="main_view">
				<div class="ibox-title">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 class="text-navy"><b><i class="fa fa-plus-circle"></i> Ver Detalle Factura</b></h3>
				</div>
				<div class="ibox-content">
					<div class="row " >
						<div  class="form-group col-md-6">
							<label>CLIENTE</label>
							<input type="text"  class="form-control"   value="<?= $nombre ?>"  readonly>
						</div>
						<div class="form-group col-md-6" >
							<label>FECHA: </label>
							<input type="text" class="form-control"   value="<?= $fecha ?>" readonly>
						</div>
					</div>
					<div class="row " >
						<div  class="form-group col-md-6">
							<label>TIPO DE DOCUMENTO</label>
							<input type="text" class="form-control"   value="<?= $tipo_desc ?>" readonly>
						</div>
						<div class="form-group col-md-6">
							<label>NUMERO DOCUMENTO: </label>
							<input type="text" class="form-control"   value="<?= $num_doc ?>" readonly>
						</div>
					</div>
					<table class="table table-condensed table-striped" id="inventable">

						<thead class="thead-inverse">
							<tr>
								<th class='info thick-line col-lg-2'><strong>CANTIDAD</strong></th>
								<th class='info thick-line col-lg-2'><strong>DESCRIPCIÓN</strong></th>
								<th class='info thick-line col-lg-2'><strong>PRECIO</strong></th>
								<th class='info thick-line col-lg-2'><strong>TOTAL</strong></th>
							</tr>
						</thead>
						<tbody id="listdetalle">
							<?php
							foreach ($factura_detalle as $row) {
								$cantidad=$row->cantidad;
								$valor=$row->valor;
								$sub_total=$row->sub_total;
								$descripcion=$row->descripcion;
								if ($tipo=="CCF") {
									$valor=number_format(round(($valor/1.13), 2),2);
									$sub_total=number_format(round(($sub_total/1.13), 2),2);

								}

								echo "<tr>";
								echo "<td>".$cantidad."</td>";
								echo "<td>".$descripcion."</td>";
								echo "<td>".$valor."</td>";
								echo "<td>".$sub_total."</td>";
								echo "</tr>";
							}

							?>
							<tr>
								<td class="thick-line"></td>
								<td class="thick-line"></td>
								<td class="thick-line "><strong>SUB TOTAL$:</strong></td>
								<td class="thick-line "><strong><?= $subtotal ?></strong></td>
							</tr>
							<?php if ($tipo=="CCF"): ?>

									<tr>
										<td class="thick-line"></td>
										<td class="thick-line"></td>
										<td class="thick-line "><strong>IVA$:</strong></td>
										<td  class="thick-line "><strong><?= $iva; ?></strong></td>
									</tr>
									<tr>
										<td class="thick-line"></td>
										<td class="thick-line"></td>
										<td class="thick-line "><strong>-IVA RETENIDO$:</strong></td>
										<td  class="thick-line "><strong><?= $retencion; ?></strong></td>
									</tr>

							<?php endif; ?>
						</tbody>

						<tfoot>
							<tr>
								<td class="thick-line"></td>
								<td class="thick-line"></td>
								<td class="thick-line "><strong>TOTAL$:</strong></td>
								<td  class="thick-line "><strong><?= $total_final; ?></strong></td>
							</tr>
						</tfoot>


					</table>
					<div class="row">
						<div class="col-lg-12">
							<input type="hidden"  id="id_factura1" name="id_factura1" value="<?= $id_factura ?>">
							<input type="hidden"  id="tipo_impresion" name="tipo_impresion" value="<?= $tipo ?>">
							<div class="row">
								<div class="form-actions col-lg-12">
									<button type="button" id="imprimir" class="btn btn-primary m-t-n-xs pull-right"> Imprimir</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ajaxStart(function() {
  $("#cargando").show();
});

// evento ajax stop
$(document).ajaxStop(function() {
  $("#cargando").hide();
});


</script>
