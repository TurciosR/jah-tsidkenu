<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox" id="main_view">
				<div class="ibox-title">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 class="text-navy"><b><i class="fa fa-eye"></i> Ver Detalle</b></h3>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form name="formulario_edit" id="formulario_edit">
							<div class="col-lg-12">
								<table class="table table-bordered table-striped" id="tableview">
									<thead>
										<tr>
											<th class="col-md-1 text-success font-bold sorting_asc">Campo</th>
											<th class="col-md-1 text-success font-bold sorting_asc">Descripción</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($data as $key => $value) {
											echo "<tr><td class='col-lg-4'>$key</td><td class='col-lg-8'>$value</td></tr>";
										}
										 ?>

										</tbody>
								</table>
							</div>

							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<!--<script src="<?/*= base_url(); */?>assets/js/funciones/<?/*=$urljs*/?>"></script>-->
<script>
$('.select').select2({
	dropdownParent: $("#viewModal")
});
</script>
