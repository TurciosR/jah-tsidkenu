<?php
defined('BASEPATH') or exit('No direct script access allowed');
$newdate = uniqid();
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
						<div  class="form-group col-md-6">
							<label>CLIENTE</label>
							<input type="text"  class="form-control"   value="<?= $nombre ?>"  readonly>
						</div>
                        <div class="form-group col-md-3" >
                            <label>SALDO: </label>
                            <input type="text" class="form-control" id="saldo" name="saldo"   value="<?= $saldo ?>" readonly>
                        </div>
                        <div class="form-group col-md-3" >
                            <label>ABONADO: </label>
                            <input type="text" class="form-control" id="abonado" name="abonado"  value="<?= $abono ?>" readonly>
                        </div>
					</div>
                    <?php
                    if ($estado==0) {
                        ?>
                        <div class="row ">
                            <div class="form-group col-md-3">
                                <label>NUM. DOC: </label>
                                <input type="text" id="num_doc" name="num_doc" class="form-control" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label>ABONO: </label>
                                <input type="text" id="abono" name="abono" class="form-control decimal" value="">
                            </div>
														<div class="form-group col-md-3" >
															<label>FECHA: </label>
															<input type="text" class="form-control datepp <?= $newdate ?>"   value="<?= date("d-m-Y") ?>" readonly>
														</div>
                            <div class="form-group col-md-3">
															<label style='color:white;' >D</label>
                                <button style='width:100%;' type="button" id="abonar" class="btn btn-primary"> ABONAR
                                </button>
	                                <input type="hidden" id="id_cuenta" name="id_cuenta" value="<?= $id_cuenta ?>">
                            </div>
                        </div>
                        <?php
                    }else{
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-warning text-center">CREDITO CANCELADO</div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
					<table class="table table-condensed table-striped" id="inventable">
							<thead class="thead-inverse">
								<tr>
									<th class='info thick-line col-lg-2'><strong>N°</strong></th>
									<th class='info thick-line col-lg-2'><strong>Abono</strong></th>
									<th class='info thick-line col-lg-2'><strong>Fecha</strong></th>
								</tr>
							</thead>
							<tbody id="listdetalle">
								<?php
                                $subtotal=0;
                                $i=0;
								foreach ($cuenta_detalle as $row) {

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
$.fn.datepicker.dates['es'] = {
	days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
	daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
	daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
	months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
	monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
};
$(".datepp").datepicker({
	format: 'dd-mm-yyyy',
	language: 'es',
});
</script>
