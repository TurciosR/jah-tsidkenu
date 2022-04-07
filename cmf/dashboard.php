<?php
include_once "_core.php";
// Page setup
$_PAGE = array();
$_PAGE['title'] = 'Dashboard';
$_PAGE['links'] = null;
$_PAGE['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/animate.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/style.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/plugins/perfect-scrollbar/perfect-scrollbar.css">';
$_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/util.css">';
$_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/main.css">';

include_once "header.php";
include_once "main_menu.php";
//permiso del script
$id_user=$_SESSION["id_usuario"];
$id_sucursal = $_SESSION["id_sucursal"];
$admin=$_SESSION["admin"];
//permiso del script

	$id_sucursal=$_SESSION["id_sucursal"];
	$qsucursal=_query("SELECT descripcion FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$row_sucursal=_fetch_array($qsucursal);
	$sucursal=$row_sucursal["descripcion"];
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="wrapper wrapper-content">
				<div class="row">
					<?php if ($admin=='1' ){ ?>
					<div class="col-lg-3">
						<a href="admin_producto.php">
							<div class="widget style1 navy-bg">
								<div class="row">
									<div class="col-xs-4">
										<i class="fa fa-archive fa-5x"></i>
									</div>
									<div class="col-xs-8 text-right">
										<span> Gestionar </span>
										<h2 class="font-bold">Productos</h2>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3">
						<a href="admin_proveedor.php">
							<div class="widget style1 lazur-bg">
								<div class="row">
									<div class="col-xs-4">
										<i class="fa fa-truck fa-5x"></i>
									</div>
									<div class="col-xs-8 text-right">
										<span> Proveedores</span>
										<h2 class="font-bold">Gestionar</h2>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3">
						<a href="admin_cliente.php">
							<div class="widget style1 navy-bg">
								<div class="row">
									<div class="col-xs-4">
										<i class="fa fa-briefcase fa-5x"></i>
									</div>
									<div class="col-xs-8 text-right">
										<span>Gestionar</span>
										<h2 class="font-bold">Clientes</h2>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3">
						<a href="venta.php">
							<div class="widget style1 yellow-bg">
								<div class="row">
									<div class="col-xs-4">
										<i class="fa fa-shopping-cart fa-5x"></i>
									</div>
									<div class="col-xs-8 text-right">
										<span> Punto de Venta </span>
										<h2 class="font-bold">Factura</h2>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3">
						<a href="admin_credito.php">
							<div class="widget style1 navy-bg">
								<div class="row">
									<div class="col-xs-4">
										<i class="fa fa-credit-card fa-5x"></i>
									</div>
									<div class="col-xs-8 text-right">
										<span> Cuentas por Cobrar </span>
										<h2 class="font-bold">Gestionar</h2>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3">
						<a href="admin_voucher.php">
							<div class="widget style1 yellow-bg">
								<div class="row">
									<div class="col-xs-4">
										<i class="fa fa-balance-scale fa-5x"></i>
									</div>
									<div class="col-xs-8 text-right">
										<span> Cuentas por Pagar </span>
										<h2 class="font-bold">Gestionar</h2>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3">
						<a href="admin_stock.php">
							<div class="widget style1 lazur-bg">
								<div class="row">
									<div class="col-xs-4">
										<i class="fa fa-table fa-5x"></i>
									</div>
									<div class="col-xs-8 text-right">
										<span> Consultar</span>
										<h2 class="font-bold"> Stock</h2>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-3">
						<a href="admin_movimiento_caja.php">
							<div class="widget style1 lazur-bg">
								<div class="row">
									<div class="col-xs-4">
										<i class="fa fa-money fa-5x"></i>
									</div>
									<div class="col-xs-8 text-right">
										<span> Movimientos de Caja</span>
										<h2 class="font-bold"> Gestionar</h2>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5 style="color:#000;">Productos mas Vendidos</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up" style="color:#000;"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
								<div>
									<canvas id="myChart" style="width: 495px; height: 250px;"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5 style="color:#000;">Ventas Por Mes</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up" style="color:#000;"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
								<div>
									<canvas id="myChart1" style="width: 495px; height: 250px;"></canvas>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5 style="color:#000;"> Venta diaria por Vendedor</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up" style="color:#000;"></i>
									</a>
								</div>
								<span class="label label-info pull-right">Diario</span>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
								<div>
									<table class="table">
										<?php
										$fecha_actual = date("Y-m-d");
										$sql="SELECT DISTINCT factura.id_empleado,usuario.usuario, empleado.nombre FROM factura JOIN usuario ON usuario.id_usuario=factura.id_empleado LEFT JOIN empleado ON empleado.id_empleado=usuario.id_empleado WHERE factura.fecha='$fecha_actual'";

										$result=_query($sql);
										$cuenta = _num_rows($result);
										echo _error();
										if($cuenta > 0)
										{
											while ($row = _fetch_array($result))
											{
												$id_empleado = $row["id_empleado"];
												$nombre = $row["nombre"];
												if ($nombre=='') {
													// code...
													$nombre=$row["usuario"];
												}
												$sql_monto = _query("SELECT SUM(total) as total FROM factura WHERE id_sucursal=$id_sucursal AND id_empleado = '$id_empleado' AND fecha = '$fecha_actual' AND anulada = 0 AND finalizada = 1 AND caja!=0 AND credito=0");
												//echo "SELECT SUM(subtotal) as monto FROM factura_detalle WHERE id_empleado = '$id_empleado' AND fecha = '$fecha_actual'";

												$row_monto = _fetch_array($sql_monto);
												$monto_total = $row_monto["total"];
												if($monto_total > 0)
												{
													$monto_total = number_format($monto_total,2);
												}
												else
												{
													$monto_total = "0.00";
												}

												echo "<tr>";
												echo "<td class='text-bluegrey'>".$nombre."</td>";
												echo "<td class='text-danger'>$".$monto_total."</td>";
												echo "</tr>";

											}
										}
										?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5 style="color:#000;"> Inversión Actual</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up" style="color:#000;"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
								<div>
									<table class="table">
										<?php
										$fecha_actual = date("Y-m-d");
										$sql="SELECT SUM(stock.stock*stock.precio_unitario) AS inversion FROM stock INNER JOIN presentacion_producto ON presentacion_producto.id_producto=stock.id_producto WHERE stock.id_sucursal=$_SESSION[id_sucursal] AND presentacion_producto.unidad=1 AND presentacion_producto.id_sucursal=$_SESSION[id_sucursal]";

										$result=_query($sql);
										$cuenta = _num_rows($result);
										echo _error();
										if($cuenta > 0)
										{
											while ($row = _fetch_array($result))
											{
												?>

												<h1 class="no-margins"><?php echo "$ ".number_format($row['inversion'],2);?></h1>

												<small>Total Inversión</small>
												<?php
											}
										}
										?>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5 style="color:#000;"> Ganancias Brutas</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up" style="color:#000;"></i>
									</a>
								</div>
								<span class="label label-info pull-right">Diario</span>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
								<div>
									<table class="table">
										<?php
										$hoy=date("Y-m-d");

										$sql="SELECT
										SUM((movimiento_producto_detalle.cantidad/presentacion_producto.unidad) * movimiento_producto_detalle.costo) AS costo,
										SUM((movimiento_producto_detalle.cantidad/presentacion_producto.unidad) * movimiento_producto_detalle.precio) AS venta
										FROM factura
										JOIN movimiento_producto ON factura.id_factura=movimiento_producto.id_factura
										JOIN movimiento_producto_detalle ON movimiento_producto_detalle.id_movimiento=movimiento_producto.id_movimiento
										JOIN presentacion_producto ON presentacion_producto.id_presentacion=movimiento_producto_detalle.id_presentacion
										WHERE factura.anulada=0 AND factura.finalizada=1 AND factura.id_sucursal=$_SESSION[id_sucursal]
										AND factura.fecha ='$hoy' ";


										$result=_query($sql);
										$cuenta = _num_rows($result);
										echo _error();
										if($cuenta > 0)
										{
											while ($row = _fetch_array($result))
											{
												$costo=$row['costo'];
												$costo=round($costo,2);
												$venta=$row['venta'];
												$venta=round($venta,2);

												$ganacia=$venta-$costo;
												$ganacia=round($ganacia,2);

												?>

												<h1 class="no-margins"><?php echo "$ ".number_format($ganacia,2);?></h1>

												<small>Ganancias antes de impuestos y gastos</small>
												<?php
											}
										}
										?>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5 style="color:#000;"> Ganancias Brutas</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up" style="color:#000;"></i>
									</a>
								</div>
								<span class="label label-primary pull-right">Mensual</span>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
								<div>
									<table class="table">
										<?php
										$m = date("m");
										$y = date("Y");
										$ini = $y."-".$m."-01";
										$ult = cal_days_in_month(CAL_GREGORIAN, $m, $y);
										$fin = $y."-".$m."-".$ult;

										$sql="SELECT
										SUM((movimiento_producto_detalle.cantidad/presentacion_producto.unidad) * movimiento_producto_detalle.costo) AS costo,
										SUM((movimiento_producto_detalle.cantidad/presentacion_producto.unidad) * movimiento_producto_detalle.precio) AS venta
										FROM factura
										JOIN movimiento_producto ON factura.id_factura=movimiento_producto.id_factura
										JOIN movimiento_producto_detalle ON movimiento_producto_detalle.id_movimiento=movimiento_producto.id_movimiento
										JOIN presentacion_producto ON presentacion_producto.id_presentacion=movimiento_producto_detalle.id_presentacion
										WHERE factura.anulada=0 AND factura.finalizada=1 AND factura.id_sucursal=$_SESSION[id_sucursal]
										AND factura.fecha BETWEEN '$ini' AND '$fin' ";


										$result=_query($sql);
										$cuenta = _num_rows($result);
										echo _error();
										if($cuenta > 0)
										{
											while ($row = _fetch_array($result))
											{
												$costo=$row['costo'];
												$costo=round($costo,2);
												$venta=$row['venta'];
												$venta=round($venta,2);

												$ganacia=$venta-$costo;
												$ganacia=round($ganacia,2);

												?>

												<h1 class="no-margins"><?php echo "$ ".number_format($ganacia,2);?></h1>

												<small>Ganancias antes de impuestos y gastos</small>
												<?php
											}
										}
										?>
									</table>
								</div>
							</div>

						</div>
					</div>


				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5 style="color:#000;"> Referencias Pendientes</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up" style="color:#000;"></i>
									</a>
								</div>
								<span class="label label-info pull-right">Diario</span>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
								<div>
									<table class="table">
										<tr>
											<td>CLIENTE</td>
											<td>MONTO</td>
											<td>REFERENCIA</td>
										</tr>
										<?php
										$fecha_actual = date("Y-m-d");
										$sql="SELECT cliente.nombre, factura.total,numero_ref FROM factura JOIN cliente ON cliente.id_cliente=factura.id_cliente WHERE numero_ref!=0 AND fecha='".date("Y-m-d")."' AND finalizada!=1 AND factura.id_sucursal=1 LIMIT 5";

										$result=_query($sql);
										$cuenta = _num_rows($result);
										echo _error();
										if($cuenta > 0)
										{
											while ($row = _fetch_array($result))
											{
												echo "<tr>";
												echo "<td class='text-bluegrey'>".utf8_decode(Mayu(utf8_decode($row['nombre'])))."</td>";
												echo "<td class='text-danger'>$".number_format($row['total'],2)."</td>";
												echo "<td class='text-danger'>".$row['numero_ref']."</td>";
												echo "</tr>";

											}
										}
										?>
									</table>
								</div>
							</div>
							<div class="ibox-content">
								<a href="admin_pendiente_rangos.php"> <span class="fa fa-plus"></span> Ver mas</a>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5 style="color:#000;">Saldo en caja</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up" style="color:#000;"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px; display: block;">
								<div>
									<table class="table">
										<tbody>
										<?php
										$id_sucursal=$_SESSION['id_sucursal'];

										$hoy=date("Y-m-d");
										$sql=_query("SELECT apertura_caja.caja,apertura_caja.id_apertura,apertura_caja.hora,apertura_caja.monto_ch,apertura_caja.monto_apertura  FROM apertura_caja WHERE apertura_caja.fecha='$hoy' AND apertura_caja.vigente=1 AND apertura_caja.id_sucursal=$_SESSION[id_sucursal]");

										$num_rows=_num_rows($sql);

										if ($num_rows>0) {
											# code...
											while ($row=_fetch_array($sql)) {
												# code...
												$monto_ch=$row['monto_ch'];
												$monto_apertura=$row['monto_apertura'];
												$aper_id=$row['id_apertura'];
												$hora_apertura=$row['hora'];
												$hora_actual=date("H:i:s");
												$id_apertura=$aper_id;
												$fecha_apertura=$hoy;
												$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.total) AS total_devoluciones FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$aper_id"));
												$monto_dev=$sql_monto_dev['total_devoluciones'];
												$monto_dev=round($monto_dev,2);


												$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.total) AS total_devoluciones FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$aper_id"));
												$monto_nc=$sql_monto_dev['total_devoluciones'];
												$monto_nc=round($monto_nc,2);

												$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.retencion) AS total_retencion FROM factura WHERE id_apertura_pagada=$aper_id AND credito=0"));
												$monto_retencion=$sql_monto_dev['total_retencion'];
												$monto_retencion=round($monto_retencion,2);

												$sql_caja = _query("SELECT * FROM mov_caja WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal'");
												$cuenta_caja = _num_rows($sql_caja);
												$total_entrada_caja=0;
												$total_salida_caja=0;
												if($cuenta_caja > 0)
												{
													while ($row_caja = _fetch_array($sql_caja))
													{
														$monto = $row_caja["valor"];
														$entrada = $row_caja["entrada"];
														$salida = $row_caja["salida"];

														if($entrada == 1 && $salida == 0)
														{
															$total_entrada_caja += $monto;
														}
														else if($salida == 1 && $entrada == 0)
														{
															$total_salida_caja += $monto;
														}
													}
												}

												$total_tike_2 = 0;
												$total_factura_2 = 0;
												$total_credito_fiscal_2 = 0;

												$total_contado_2 = 0;
												$total_transferencia_2 = 0;
												$total_cheque_2 = 0;

												$t_tike_2 = 0;
												$t_factuta_2 = 0;
												$t_credito_2 = 0;
												$sql_corte_caja = _query("SELECT * FROM factura WHERE fecha = '$fecha_apertura' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND finalizada = 1 AND credito=0 AND id_apertura_pagada ='$id_apertura'");
												$cuenta_caja = _num_rows($sql_corte_caja);
												if($cuenta_caja > 0)
												{
													while ($row_corte = _fetch_array($sql_corte_caja))
													{
														$id_factura = $row_corte["id_factura"];
														$anulada = $row_corte["anulada"];
														$subtotal = $row_corte["subtotal"];
														$suma = $row_corte["sumas"];
														$iva = $row_corte["iva"];
														$total = $row_corte["total"];
														$numero_doc = $row_corte["numero_doc"];
														$tipo_pago = $row_corte["credito"];
														$pagada = $row_corte["finalizada"];
														$tipo_documento = $row_corte["tipo_documento"];




														if($tipo_documento == 'TIK')
														{
															$total_tike_2 += $total;

															$t_tike_2 += 1;
														}
														else if($tipo_documento == 'COF')
														{
															$total_factura_2 += $total;

															$t_factuta_2 += 1;
														}
														else if($tipo_documento == 'CCF')
														{
															$total_credito_fiscal_2 += $total;

															$t_credito_2 += 1;
														}
													}
												}
												////////////////////////////////////////////////////////////////////////////////////////////////////////
												//$total_devolucion = $total_dev_g + $total_dev_e;

												$total_corte_2 = $total_tike_2 + $total_factura_2 + $total_credito_fiscal_2 + $monto_apertura + $total_entrada_caja - $total_salida_caja + $monto_ch-round($monto_dev,2)-round($monto_nc,2)-$monto_retencion;

												echo"
												<tr>
													<td class='text-bluegrey'>CAJA 1</td>
													<td class='text-danger'>  ".number_format($total_corte_2,2)."</td>
												</tr>";
											}
										}
										 ?>


										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<div class="row">
					<div class="col-lg-12">


						<div class="col-md-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Listado de productos a experirar en losproximos 3 meses</h5>
									<div class="ibox-tools">
										<a class="collapse-link">
											<i class="fa fa-chevron-up"></i>
										</a>
										<a class="close-link">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="ibox-content">
									<table class="table table-hover no-margins">
										<thead>
											<tr>
												<th>Producto</th>
												<th>Meses vencimiento Proveedor e inicio  recogida</th>
												<th>Existencias</th>
												<th>Fecha Caduca</th>

											</tr>
										</thead>
										<tbody>

											<?php
											$hoy=date("Y-m-d");
											$man=sumar_meses($hoy,3);
											$sql=_query("SELECT producto.descripcion,lote.cantidad,lote.vencimiento,proveedor.vencimienttos FROM lote JOIN producto ON lote.id_producto=producto.id_producto JOIN proveedor ON proveedor.id_proveedor=producto.id_proveedor WHERE lote.id_sucursal=$id_sucursal AND lote.estado='VIGENTE' AND lote.cantidad>0 AND vencimiento BETWEEN '$hoy' AND '$man'");

											while($ra=_fetch_array($sql))
											{

											?>
											<tr>
												<td><?php echo $ra['descripcion']; ?></td>
												<td><?php echo "INICIO RECOGIDA: ".restar_meses($ra['vencimiento'],$ra['vencimienttos'])." ($ra[vencimienttos] meses)";  ?></td>
												<td><?php echo number_format($ra['cantidad'],0,"",""); ?></td>
												<td><span class="label label-danger"><?php echo ED($ra['vencimiento']) ?></span></td>
											</tr>
											<?php
											}
											 ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Listado de productos vencidos</h5>
									<div class="ibox-tools">
										<a class="collapse-link">
											<i class="fa fa-chevron-up"></i>
										</a>
										<a class="close-link">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="ibox-content">
									<table class="table table-hover no-margins">
										<thead>
											<tr>
												<th>Producto</th>
												<th>Existencias</th>
												<th>Fecha Caduca</th>

											</tr>
										</thead>
										<tbody>

											<?php
											$hoy=date("Y-m-d");
											$man=sumar_meses($hoy,3);
											$sql=_query("SELECT producto.descripcion,lote.cantidad,lote.vencimiento FROM lote JOIN producto WHERE lote.id_sucursal=$id_sucursal AND lote.id_producto=producto.id_producto AND lote.cantidad>0 AND lote.estado='VIGENTE' AND vencimiento < '$hoy' AND vencimiento != '0000-00-00'");

											while($ra=_fetch_array($sql))
											{



											?>
											<tr><td><?php echo $ra['descripcion']; ?></td><td><?php echo number_format($ra['cantidad'],0,"",""); ?></td><td><span class="label label-danger"><?php echo ED($ra['vencimiento']) ?></span></td></tr>
											<?php
											}
											 ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Listado de productos para Reposición</h5>
									<div class="ibox-tools">
										<a class="collapse-link">
											<i class="fa fa-chevron-up"></i>
										</a>
										<a class="close-link">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="ibox-content">
									<table class="table table-hover no-margins">
										<thead>
											<tr>
												<th>Producto</th>
												<th>Existencia Minima</th>
												<th>Existencia Actual</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql=_query("SELECT producto.descripcion,producto.minimo,stock.stock FROM stock JOIN producto WHERE stock.id_sucursal=$id_sucursal AND stock.id_producto=producto.id_producto AND stock.stock<producto.minimo");

											while($ra=_fetch_array($sql))
											{
											?>
											<tr><td><?php echo $ra['descripcion']; ?></td><td><?php echo $ra['minimo'] ?></td><td><span class="label label-danger"><?php echo number_format($ra['stock'],0,"",""); ?></span></td></tr>
											<?php
											}
											 ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	<?php
include("footer.php");
echo "<script src='js/funciones/funciones_dashboard.js'></script>";
?>
