<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox" id="main_view">
				<div class="ibox-title">
					<h3 class="text-success"><b><i class="mdi mdi-plus"></i> Agregar Cliente</b></h3>
                    <p>Los campos con <span class="text-danger">*</span> son requeridos.</p>
				</div>
				<div class="ibox-content">
					<form id="form_add" novalidate>
						<div class="row">
							<div class="col-lg-6" hidden>
								<div class="form-group single-line">
									<label for="nombre">Razon Social<span class="text-danger">*</span></label>
									<input type="text" name="nombre" id="nombre" class="form-control mayu"  placeholder="Ingrese un nombre"
										   data-parsley-trigger="change">
								</div>
							</div>
						</div>
                        <div class="row">
													<div class="col-lg-6">
														<div class="form-group single-line">
															<label for="nombre_comercial">Nombre<span class="text-danger">*</span></label>
															<input type="text" name="nombre_comercial" id="nombre_comercial" class="form-control mayu"  placeholder="Ingrese un nombre"
																   required data-parsley-trigger="change">
														</div>
													</div>
                            <div class="col-lg-6">
                                <div class="form-group single-line">
                                    <label for="direccion">Dirección<span class="text-danger"></span></label>
                                    <input type="text" name="direccion" id="direccion" class="form-control"  placeholder="Ingrese una direccion" data-parsley-trigger="change">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="departamento">Departamento<span class="text-danger"></span></label>
                                    <select name="departamento" id="departamento" class="form-control select2">
                                        <option value="0">Seleccione un departamento</option>
                                        <?php foreach ($departamentos as $dep): ?>
                                            <option value="<?=$dep->id_departamento?>"><?=$dep->nombre?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="municipio">Municipio<span class="text-danger"></span></label>
                                    <select name="municipio" id="municipio" class="form-control select2" >
                                        <option value="0">Seleccione un municipio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="dui">DUI</label>
                                    <input type="text" name="dui" id="dui" class="form-control dui"  placeholder="Ingrese una direccion">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="nit">NIT<span class="text-danger"></span></label>
                                    <input type="text" name="nit" id="nit" class="form-control nit"  placeholder="Ingrese una direccion" data-parsley-trigger="change">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="nrc">NRC <span class="text-danger"></span></label>
                                    <input type="text" name="nrc" id="nrc" class="form-control"  placeholder="Ingrese el nrc" data-parsley-trigger="change">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="giro">Giro<span class="text-danger"></span></label>
                                    <select name="giro" id="giro" class="form-control select2" >
                                        <option value="0">Seleccione un giro</option>
                                        <?php foreach ($giro as $gir): ?>
                                            <option value="<?=$gir->id_giro?>"><?=$gir->descripcion?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3" hidden>
                                <div class="form-group single-line">
                                    <label for="categoria">Categoria <span class="text-danger">*</span></label>
                                    <select name="categoria" id="categoria" class="form-control select2" >
                                        <option value="0">Seleccione una categoria</option>
                                        <?php foreach ($categoria_cliente as $cat): ?>
                                            <option value="<?=$cat->id_categoria?>"><?=$cat->nombre?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="tipo">Tipo de Cliente <span class="text-danger"></span></label>
                                    <select name="tipo" id="tipo" class="form-control select2" >
                                        <option value="0">Seleccione un tipo de cliente</option>
                                        <?php foreach ($tipo_cliente as $tipo): ?>
                                            <option value="<?=$tipo->id_tipo?>"><?=$tipo->descripcion?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
														<div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="telefono1">Telefono 1 <span class="text-danger"></span></label>
                                    <input type="text" name="telefono1" id="telefono1" class="form-control tel"  placeholder="Ingrese el numero de telefono 1" data-parsley-trigger="change">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="telefono2">Telefono 2</label>
                                    <input type="text" name="telefono2" id="telefono2" class="form-control tel"  placeholder="Ingrese el numero de telefono 1" >
                                </div>
                            </div>
                            <div class="col-lg-3" hidden>
                                <div class="form-group single-line">
                                    <label for="fax">Fax </label>
                                    <input type="text" name="fax" id="fax" class="form-control tel"  placeholder="Ingrese el numero de fax">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group single-line">
                                    <label for="correo">Correo Electronico </label>
                                    <input type="text" name="correo" id="correo" class="form-control"  placeholder="Ingrese un correo" >
                                </div>
                            </div>
														<div class="col-lg-3">
															 <div class="form-group single-line">
																	 <label for="tipo">Clasificación de Cliente <span class="text-danger">*</span></label>
																	 <select name="clasifica" id="clasifica" class="form-control select2" >
																			 <option value="0">Seleccione clasificaci&oacute;n de cliente</option>
																			 <?php foreach ($clasifica_cliente as $clasifica): ?>
																					 <option value="<?=$clasifica->id?>"><?=$clasifica->descripcion?></option>
																			 <?php endforeach; ?>
																	 </select>
															 </div>
													 </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3" hidden>
                                <div class="form-group single-line">
                                    <label for="descuento">Porcentaje de descuento<span class="text-danger">*</span></label>
                                    <select name="descuento" id="descuento" class="form-control select2" >
                                        <option value="0">Seleccione un porcentaje</option>
                                        <?php foreach ($porcentajes as $porc): ?>
                                            <option value="<?=$porc->id_porcentaje?>"><?=$porc->descripcion?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3" hidden>
                                <div class="form-group single-line">
                                    <label for="dias_credito">Dias Credito</label>
                                    <input type="text" name="dias_credito" id="dias_credito" class="form-control "  placeholder="Ingrese el numero de telefono 1" >
                                </div>
                            </div>
                            <div class="col-lg-3" hidden>
                                <div class="form-group single-line">
                                    <label for="tipo_documento">Tipo de Documento </label>
                                    <select name="tipo_documento" id="tipo_documento" class="form-control select2" >
                                        <option value="0">Seleccione un tipo de documento</option>
                                        <?php foreach ($tipo_doc as $tip): ?>
                                            <option value="<?=$tip->idtipodoc?>"><?=$tip->nombredoc?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group single-line">
                                    <label for="contacto">Nombre de contacto</label>
                                    <input type="text" name="contacto" id="contacto" class="form-control "  placeholder="Ingrese el nombre de contacto" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="contacto_telefono">Telefono de contacto </label>
                                    <input type="text" name="contacto_telefono" id="contacto_telefono" class="form-control tel"  placeholder="Ingrese el numero de telefono" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group single-line">
                                    <label for="contacto_correo">Correo de contacto </label>
                                    <input type="text" name="contacto_correo" id="contacto_correo" class="form-control"  placeholder="Ingrese el correo" >
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group single-line">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-actions col-lg-12">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" id="csrf_token_id">
                                <button type="submit" id="btn_add" name="btn_add" class="btn btn-success m-t-n-xs float-right"><i class="mdi mdi-content-save"></i>
                                    Guardar Registro
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
<div class='modal fade inmodal' id='viewModal' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog modal-md'>
		<div class='modal-content modal-md'>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
