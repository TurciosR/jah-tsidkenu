<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox" id="main_view">
				<div class="ibox-title">
					<h3 class="text-success"><b><i class="mdi mdi-plus"></i> Reportes</b></h3>
				</div>
				<div class="ibox-content">
					<form id="form_add" novalidate>
						<div class="row">
							<div class="col-lg-2">
								<div class="form-group">
									<label for="nombre">Tipo</label><br>
									<input checked type="radio" id="checkboxConsolidado" name="checkboxTipo"> <label for="checkboxConsolidado">Consolidado</label><br>
									<input type="radio" id="checkboxCategoria" name="checkboxTipo"> <label for="checkboxCategoria">Por categoria</label>
								</div>
							</div>
							<div class="col-lg-2">
								<div class="form-group">
									<label for="nombre">Costos</label><br>
									<input checked type="radio" id="checkboxCostos" name="checkboxCostos" value="1"> <label for="checkboxCostos">Mostrar</label><br>
									<input type="radio" id="checkboxNoCostos" name="checkboxCostos" value="0"> <label for="checkboxNoCostos">No mostrar</label>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<label for="nombre">Reporte</label>
									<select class="select2" style="width:100%" name="" id="reportes">
										<option value="1">Reporte de Existencias</option>
									</select>
								</div>
							</div>
							<div class="col-lg-3">
								<label for="nombre">Sucursal</label>
								<select data-parsley-trigger="change" style="width:100%" required class="select2" id="sucursal" name="sucursal">
								<?php foreach ($sucursal as $key): ?>
									<option <?php if($id_sucursal==$key->id_sucursal){echo "selected";} ?> value="<?=$key->id_sucursal ?>"><?=$key->nombre." ".$key->direccion ?></option>
								<?php endforeach; ?>
								</select>
							</div>
							<div class="col-lg-2 contenedor_categoria" hidden>
								<label for="">Categoria</label>
								<select name="selectCategoria" id="selectCategoria" class="select2">
									<option value="0">Seleccione...</option>
									<?php
										foreach ($categorias as $arrCategoria) {
											?>
												<option value="<?=$arrCategoria->id_categoria?>"><?=$arrCategoria->nombre?></option>
											<?php
										}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-actions col-lg-2">
								<label for="">Accion</label><br>
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" id="csrf_token_id">
								<button type="button" id="generarReporteExist" name="btn_add" class="btn btn-success m-t-n-xs pull-right" style="width:100%;"><i class="mdi mdi-content-save"></i>
								Generar Reporte
								</button>
							</div>
						</div>
					</form>
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
