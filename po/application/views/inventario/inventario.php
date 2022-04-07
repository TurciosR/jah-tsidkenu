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
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox ">
				<div class="ibox-title">
					<h3 class="text-navy"><b><i class="fa fa-1x<?=$icono?>"></i> <?=$nombre_archivo?></b></h3>
				</div>
				<div class="ibox-content">
					<form  target="_blank" action="<?=base_url("Inventario/report");?>" method="post">
					<div class="row">
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
								<label>Mostrar</label>
								<select class="selec" style="width:100%" id="mostrar"  name="mostrar">
									<option value="1">Con EXISTENCIAS</option>
									<option value="0">Todos</option>
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
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#idsucursal").select2();
	$("#mostrar").select2();
</script>
