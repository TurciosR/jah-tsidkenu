<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox" id="main_view">
				<div class="ibox-title">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 class="text-navy"><b><i class="fa fa-plus-circle"></i> Detalle Abonos</b></h3>
				</div>
				<div class="ibox-content">
					<div class="row " >
						<div  class="form-group col-md-8">
							<label>CLIENTE</label>
							<input type="text"  class="form-control"   value="<?= $nombre ?>"  readonly>
						</div>
						<div class="form-group col-md-4" >
							<label>FECHA: </label>
							<input type="text" class="form-control"   value="<?= $fecha ?>" readonly>
						</div>
					</div><div class="row " >
						<div  class="form-group col-md-4">
							<label>MONTO:</label>
							<input type="text"  class="form-control"   value="<?= $monto ?>"  readonly>
						</div>
						<div class="form-group col-md-4" >
							<label>SALDO: </label>
							<input type="text" class="form-control"   value="<?= $saldo ?>" readonly>
						</div>
                        <div class="form-group col-md-4" >
							<label>ABONAR: </label>
							<input type="text" class="form-control"   value="<?= $abono?>" readonly>
						</div>
					</div>
					<table class="table table-condensed table-striped" id="inventable">
							<thead class="thead-inverse">
								<tr>
									<th class='info thick-line col-lg-2'><strong>CANTIDAD</strong></th>
									<th class='info thick-line col-lg-2'><strong>DESCRIPCIÓN</strong></th>
									<th class='info thick-line col-lg-2'><strong>PRECIO</strong></th>
									<th class='info thick-line col-lg-2'><strong>SUBTOTAL</strong></th>
								</tr>
							</thead>
							<tbody id="listdetalle">
								<?php
                                $subtotal=0;
								foreach ($cuenta_detalle as $row) {
									$cantidad=$row->cantidad;
									$valor=$row->precio;
									$sub_total=$row->subtotal;
									$descripcion=$row->detalle;
									$subtotal+=$sub_total;
									echo "<tr>";
									echo "<td>".$cantidad."</td>";
									echo "<td>".$descripcion."</td>";
									echo "<td>".$valor."</td>";
									echo "<td>".$sub_total."</td>";
									echo "</tr>";
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<td class="thick-line"></td>
									<td class="thick-line"></td>
									<td class="thick-line "><strong>TOTAL$:</strong></td>
									<td  class="thick-line "><strong><?= $subtotal; ?></strong></td>
								</tr>
							</tfoot>
					</table>
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
								<div class="form-actions col-lg-12">
									<button type="button"  data-dismiss="modal" class="btn btn-default m-t-n-xs pull-right"> Cerrar</button>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
