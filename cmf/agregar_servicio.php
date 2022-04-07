<?php
include ("_core.php");
include ('num2letras.php');
include ('facturacion_funcion_imprimir.php');
function initial(){
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$id_sucursal=$_SESSION['id_sucursal'];
	date_default_timezone_set('America/El_Salvador');
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//permiso del script

	//include ('facturacion_funcion_imprimir.php');
	//$sql="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND id_empleado=$id_user");
	$cuenta = _num_rows($sql_apertura);
	$row_apertura = _fetch_array($sql_apertura);
	$id_apertura = $row_apertura["id_apertura"];
	$empleado = $row_apertura["id_empleado"];
	$turno = $row_apertura["turno"];
	$fecha_apertura = $row_apertura["fecha"];
	$hora_apertura = $row_apertura["hora"];
	$monto_apertura = $row_apertura["monto_apertura"];

	$hora_actual = date('H:i:s');
	if($cuenta > 0)
	{
		?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
			<h4 class="modal-title">Agregar Servicio</h4>
		</div>
		<div class="modal-body">
			<!--div class="wrapper wrapper-content  animated fadeInRight"-->
			<div class="row" id="row1">
				<!--div class="col-lg-12"-->
				<?php

				?>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-info single-line">
							<label>Servicio</label>
							<select class="idServicio" style="width: 100%" id="idServicio" name="idServicio">
								<?php
								$sql=_query("SELECT * FROM servicios");

								while($row=_fetch_array($sql))
								{
									echo"<option value='$row[id_servicio]'>$row[servicio]</option>";
								}
								 ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-info single-line">
							<label>Monto </label> <input type='text'  class='form-control numeric montoServicio' id='montoServicio' name='montoServicio'>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button"  class="btn btn-primary btnServicio" id="btnServicio">Agregar</button>
			<button type="button" class="btn btn-default closeServicio" data-dismiss="modal">Cerrar</button>
		</div>
		<script type="text/javascript">
		$(".numeric").numeric(
			{
				negative:false,
			}
		);
		$(".idServicio").select2();
	</script>
	<!--/modal-footer -->

	<?php

}
else
{
	echo "<div></div><br><br><div class='alert alert-warning text-center'>No se ha encontrado una apertura vigente.</div>";
}
}


if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'formDelete' :
				initial();
				break;
			}
		}
	}

	?>
