	<?php
	include ("_core.php");
	$admin = $_SESSION["admin"];
		$id_pedido = $_REQUEST['id_pedido'];
		$id_sucur = $_SESSION['id_sucursal'];
		$id_user=$_SESSION["id_usuario"];
		$sql="SELECT pedido.*, cliente.nombre FROM pedido, cliente WHERE pedido.id_cliente=cliente.id_cliente AND pedido.id_sucursal='$id_sucur' AND pedido.id_pedido='$id_pedido' ORDER BY 'PENDIENTE'";
		$result = _query($sql);
		$row = _fetch_array($result);
		$cliente = $row["nombre"];
		$fecha = $row["fecha"];
		$fecha2 = $row["fecha_entrega"];
		$lugar = $row["lugar_entrega"];
		$total = $row["total"];
		$uri = $_SERVER['SCRIPT_NAME'];
		$filename=get_name_script($uri);
		$links=permission_usr($id_user,$filename);
	?>
	<?php if($links!='NOT' || $admin == '1' ){ ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">DETALLES DE PEDIDO</h4>
	</div>
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<label>Cliente:</label>
						<input type="text" name="fecha" value="<?php echo $cliente; ?>" class="form-control" readOnly>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label>Lugar de entrega:</label>
						<input type="text" name="fecha" value="<?php echo $lugar; ?>" class="form-control" readOnly>
					</div>
				</div>
			</div>
			<div class="row">
			<div class="col-lg-6">
					<div class="form-group">
						<label>Fecha:</label>
						<input type="text" name="fecha" value="<?php echo $fecha; ?>" class="form-control" readOnly>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Fecha de entrega:</label>
						<input type="text" name="fecha" value="<?php echo $fecha2; ?>" class="form-control" readOnly>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<table class="table table-hover table-bordered" id="tabla_modal">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Presentación</th>
							<th>Descripción</th>
							<th>Cant</th>
							<th>Enviado</th>
							<th>Pre. V</th>
							<th>Subtotal</th>
						</tr>
					</thead>
						<tbody>
						<?php
	           $sql_prese=_query("SELECT producto.id_producto, producto.descripcion AS producto, presentacion.nombre,presentacion_producto.id_presentacion ,presentacion_producto.descripcion, presentacion_producto.unidad ,pedido_detalle.id_pedido_detalle,pedido_detalle.precio_venta, pedido_detalle.cantidad,pedido_detalle.cantidad_enviar, pedido_detalle.subtotal, stock.stock
							 FROM pedido_detalle
							 JOIN producto ON (pedido_detalle.id_producto=producto.id_producto)
							 JOIN presentacion_producto ON (pedido_detalle.id_presentacion=presentacion_producto.id_presentacion)
							 JOIN presentacion ON (presentacion_producto.presentacion=presentacion.id_presentacion)
							 JOIN stock ON (pedido_detalle.id_producto=stock.id_producto)
							 WHERE pedido_detalle.id_pedido='$id_pedido'");

							$i = 1;
							$cant = 0;
							$enviado = 0;
							while ($filas = _fetch_array($sql_prese))
							{	$cant += $filas['cantidad'];
								$enviado += $filas['cantidad_enviar'];
								 $id_presentacion=$filas['id_presentacion'];
	               	echo "<tr>";
	               	echo "<td>".$filas['id_producto']."</td>";
	               	echo "<td>".$filas['producto']."</td>";
	               	echo "<td>".$filas['nombre']."</td>";
	               	echo "<td>".$filas['descripcion']."</td>";
	               	echo "<td>".$filas['cantidad']."</td>";
									echo "<td>".$filas['cantidad_enviar']."</td>";
									echo "<td>".$filas['precio_venta']."</td>";
	               	echo "<td>".$filas['subtotal']."</td>";
	               	echo "</tr>";
								$i++;
							}
						?>
						</tbody>
						<tfoot>
	          	<tr>
	            	<td colspan="4">Total<strong></strong></td>
								<td><?php echo $cant;?></td>
								<td><?php echo $enviado ?></td>
								<td></td>
	              <td id='total_dinero'>$<?php echo $total;?></td>
	            </tr>
	          </tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">

	<?php

		echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
		</div><!--/modal-footer -->";
	} //permiso del script
		else {
			$mensaje="No tiene permiso para este modulo.";
			echo "
			<div calss='modal-header'>
				<div class='alert alert-warning'><h5 class='text-success'>$mensaje</h5></div>
			</div>
			<div class='modal-footer'>
				<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cerrar</button>
			</div>";
	}//permiso del script
	?>
