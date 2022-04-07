<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-2">
	</div>
</div>

<div class="wrapper wrapper-content  animated fadeInRight">
 	<div class="row">
	 	<div class="col-lg-12">
		 	<div class="ibox ">
			 	<div class="ibox-title">
			 		<h3 class="text-navy"><b><i class="fa fa-files-o fa-1x"></i> Reporte de Examenes Pendientes</b></h3>
				 </div>
				<div class="ibox-content">
				 	<div class="row">
			 			<div class="col-md-6">
						 	<div class="form-group has-info single-line">
								 <label>Fecha Inicio</label>
								 <input type="text" class="form-control datepicker" id="fecha1" name="fecha1" value="2019-01-02">
						 	</div>
						</div>
						 <div class="col-md-6">
						 	<div class="form-group has-info single-line">
						 		<label>Fecha Fin</label>
							 	<input type="text" class="form-control datepicker" id="fecha2" name="fecha2" value="2020-01-02">
						 	</div>
						 </div>
				 	</div>
				 	<input type="hidden" name="process" id="process" value="edit">
				 	<div class="row">
				 		<div class="col-md-12">
					 		<div class="form-group">
						 		<input type="submit" id="xls" name="xls" value="EXCEL" class="btn btn-primary m-t-n-xs pull-right">
						 		<span class="pull-right">&nbsp;</span>
							 	<input type="submit" id="submit1" name="submit1" value="Imprimir" class="btn btn-primary m-t-n-xs pull-right">
							</div>
					 	</div>
				 	</div>
			 	</div>
		 	</div>
	 	</div>
	 </div>
</div>
<script src="<?= base_url(); ?>assets/js/funciones/<?=$urljs; ?>"></script>

