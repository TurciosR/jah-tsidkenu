<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox" id="main_view">
				<div class="ibox-title">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 class="text-navy"><b><i class="fa fa-plus-circle"></i> SALIDA DE ARO</b></h3>
				</div>
				<div class="ibox-content">
					<div class="row " >
						<div  class="form-group col-md-6">
							<label>CODIGO</label>
                            <input type="text"  class="form-control"   value="<?= $codigo?>"  readonly>
                        </div>
                        <div  class="form-group col-md-6">
							<label>CANTIDAD</label>
                            <input type="text"   id="cantidad" name="cantidad" class="form-control"   value=""  >
                        </div>
                        <div  class="form-group col-md-12">
							<label>MOTIVO</label>
                            <textarea name="motivo"  class="form-control" id="motivo" cols="20" rows="4"></textarea>
                        </div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
                                <div class="form-actions col-lg-10">
                                    <button type="button" data-dismiss="modal" class="btn btn-danger m-t-n-xs pull-right"> Cerrar</button>
                                </div>
                                <div class="form-actions col-lg-2">
                                    <button type="button" id="btn_gdr" class="btn btn-primary m-t-n-xs pull-right"> Guardar</button>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
