<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-2">
	</div>
</div>
<style media="screen">
	input[type='text']
	{
		height: 28px !important;
	}
</style>
<style media="screen">
.loader {
border: 5px solid #f3f3f3; /* Light grey */
border-top: 5px solid #3498db; /* Blue */
border-radius: 50%;
width: 50px;
height: 50px;
animation: spin 2s linear infinite;
}

@keyframes spin {
0% { transform: rotate(0deg); }
100% { transform: rotate(360deg); }
}
</style>

<style media="screen">
	#editable2  tbody td:nth-child(4)
	{
		text-align: right;
	}
	#editable2  tbody td:nth-child(5)
	{
		text-align: right;
	}
	#editable2  tbody td:nth-child(6)
	{
		text-align: center;
	}
</style>
<?php
$m = date("m");
$y = date("Y");
$ini = "01-".$m."-$y";
$ult = cal_days_in_month(CAL_GREGORIAN, $m, $y);
$fin = $ult."-".$m."-".$y;
 ?>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox ">
				<div class="ibox-title">
					<h3 class="text-navy"><b><i class="fa fa-1x<?=$icono?>"></i> <?=$nombre_archivo?></b></h3>
				</div>
				<div class="ibox-content">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group single-line">
									<label>Fecha</label>
									<input type="text" placeholder="Fecha Inicial" style="text-align: center;" class="datepicker form-control" id="fecha1" name="fecha1" value="<?=date("d-m-Y") ?>" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group single-line">
									<label>Venta</label>
									<input type="text" placeholder="Venta" style="text-align: right;" class="form-control" id="venta" name="venta" value="" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group single-line">
									<label>Ingreso</label>
									<input type="text" placeholder="Ingreso" style="text-align: right;" class="form-control" id="ingreso" name="ingreso" value="" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group single-line">
									<label>Sucursal</label>
									<select class="select" style="width:100%" id="sucursal"  name="sucursal">
										<?php foreach ($sucur as $key): ?>
											<option <?php if($this->session->id_sucursal==$key->id){ echo " selected ";} ?> value="<?=$key->id?>"><?=$key->nombre?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group single-line">
									<label>Concepto</label>
									<textarea class="form-control" id="concepto" name="concepto" rows="1" ></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="loader" hidden></div>
								<button class="btn btn-primary " type="button" id="guardar" name="guardar"> <i class="fa fa-save"></i>  Agregar</button>
								<input type="hidden" id="idl" name="idl" value="0">
								<button class="btn btn-info oi" disabled type="button" id="editar" name="editar"> <i class="fa fa-edit"></i>  Editar</button>
							</div>
						</div>

				</div>
				<div class="ibox-title">
					<h3 class="text-navy"><b><i class="fa fa-1x<?=$icono?>"></i> <?="Datos ingresados Previamente"?></b></h3>
				</div>
				<div class="ibox-content">
					<form  target="_blank" action="<?=base_url("Libreta/report");?>" method="post">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group single-line">
								<label>Inicio</label>
								<input type="text"  style="text-align: center;" class="form-control datepicker" readonly id="inicio" name="inicio" value="<?=$ini?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group single-line">
								<label>Fin</label>
								<input type="text"  style="text-align: center;" class="form-control datepicker" readonly id="fin" name="fin" value="<?=$fin ?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group single-line">
								<label>Sucursal</label>
								<select class="selec" style="width:100%" id="idsucursal"  name="idsucursal">
									<?php foreach ($sucur as $key): ?>
										<option <?php if($this->session->id_sucursal==$key->id){ echo " selected ";} ?> value="<?=$key->id?>"><?=$key->nombre?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group single-line">
								<label>Imprimir</label>
								<button style="height:28px; font-size:12px;" class="btn btn-primary form-control"> <i class="fa fa-file-pdf-o"></i> PDF</button>
							</div>
						</div>
					</div>
				</form>
					<div class="row">
						<div class="col-lg-12">
							<table class="table" id="editable2">
								<thead>
									<th class="col-lg-1">id</th>
									<th>Fecha</th>
									<th>Descripción</th>
									<th>Venta</th>
									<th>Ingreso</th>
									<!--
									<th>Acumulado</th>
								-->
									<th class="col-lg-2">Acción</th>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url(); ?>assets/js/funciones/funciones_libreta.js?<?=rand(1,999) ?>"></script>
