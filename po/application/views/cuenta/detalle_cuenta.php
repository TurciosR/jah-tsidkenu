<?php
defined('BASEPATH') or exit('No direct script access allowed');

$newdate = uniqid();
$button = uniqid();
$newempresa = uniqid();
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
						<div  class="form-group col-md-7">
							<label>CLIENTE</label>
							<input type="text"  class="form-control"   value="<?= $nombre ?>"  readonly>
						</div>
						<div class="form-group col-md-3" >
							<label>FECHA: </label>
							<input type="text" class="form-control datep <?= $newdate ?>"   value="<?= $fecha ?>" readonly>
						</div>
						<div class="form-group col-md-2">
							<label style='color:white;'>S</label>
							<button type="button" style='width:100%' class='btn btn-primary <?= $button ?>' id="" name="button"> <i class='fa fa-save'> </i> </button>
						</div>
					</div>
					<div class="row">
						<div  class="form-group col-md-7">
							<label>EMPRESA</label>
							<input type="text"  class="form-control <?=$newempresa ?>"   value="<?=$empresa ?>"  >
						</div>
					</div>
					<div class="row " >
						<div  class="form-group col-md-4">
							<label>MONTO:</label>
							<input type="text"  class="form-control"   value="<?= $monto ?>"  readonly>
						</div>
						<div class="form-group col-md-3" >
							<label>SALDO: </label>
							<input type="text" class="form-control"   value="<?= $saldo ?>" readonly>
						</div>
            <div class="form-group col-md-3" >
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
					<br>

					<div class="row " >
						<div  class="form-group col-md-7">
							<label>ABONOS PREVIOS</label>
						</div>
					</div>
					<table class="table table-condensed table-striped" >
							<thead class="thead-inverse">
								<tr>
									<th class='info thick-line col-lg-2'><strong>N°</strong></th>
									<th class='info thick-line col-lg-2'><strong>Abono</strong></th>
									<th class='info thick-line col-lg-2'><strong>Fecha</strong></th>
								</tr>
							</thead>
							<tbody>
								<?php
                                $subtotal=0;
                                $i=0;
								foreach ($cuenta_detalle2 as $row) {

								    $i+=1;
									$abono=$row->abono;
									$fecha=$row->fecha;
									echo "<tr>";
									echo "<td>".$i."</td>";
									echo "<td>".$abono."</td>";
									echo "<td>".$fecha."</td>";
									//echo "<td>".$sub_total."</td>";
									echo "</tr>";
								}
								?>
							</tbody>
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

<script type="text/javascript">
$(document).on('click', '.<?=$button?>', function(event) {
	event.preventDefault();
	fechad = $(".<?=$newdate?>").val();
	empresad = $(".<?=$newempresa?>").val();

	$.ajax({
		url: '<?=base_url("Cuenta/actdate")  ?>',
		type: 'POST',
		dataType: 'json',
		data: {id_cuenta: '<?=$id_cuenta?>',fecha:fechad,empresa:empresad},
		success: function(xdatos)
		{
			display_notify(xdatos.typeinfo,xdatos.msg);

			setTimeout(
				function()
				{
					location.reload();
				},500
			);
		}
	});

});
$.fn.datepicker.dates['es'] = {
	days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
	daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
	daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
	months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
	monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
};
$(".datep").datepicker({
	format: 'dd-mm-yyyy',
	language: 'es',
});
</script>
