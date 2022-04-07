<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>



<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox" id="main_view">
				<div class="ibox-title">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 class="text-navy"><b><i class="fa fa-plus-circle"></i> Editar Coordinacion</b></h3>
				</div>
				<div class="ibox-content">

					<div class="row">
						<form name="formEdit" id="formEdit">
							<div class="col-lg-12">
								<div class="form-group col-lg-8">
									<label>Nombre de Coordinacion</label>
									<input type="text" class="form-control" id="nombre_unidad" name="nombre_unidad" value="<?= $nombre ?>">
								</div>
								<div class="form-group col-lg-4">
									<label>Estado de Coordinacion</label>
									<select class="form-control select" name="estado_unidad" id="estado_unidad">
										<?php foreach ($options as $option):?>
											<option value="<?=$option['value'] ?>" data-description="<?=$option['text'] ?>"
											<?php if ($option['selected'] == 1): ?>
												<?= "selected" ?>
											<?php endif; ?>
											> <?= $option['text'] ?> </option>
										<?php endforeach;?>
									</select>

								</div>

								<div class="row">
									<div class="form-actions col-lg-12">
										<input type="hidden" class="form-control" id="id" name="id" value="<?= $id_unidad ?>">
										<button type="submit" id="editarUnidad" class="btn btn-primary m-t-n-xs pull-right"> Guardar Cambios</button>
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

<script src="<?= base_url(); ?>assets/js/funciones/<?=$urljs;?>"></script>
<script>
    $('.select').select2({
        dropdownParent: $("#viewModal")
	});
    $("#estado_unidad").change(function () {
        if ($(this).val() != "") {
            $("#infos").text($("#estado_unidad option:selected").attr("data-description"));
        } else {
            $("#infos").text("");
        }
    });
</script>
