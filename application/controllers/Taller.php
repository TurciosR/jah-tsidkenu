<?php
defined('BASEPATH') or exit('No direct script access allowed');

error_reporting(E_ALL ^ E_NOTICE);
include APPPATH . 'libraries/NumeroALetras.php';
include APPPATH . 'libraries/AlignMarginText.php';
class Taller extends CI_Controller
{
	/*
	Global table name
	*/
	private $table = "stock";
	private $pk = "id_producto";

	function __construct()
	{
		parent::__construct();
		$this->load->model('UtilsModel', "utils");
		$this->load->model("TallerModel", "taller");
		$this->load->model("VentasModel", "ventas");
		$this->load->model("Clientes_model", "clientes");
		$this->load->library('user_agent');
		$this->load->model("InventarioModel", "inventario");
		//	$this->load->helper('print_helper');
	}

	// cambios 7-7-2022
	private function getTipoPago(string $tipo)
	{
		$result = "";
		switch ($tipo) {
			case 'CON':
				$result = ' <span class="badge badge-primary"><span class="mdi mdi-currency-usd"></span> ' . $tipo . '</span>';
				break;

			case 'TAR':
				$result = ' <span class="badge badge-success"><span class="mdi mdi-credit-card"></span> ' . $tipo . '</span>';
				break;

			case 'CRE':
				$result = ' <span class="badge badge-info"><span class="mdi mdi-calendar-clock"></span> ' . $tipo . '</span>';
				break;

			default:
				# code...
				break;
		}

		return $result;
	}
	/*********************************************************/
	/*********************************************************/
	/************************CARGAS***************************/
	/*********************************************************/
	/*********************************************************/
	public function index()
	{
		$id_usuario = $this->session->id_usuario;
		$id_sucursal = $this->session->id_sucursal;
		$usuario_tipo =	$this->taller->get_one_row("usuario", array('id_usuario' => $id_usuario,));
		if ($usuario_tipo != NULL) {
			if ($usuario_tipo->admin == 1 || $usuario_tipo->super_admin == 1) {
				$sucursales = $this->taller->get_detail_rows("sucursales", array('1' => 1,));
			} else {
				$sucursales = $this->taller->get_detail_rows("sucursales", array('id_sucursal' => $id_sucursal,));
			}
		} else {
			$sucursales = $this->taller->get_detail_rows("sucursales", array('1' => 1,));
		}

		$data = array(
			"titulo" => "Trabajos en taller",
			"icono" => "mdi mdi-cogs",
			"buttons" => array(
				0 => array(
					"icon" => "mdi mdi-plus",
					'url' => 'taller/agregar',
					'txt' => ' Nuevo trabajo de taller',
					'modal' => false,
				),
			),
			"selects" => array(
				0 => array(
					"name" => "sucursales",
					"data" => $sucursales,
					"id" => "id_sucursal",
					"text" => array(
						"nombre",
						"direccion",
					),
					"separator" => " ",
					"selected" => $this->session->id_sucursal,
				),
			),
			"table" => array(
				"No." => 5,
				"Fecha" => 5,
				"Cliente" => 15,
				"Concepto" => 35,
				"Total" => 8,
				"Estado" => 5,
				"Detalle" => 35,
				"Acciones" => 10,
			),
		);
		$extras = array(
			'css' => array(),
			'js' => array(
				"js/scripts/taller.js"
			),
		);
		layout("template/admin", $data, $extras);
	}

	function get_data()
	{
		$draw = intval($this->input->post("draw"));
		$start = intval($this->input->post("start"));
		$length = intval($this->input->post("length"));
		$id_sucursal = $this->input->post("id_sucursal");

		$order = $this->input->post("order");
		$search = $this->input->post("search");
		$search = $search['value'];
		$col = 0;
		$dir = "";
		if (!empty($order)) {
			foreach ($order as $o) {
				$col = $o['column'];
				$dir = $o['dir'];
			}
		}

		if ($dir != "asc" && $dir != "desc") {
			$dir = "desc";
		}
		$valid_columns = array(
			0 => 'v.id_trabajo_taller',
			1 => 'v.fecha',
			2 => 'c.nombre',
			3 => 'v.concepto',
			4 => 'v.total',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}

		$row = $this->taller->get_collection($order, $search, $valid_columns, $length, $start, $dir, $id_sucursal);
		if ($row != 0) {
			$data = array();
			foreach ($row as $rows) {
				//procedemos a obtener el detalle de la venta
				$detalleV = $this->taller->get_detalle_venta($rows->id_trabajo_taller);
				$detalleS = $this->taller->get_detalle_serv($rows->id_trabajo_taller);
				$menudrop = "<div class='btn-group'>
				<button data-toggle='dropdown' class='btn btn-success dropdown-toggle' aria-expanded='false'><i class='mdi mdi-menu' aria-haspopup='false'></i> Menu</button>
				<ul class='dropdown-menu dropdown-menu-right' x-placement='bottom-start'>";
				$filename = base_url("taller/editar/");
				$icon = "mdi mdi-toggle-switch";
				if ($rows->id_estado == 4) {
					$menudrop .= "<li><a role='button' href='" . $filename . $rows->id_trabajo_taller . "' ><i class='mdi mdi-square-edit-outline' ></i> Actualizar</a></li>";
					$filename = base_url("taller/finalizar/");
					$menudrop .= "<li><a role='button' href='" . $filename . $rows->id_trabajo_taller . "' ><i class='mdi mdi-receipt' ></i> Facturar</a></li>";
					$menudrop .= "<li><a  class='delete_row'  id=" . $rows->id_trabajo_taller . " ><i class='mdi mdi-trash-can-outline'></i> Eliminar</a></li>";
					$menudrop .= "<li><a  class='state_change'  data-state='Anular'  id=" . $rows->id_trabajo_taller . " ><i class='$icon'></i> Anular</a></li>";
				}
				// if ($rows->id_estado == 2 && $rows->nombredoc != "DEVOLUCION") {
				// 	$filename = base_url("taller/devolver/");
				// 	$menudrop .= "<li><a role='button' href='" . $filename . $rows->id_trabajo_taller . "' ><i class='mdi mdi-square-edit-outline' ></i> Devoluci??n</a></li>";
				// }
				$menudrop .= "<li><a  data-toggle='modal' data-target='#viewModal' data-refresh='true'  role='button' class='detail' data-id=" . $rows->id_trabajo_taller . "><i class='mdi mdi-eye-check' ></i> Detalles</a></li>";

				$menudrop .= "</ul></div>";

				$spacing = $detalleV->detalle_v != "" ? "<br><br>" : "";
				$stringserv = $detalleS->detalle_s != "" ? $spacing . $detalleS->detalle_s : "";

				$data[] = array(
					$rows->id_trabajo_taller,
					$rows->fecha,
					$rows->nombre,
					$rows->concepto,
					'$ ' . $rows->total,
					$rows->descripcion,
					$detalleV->detalle_v . $stringserv,
					$menudrop,
				);
			}
			$total = $this->taller->total_rows();
			$output = array(
				"draw" => $draw,
				"recordsTotal" => $total,
				"recordsFiltered" => $total,
				"data" => $data
			);
		} else {
			$data[] = array(
				"",
				"",
				"No se encontraron registros",
				"",
				"",
				"",
				"",
				"",
			);
			$output = array(
				"draw" => $draw,
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => $data
			);
		}
		echo json_encode($output);
		exit();
	}

	function detalle($id = -1)
	{
		if ($this->input->method(TRUE) == "GET") {
			$id = $this->uri->segment(3);
			//$rowvta = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));
			$rows = $this->taller->get_detail_ci($id);
			$rowserv = $this->taller->get_detail_serv($id);
			//if($rows && $id!=""){
			if ($id != "") {
				$data = array(
					"id" => $id,
					"rows" => $rows,
					"rowserv" => $rowserv,
					"process" => "venta",
				);
				$this->load->view("taller/ver_detalle.php", $data);
			} else {
				//redirect('errorpage');
			}
		}
	}

	function state_change()
	{
		if ($this->input->method(TRUE) == "POST") {
			$id = $this->input->post("id");
			$anular = 1;
			$where = "id_trabajo_taller='" . $id . "'";
			$data = array(
				"id_estado" => 3,
			);

			$response = $this->utils->update("trabajos_taller", $data, $where);
			//
			$detalles = $this->taller->get_detail_ci($id);
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));
			//procedemos a realizar la carga del inventario
			$correlativo = $this->inventario->get_max_correlative('ci', $row->id_sucursal);
			$id_usuario = $this->session->id_usuario;
			$data = array(
				'fecha' => $row->fecha,
				'hora' => $row->hora,
				'concepto' => "POR DEVOLUCION DE PRODUCTOS",
				'total' => $row->total,
				'id_sucursal' => $row->id_sucursal,
				'correlativo' => $correlativo,
				'id_usuario' => $id_usuario,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
			);

			$imei_required = false;

			$id_carga = $this->inventario->inAndCon('inventario_carga', $data);

			$id_sucursal = $row->id_sucursal;
			if ($detalles != NULL) {
				foreach ($detalles as $detalle) {
					$id_producto = $detalle->id_producto;
					$color = $detalle->id_color;
					$costo = $detalle->costo;
					$precio_sugerido = $detalle->precio;
					$cantidad = $detalle->cantidad;
					$subtotal = ($detalle->cantidad * $detalle->costo);
					$form_data = array(
						'id_carga' => $id_carga,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'costo' => $costo,
						'precio' => $precio_sugerido,
						'cantidad' => $cantidad,
						'subtotal' => $subtotal,
					);
					$id_detalle = $this->inventario->inAndCon('inventario_carga_detalle', $form_data);

					if ($detalle->tipo_prod == 0) {
						$stock_data = $this->taller->get_stock($id_producto, $detalle->id_color, $id_sucursal);
						$newstock = ($stock_data->cantidad) + $cantidad;
						$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
					}
				}
			}
			///
			if ($response) {
				$xdatos["type"] = "success";
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Registo  anulado correctamente!";
			} else {

				$xdatos["type"] = "error";
				$xdatos['title'] = 'Alerta';
				$xdatos["msg"] = "Registo no pudo ser  anulado";
			}
			echo json_encode($xdatos);
		}
	}

	function change_state($id = -1)
	{
		if ($this->input->method(TRUE) == "GET") {
			$id = $this->uri->segment(3);
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));
			$rows = $this->taller->get_detail_rows("estado", array('1' => 1,));
			if ($rows && $id != "") {
				$data = array(
					"row" => $row,
					"rows" => $rows,
				);
				$this->load->view("ventas/change_state.php", $data);
			} else {
				redirect('errorpage');
			}
		}
	}
	function get_data_cliente($id = -1)
	{
		if ($this->input->method(TRUE) == "GET") {
			$id = $this->uri->segment(3);
			$giro = $this->clientes->get_giro();
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));
			$rowcte = $this->taller->get_one_row("clientes", array('id_cliente' => $row->id_cliente,));
			//$id_cliente=$row->id_cliente;
			$row_tipopago = $this->taller->get_detail_rows("tipo_pago", array('null' => -1,));

			//Procedemos a obtener los datos de los vendedores
			$vendedores = $this->taller->get_detail_rows("usuario", array('id_rol' => '1',));

			if ($row && $id != "") {
				$data = array(
					"row" => $row,
					"rowcte" => $rowcte,
					"giro" => $giro,
					"vendedores" => $vendedores,
					"tipo_pago" => $row_tipopago,
				);
				$this->load->view("taller/data_client.php", $data);
			} else {
				redirect('errorpage');
			}
		}
	}
	function agregar()
	{
		if ($this->input->method(TRUE) == "GET") {
			//apertura de caja
			$fecha = date('Y-m-d');
			$id_usuario = $this->session->id_usuario;
			$id_sucursal = $this->session->id_sucursal;
			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
			$usuario_ap = NULL;
			if ($row_ap != NULL) {
				$id_apertura = $row_ap->id_apertura;
				$usuario_ap =	$this->taller->get_one_row("usuario", array('id_usuario' => $row_ap->id_usuario,));
			}
			$row_clientes = $this->taller->get_detail_rows("clientes", array('activo' => 1, 'deleted' => 0,)); //
			$row_client_select = $this->taller->get_one_row("clientes", array('activo' => 1, 'deleted' => 0,));
			//fin apertura caja
			$data = array(
				"sucursal" => $this->taller->get_detail_rows("sucursales", array('1' => 1,)),
				"id_sucursal" => $this->session->id_sucursal,
				"row_clientes" => $row_clientes,
				"id_usuario" => $id_usuario,
				"row_ap" => $row_ap,
				"usuario_ap" => $usuario_ap,
				"row_client_select" => $row_client_select,
			);

			$extras = array(
				'css' => array(
					"css/scripts/taller.css"
				),
				'js' => array(
					"js/scripts/taller.js"
				),
			);

			layout("taller/guardar", $data, $extras);
		} else if ($this->input->method(TRUE) == "POST") {
			$this->load->model("ProductosModel", "productos");
			$this->utils->begin();
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$total = $this->input->post("total");
			$id_cliente = $this->input->post("client");
			$data_ingreso = json_decode($this->input->post("data_ingreso"), true);
			$id_sucursal = $this->input->post("sucursal");
			$id_usuario = $this->session->id_usuario;
			$hora = date("H:i:s");

			$correlativo = $this->taller->get_max_correlative('ven', $id_sucursal);

			$referencia = $this->taller->get_correlative('reftaller', $id_sucursal);
			$this->utils->update("correlativo", array("reftaller" => $referencia,), "id_sucursal=" . $id_sucursal);

			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'indicaciones' => "TRABAJO EN TALLER",
				'id_cliente' => $id_cliente,
				'id_estado' => 4,
				'id_sucursal_despacho' => $id_sucursal,
				'referencia' => $referencia,
				'correlativo' => $correlativo,
				'total' => $total,
				'id_sucursal' => $id_sucursal,
				'id_usuario' => $id_usuario,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
				'guia' => "",
			);

			$imei_required = false;

			$id_trabajo_taller = $this->taller->inAndCon('trabajos_taller', $data);
			if ($id_trabajo_taller != NULL) {
				if ($data_ingreso != NULL) {
					foreach ($data_ingreso as $fila) {
						$id_producto = $fila['id_producto'];
						$costo = $fila['costo'];
						$cantidad = $fila['cantidad'];
						$precio_sugerido = $fila['precio_sugerido'];
						$descuento = $fila['descuento'];
						$precio_final = $fila['precio_final'];
						$subtotal = $fila['subtotal'];
						$color = $fila['color'];
						$tipo = $fila['tipo']; //"0:PRODUCTO,1:SERVICIO"

						$estado = $fila['est'];
						($tipo == 0) ? $id_precio = $fila['id_precio'] : $id_precio = 0;

						$form_data = array(
							'id_trabajo_taller' => $id_trabajo_taller,
							'id_producto' => $id_producto,
							'id_color' => $color,
							'costo' => $costo,
							'precio' => $precio_sugerido,
							'precio_fin' => $precio_final,
							'descuento' => $descuento,
							'cantidad' => $cantidad,
							'subtotal' => $subtotal,
							'condicion' => $estado,
							'tipo_prod' => $tipo,
							'garantia' =>  $this->taller->getGarantia($id_producto, $estado),
							'id_precio_producto' => $id_precio,
						);
						$id_detalle = $this->taller->inAndCon('trabajos_taller_detalle', $form_data);
						$stock_data = $this->taller->get_stock($id_producto, $color, $id_sucursal);
						$newstock = ($stock_data->cantidad) - $cantidad;
						if ($tipo == 0) {
							$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
						}
						if ($this->taller->has_imei_required($id_producto)) {
							// code...
							$imei_required = true;
						}
					}
				}
				if ($imei_required) {
					// code...
					$this->utils->update("trabajos_taller", array('requiere_imei' => 1,), "id_trabajo_taller=$id_trabajo_taller");
				}
				$this->utils->commit();
				$xdatos["type"] = "success";
				$xdatos["referencia"] = $id_trabajo_taller;
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Registo ingresado correctamente!";
			} else {
				$this->utils->rollback();
				$xdatos["type"] = "error";
				$xdatos['title'] = 'Alerta';
				$xdatos["referencia"] = -1;
				$xdatos["msg"] = "Error al ingresar el registro";
			}


			echo json_encode($xdatos);
		}
	}

	function editar($id = -1)
	{

		if ($this->input->method(TRUE) == "GET") {
			$id = $this->uri->segment(3);
			//apertura de caja
			$id_usuario = $this->session->id_usuario;
			$fecha = date('Y-m-d');
			$id_sucursal = $this->session->id_sucursal;
			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
			$usuario_ap = NULL;
			if ($row_ap != NULL) {
				$id_apertura = $row_ap->id_apertura;
				$usuario_ap =	$this->taller->get_one_row("usuario", array('id_usuario' => $row_ap->id_usuario,));
			}
			$row_clientes = $this->taller->get_detail_rows("clientes", array('activo' => 1, 'deleted' => 0,)); //
			$row_client_select = $this->taller->get_one_row("clientes", array('activo' => -1, 'deleted' => 0,));
			//fin apertura caja
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));
			$rowc = $this->taller->get_one_row("clientes", array('id_cliente' => $row->id_cliente,));
			$rowpc = $this->taller->get_porcent_client($rowc->clasifica);

			$detalles = $this->taller->get_detail_ci($id);
			$detalleservicios = $this->taller->get_detail_serv($id);

			$detalles1 = array();
			if ($detalles != NULL) {

				$clasifica = $rowc->clasifica;
				foreach ($detalles as $detalle) {
					$id_producto = $detalle->id_producto;
					$precio = $detalle->precio;
					$qty_sold = $detalle->cantidad;
					if ($detalle->id_color != -1) {
						//$precios=$this->taller->get_detail_rows("producto_precio",array('id_producto' =>$id_producto,'id_listaprecio'=>$clasifica));
						$precios = $this->taller->get_detail_rows("producto_precio", array('id_producto' => $id_producto));

						$stock_data = $this->taller->get_stock($id_producto, $detalle->id_color, $row->id_sucursal);
						//$detalle->precios = $detallesp["precios"];
						$lista = "";
						$lista .= "<select class='form-control precios sel' style='width:100%;'>";
						$costo = 0;
						$costo_iva = 0;
						foreach ($precios as $row_por) {
							$id_porcentaje = $row_por->id_precio;
							$costo = $row_por->costo;
							$costo_iva = $row_por->costo_iva;

							$precio = $row_por->porcentaje;
							if ($detalle->id_precio_producto == $row_por->id_precio) {
								// code...
								$lista .= "<option value='" . $precio . "' precio='" . $precio . "' id_precio='" . $row_por->id_precio . "' selected>" . number_format($precio, 2, ".", ",") . "</option>";
							} else {
								// code...
								$lista .= "<option value='" . $precio . "' precio='" . $precio . "' id_precio='" . $row_por->id_precio . "'>" . number_format($precio, 2, ".", ",") . "</option>";
							}
						}
						$lista .= "</select>";
						$detalle->precios = $lista;
						$detalle->stock = $stock_data->cantidad + $qty_sold;
						$detalle->id_stock = $stock_data->id_stock;
						$detalle->id_color = $stock_data->id_color;

						$d = $this->taller->get_reservado($id_producto, $id, $detalle->id_color);
						$detalle->reservado = $d->reservado;

						$estado = "<select class='est'>";
						if ($detalle->condicion == "NUEVO") {
							$estado .= "<option selected value='NUEVO'>NUEVO</option>";
							$estado .= "<option value='USADO'>USADO</option>";
						} else {
							$estado .= "<option value='NUEVO'>NUEVO</option>";
							$estado .= "<option selected value='USADO'>USADO</option>";
						}
						$estado .= "</select>";

						$detalle->estado = $estado;
						//}
					}
					array_push($detalles1, $detalle);
				}
			}
			if ($row && $id != "") {
				$data = array(
					"row" => $row,
					"detalles" => $detalles1,
					"detalleservicios" => $detalleservicios,
					'rowpc' => $rowpc,
					"sucursal" => $this->taller->get_detail_rows("sucursales", array('1' => 1,)),
					"id_sucursal" => $row->id_sucursal,
					"rowc" => $rowc,
					"id_usuario" => $id_usuario,
					"row_ap" => $row_ap,
					"usuario_ap" => $usuario_ap,
					"row_clientes" => $row_clientes,
					"row_client_select" => $row_client_select,
				);
				$extras = array(
					'css' => array(
						"css/scripts/taller.css"
					),
					'js' => array(
						"js/scripts/taller.js"
					),
				);
				layout("taller/editar", $data, $extras);
			} else {
				redirect('errorpage');
			}
		} else if ($this->input->method(TRUE) == "POST") {
			$this->utils->begin();
			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$instrucciones = $this->input->post("instrucciones");
			$total = $this->input->post("total");
			$id_cliente = $this->input->post("client");
			$data_ingreso = json_decode($this->input->post("data_ingreso"), true);
			$id_sucursal = $this->input->post("sucursal");
			$envio = $this->input->post("envio");
			$id_usuario = $this->session->id_usuario;
			$id_sucursal = $this->session->id_sucursal;
			$hora = date("H:i:s");

			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));

			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'indicaciones' => "EDITAR TRABAJO DE TALLER",
				'id_cliente' => $id_cliente,
				'id_estado' => 4,
				'id_sucursal_despacho' => $id_sucursal,
				'total' => $total,
				'id_sucursal' => $id_sucursal,
				'id_usuario' => $id_usuario,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
				'guia' => "",
			);


			/*editar encabezado*/
			$this->utils->update('trabajos_taller', $data, "id_trabajo_taller=$id_trabajo_taller");

			/*Cargo los detalles previos*/
			$detalles_previos = $this->taller->get_detail_ci($id_trabajo_taller);
			if ($detalles_previos != NULL) {
				foreach ($detalles_previos as $key) {
					if ($key->tipo_prod == 0) {
						$stock_data = $this->taller->get_stock($key->id_producto, $key->id_color, $id_sucursal);
						$newstock = ($stock_data->cantidad) + ($key->cantidad);

						$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
					}
				}
			}
			/*eliminar detalles previos*/
			$this->utils->delete("trabajos_taller_detalle", "id_trabajo_taller=$id_trabajo_taller");

			/*nuevos detalles*/
			if ($data_ingreso != NULL) {
				foreach ($data_ingreso as $fila) {
					$id_producto = $fila['id_producto'];
					$costo = $fila['costo'];
					$cantidad = $fila['cantidad'];
					$precio_sugerido = $fila['precio_sugerido'];
					$descuento = $fila['descuento'];
					$precio_final = $fila['precio_final'];
					$subtotal = $fila['subtotal'];
					$color = $fila['color'];
					$estado = $fila['est'];
					$tipo = $fila['tipo']; //"0:PRODUCTO,1:SERVICIO"
					($tipo == 0) ? $id_precio = $fila['id_precio'] : $id_precio = 0;

					$form_data = array(
						'id_trabajo_taller' => $id_trabajo_taller,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'costo' => $costo,
						'precio' => $precio_sugerido,
						'precio_fin' => $precio_final,
						'descuento' => $descuento,
						'cantidad' => $cantidad,
						'subtotal' => $subtotal,
						'condicion' => $estado,
						'tipo_prod' => $tipo,
						'garantia' =>  $this->taller->getGarantia($id_producto, $estado),
						'id_precio_producto' => $id_precio,
					);
					$id_detalle = $this->taller->inAndCon('trabajos_taller_detalle', $form_data);
					$this->utils->update(
						"producto",
						array(
							'precio_sugerido' => $precio_sugerido,
							'costo_s_iva' => $costo,
							'costo_c_iva' => round($costo * 1.13),
						),
						"id_producto=$id_producto"
					);
					$stock_data = $this->taller->get_stock($id_producto, $color, $id_sucursal);
					$newstock = ($stock_data->cantidad) - $cantidad;
					if ($tipo == 0 && $estado != "SERVICIO") {
						$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
					}
				}
			}

			$this->utils->commit();
			$xdatos["type"] = "success";
			$xdatos['title'] = 'Informaci??n';
			$xdatos["msg"] = "Registo ingresado correctamente!";

			echo json_encode($xdatos);
		}
	}

	/**
	 * Se utiliza para finalizar un trabajo de taller y generar un registro en la tabla ventas
	 * @param $id_trabajo_taller
	 * 
	 * @return mixed
	 */
	function finalizar($id = -1)
	{
		if ($this->input->method(TRUE) == "GET") {
			$id = $this->uri->segment(3);
			//apertura de caja
			$id_usuario = $this->session->id_usuario;
			$fecha = date('Y-m-d');
			$id_sucursal = $this->session->id_sucursal;
			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
			$usuario_ap = NULL;
			if ($row_ap != NULL) {
				$id_apertura = $row_ap->id_apertura;
				$usuario_ap =	$this->taller->get_one_row("usuario", array('id_usuario' => $row_ap->id_usuario,));
				if ($row_ap->id_usuario == 0) {
				} else {
					$detalle_ap =	$this->taller->get_one_row("detalle_apertura", array('id_apertura' => $id_apertura, 'vigente' => 1, 'id_usuario' => $row_ap->id_usuario,));
				}
			}
			$row_clientes = $this->taller->get_detail_rows("clientes", array('null' => -1,)); //
			//fin apertura caja
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));
			$rowc = $this->taller->get_one_row("clientes", array('id_cliente' => $row->id_cliente,));
			$rowpc = $this->taller->get_porcent_client($rowc->clasifica);
			$clasifica = $rowc->clasifica;
			$detalles = $this->taller->get_detail_ci($id);
			$detalleservicios = $this->taller->get_detail_serv($id);
			$tipodoc =	$this->taller->get_tipodoc();
			$detalles1 = array();
			if ($detalles != NULL) {
				foreach ($detalles as $detalle) {
					$id_producto = $detalle->id_producto;
					$precio = $detalle->precio;
					$qty_sold = $detalle->cantidad;
					//$precios=$this->taller->get_detail_rows("producto_precio",array('id_producto' =>$id_producto,'id_listaprecio'=>$clasifica));

					$precios = $this->taller->get_detail_rows("producto_precio", array('id_producto' => $id_producto));
					$stock_data = $this->taller->get_stock($id_producto, $detalle->id_color, $row->id_sucursal);
					$lista = "";
					$lista .= "<select class='form-control precios sel' style='width:100%;'>";
					$costo = 0;
					$costo_iva = 0;
					foreach ($precios as $row_por) {
						$id_porcentaje = $row_por->id_precio;
						$costo = $row_por->costo;
						$costo_iva = $row_por->costo_iva;
						$precio = $row_por->porcentaje;
						if ($detalle->id_precio_producto == $row_por->id_precio) {
							// code...
							$lista .= "<option value='" . $precio . "' precio='" . $precio . "' id_precio='" . $row_por->id_precio . "' selected>" . number_format($precio, 2, ".", ",") . "</option>";
						} else {
							// code...
							$lista .= "<option value='" . $precio . "' precio='" . $precio . "' id_precio='" . $row_por->id_precio . "'>" . number_format($precio, 2, ".", ",") . "</option>";
						}
					}
					$lista .= "</select>";
					$detalle->precios = $lista;
					$detalle->stock = $stock_data->cantidad + $qty_sold;
					$detalle->id_stock = $stock_data->id_stock;
					$detalle->id_color = $stock_data->id_color;

					$d = $this->taller->get_reservado($id_producto, $id, $detalle->id_color);
					$detalle->reservado = $d->reservado;

					$estado = "<select class='est'>";
					if ($detalle->condicion == "NUEVO") {
						$estado .= "<option selected value='NUEVO'>NUEVO</option>";
						$estado .= "<option value='USADO'>USADO</option>";
					} else {
						$estado .= "<option value='NUEVO'>NUEVO</option>";
						$estado .= "<option selected value='USADO'>USADO</option>";
					}
					$estado .= "</select>";

					$detalle->estado = $estado;
					//}
					array_push($detalles1, $detalle);
				}
			}
			if ($row && $id != "") {
				$id_usuario = $this->session->id_usuario;
				$fecha = date('Y-m-d');
				$data = array(
					"row" => $row,
					"detalles" => $detalles1,
					"detalleservicios" => $detalleservicios,
					'tipodoc' => $tipodoc,
					'rowpc' => $rowpc,
					"sucursal" => $this->taller->get_detail_rows("sucursales", array('1' => 1,)),
					"id_sucursal" => $row->id_sucursal,
					"rowc" => $rowc,
					"row_clientes" => $row_clientes,
					"id_usuario" => $id_usuario,
					"row_ap" => $row_ap,
					"usuario_ap" => $usuario_ap,
				);
				$extras = array(
					'css' => array(
						"css/scripts/taller.css"
					),
					'js' => array(
						"js/scripts/taller.js"
					),
				);
				layout("taller/finalizar", $data, $extras);
			} else {
				redirect('errorpage');
			}
		} else if ($this->input->method(TRUE) == "POST") {
			$this->utils->begin();
			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
			$concepto = strtoupper($this->input->post("concepto"));
			$encargado = strtoupper($this->input->post("encargado"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$total = $this->input->post("total");
			$id_cliente = $this->input->post("client");
			$data_ingreso = json_decode($this->input->post("data_ingreso"), true);
			$tipodoc = $this->input->post("tipodoc");
			$id_sucursal = $this->session->id_sucursal;
			$envio = $this->input->post("envio") ?? "";
			$id_usuario = $this->session->id_usuario;
			$hora = date("H:i:s");
			$fechahoy = date('Y-m-d');
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));
			$id_sucursalO = $row->id_sucursal;
			$fecha_corr = $this->taller->get_date_correlative($id_sucursal);
			$referencia =  $row->referencia;


			switch ($tipodoc) {
				case 1:
					$correlativo = $this->taller->get_correlative('tik', $id_sucursal);
					$correlativo1 = $this->taller->update_correlative('tik', $correlativo, $id_sucursal);
					break;
				case 2:
					$correlativo = $this->taller->get_correlative('cof', $id_sucursal);
					$correlativo1 = $this->taller->update_correlative('cof', $correlativo, $id_sucursal);
					break;
				case 3:
					$correlativo = $this->taller->get_correlative('ccf', $id_sucursal);
					$correlativo1 = $this->taller->update_correlative('ccf', $correlativo, $id_sucursal);
					break;
			}

			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fechahoy);
			$id_apertura = $row_ap->id_apertura;
			$caja = $row_ap->caja;
			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'encargado' => $encargado,
				'indicaciones' => "TRABAJO DE TALLER",
				'id_cliente' => $id_cliente,
				'envio' => $envio,
				'id_estado' => 2,
				'id_sucursal_despacho' => $id_sucursal,
				'total' => $total,
				'id_sucursal' => $id_sucursal,
				'id_usuario' => $id_usuario,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
				'tipo_doc' => $tipodoc,
				'referencia' => $referencia,
				'correlativo' => $correlativo,
				'guia' => "",
				'caja' => $caja,
				'id_apertura' => $id_apertura,
				'hora_fin' => $hora,
			);

			/*editar encabezado*/
			$this->utils->update('trabajos_taller', $data, "id_trabajo_taller=$id_trabajo_taller");
			unset($data['encargado']);
			$data['concepto'] = "FACTURACION DE TRABAJO DE TALLER NRO. " . $id_trabajo_taller;
			$id_venta = $this->ventas->inAndCon('ventas', $data);

			// Asociamos la venta con el trabajo del taller
			$this->utils->update('trabajos_taller', ['id_venta' => $id_venta], "id_trabajo_taller=$id_trabajo_taller");

			/*Cargo los detalles previos*/
			$detalles_previos = $this->taller->get_detail_ci($id_trabajo_taller);
			if ($detalles_previos != NULL) {
				foreach ($detalles_previos as $key) {

					if ($key->tipo_prod == 0) {
						$stock_data = $this->taller->get_stock($key->id_producto, $key->id_color, $id_sucursalO);
						$newstock = ($stock_data->cantidad) + ($key->cantidad);
						$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
					}
				}
			}
			/*eliminar detalles previos*/
			$this->utils->delete("trabajos_taller_detalle", "id_trabajo_taller=$id_trabajo_taller");

			/*nuevos detalles*/
			if ($data_ingreso != NULL) {
				foreach ($data_ingreso as $fila) {
					$id_producto = $fila['id_producto'];
					$costo = $fila['costo'];
					$cantidad = $fila['cantidad'];
					$precio_sugerido = $fila['precio_sugerido'];
					$descuento = $fila['descuento'];
					$precio_final = $fila['precio_final'];
					$subtotal = $fila['subtotal'];
					$color = $fila['color'];
					$estado = $fila['est'];
					$tipo = $fila['tipo']; //"0:PRODUCTO,1:SERVICIO"
					($tipo == 0) ? $id_precio = $fila['id_precio'] : $id_precio = 0;

					$form_data = array(
						'id_trabajo_taller' => $id_trabajo_taller,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'costo' => $costo,
						'precio' => $precio_sugerido,
						'precio_fin' => $precio_final,
						'descuento' => $descuento,
						'cantidad' => $cantidad,
						'subtotal' => $subtotal,
						'condicion' => $estado,
						'tipo_prod' => $tipo,
						'garantia' =>  $this->taller->getGarantia($id_producto, $estado),
						'id_precio_producto' => $id_precio,
					);
					$id_detalle = $this->taller->inAndCon('trabajos_taller_detalle', $form_data);

					unset($form_data['id_trabajo_taller']);
					$form_data['id_venta'] = $id_venta;
					$id_detalle_v = $this->ventas->inAndCon('ventas_detalle', $form_data);
					$this->utils->update(
						"producto",
						array(
							'precio_sugerido' => $precio_sugerido,
							'costo_s_iva' => $costo,
							'costo_c_iva' => round($costo * 1.13),
						),
						"id_producto=$id_producto"
					);

					if ($tipo == 0 && $estado != "SERVICIO") {
						$stock_data = $this->taller->get_stock($id_producto, $color, $id_sucursal);
						$newstock = ($stock_data->cantidad) - $cantidad;
						$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
					}
				}
			}

			$this->utils->commit();
			$xdatos["type"] = "success";
			$xdatos['title'] = 'Informaci??n';
			$xdatos["msg"] = "Facturacion guardada correctamente!";
			$xdatos["id_factura"] = $id_trabajo_taller;
			$xdatos["id_venta"] = $id_venta;
			$xdatos["proceso"] = "finalizar";

			echo json_encode($xdatos);
		}
	}

	//finalizar por referencia o facturar Directo
	function fin_fact($id = -1)
	{
		if ($this->input->method(TRUE) == "POST") {

			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
			if ($id_trabajo_taller == -1) { //caso que no tenia referencia a cargar es como facturar directo
				$this->load->model("ProductosModel", "productos");
				$this->utils->begin();
				$concepto = "VENTA";
				$fecha = Y_m_d($this->input->post("fecha"));
				$total = $this->input->post("total");

				$id_cliente = $this->input->post("client");
				$data_ingreso = json_decode($this->input->post("data_ingreso"), true);
				$id_sucursal = $this->input->post("id_sucursal");
				$tipodoc = $this->input->post("tipodoc");
				$id_usuario = $this->session->id_usuario;
				$hora = date("H:i:s");
				$fechahoy = date('Y-m-d');
				$fecha_corr = $this->taller->get_date_correlative($id_sucursal);

				$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fechahoy);
				$id_apertura = $row_ap->id_apertura;
				$caja = $row_ap->caja;

				$referencia = 0;
				switch ($tipodoc) {
					case 1:
						$correlativo = $this->taller->get_correlative('tik', $id_sucursal);
						$correlativo1 = $this->taller->update_correlative('tik', $correlativo, $id_sucursal);
						break;
					case 2:
						$correlativo = $this->taller->get_correlative('cof', $id_sucursal);
						$correlativo1 = $this->taller->update_correlative('cof', $correlativo, $id_sucursal);
						break;
					case 3:
						$correlativo = $this->taller->get_correlative('ccf', $id_sucursal);
						$correlativo1 = $this->taller->update_correlative('ccf', $correlativo, $id_sucursal);
						break;
				}

				$data = array(
					'fecha' => $fechahoy,
					'hora' => $hora,
					'concepto' => "FINALIZADA REF",
					'indicaciones' => "FINALIZADA REF",
					'id_cliente' => $id_cliente,
					'id_estado' => 2,
					'id_sucursal_despacho' => $id_sucursal,
					'correlativo' => $correlativo,
					'total' => $total,
					'id_sucursal' => $id_sucursal,
					'id_usuario' => $id_usuario,
					'tipo_doc' => $tipodoc,
					'referencia' => 0,
					'caja' => $caja,
					'id_apertura' => $id_apertura,
					'hora_fin' => $hora,
				);

				$imei_required = false;

				$id_factura = $this->taller->inAndCon('trabajos_taller', $data);
				if ($id_factura != NULL) {
					if ($data_ingreso != NULL) {
						foreach ($data_ingreso as $fila) {
							$id_producto = $fila['id_producto'];
							$costo = $fila['costo'];
							$cantidad = $fila['cantidad'];
							$precio_sugerido = $fila['precio_sugerido'];
							$descuento = $fila['descuento'];
							$precio_final = $fila['precio_final'];
							$subtotal = $fila['subtotal'];
							$color = $fila['color'];
							$tipo = $fila['tipo']; //"0:PRODUCTO,1:SERVICIO"

							$estado = $fila['est'];
							$id_precio = $fila['id_precio'];

							$form_data = array(
								'id_trabajo_taller' => $id_factura,
								'id_producto' => $id_producto,
								'id_color' => $color,
								'costo' => $costo,
								'precio' => $precio_sugerido,
								'precio_fin' => $precio_final,
								'descuento' => $descuento,
								'cantidad' => $cantidad,
								'subtotal' => $subtotal,
								'condicion' => $estado,
								'tipo_prod' => $tipo,
								'garantia' =>  $this->taller->getGarantia($id_producto, $estado),
								'id_precio_producto' => $id_precio,
							);
							$id_detalle = $this->taller->inAndCon('trabajos_taller_detalle', $form_data);

							if ($tipo == 0) {
								$stock_data = $this->taller->get_stock($id_producto, $color, $id_sucursal);
								$newstock = ($stock_data->cantidad) - $cantidad;
								$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
							}
							if ($this->taller->has_imei_required($id_producto)) {
								$imei_required = true;
							}
						}
					}

					$this->utils->commit();
					$xdatos["type"] = "success";
					$xdatos['title'] = 'Informaci??n';
					$xdatos["msg"] = "Registo ingresado correctamente!";
					$xdatos["id_factura"] = $id_factura;
					$xdatos["proceso"] = "facturar";
				} else {
					$this->utils->rollback();
					$xdatos["type"] = "error";
					$xdatos['title'] = 'Alerta';
					$xdatos["msg"] = "Error al ingresar el registro";
				}
			} //finaliza venta sin referencia como de facturar
			else { //en caso que si hay referencia es como finalizar !
				$this->utils->begin();
				$concepto = strtoupper($this->input->post("concepto"));
				$fecha = Y_m_d($this->input->post("fecha"));

				$total = $this->input->post("total");
				$id_cliente = $this->input->post("client");
				$data_ingreso = json_decode($this->input->post("data_ingreso"), true);
				$tipodoc = $this->input->post("tipodoc");
				$id_sucursal = $this->session->id_sucursal;
				$envio = $this->input->post("envio");
				$id_usuario = $this->session->id_usuario;
				$hora = date("H:i:s");

				$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));
				$id_sucursalO = $row->id_sucursal;
				$fecha_corr = $this->taller->get_date_correlative($id_sucursal);
				$referencia =  $row->referencia;

				switch ($tipodoc) {
					case 1:
						$correlativo = $this->taller->get_correlative('tik', $id_sucursal);
						$correlativo1 = $this->taller->update_correlative('tik', $correlativo, $id_sucursal);
						break;
					case 2:
						$correlativo = $this->taller->get_correlative('cof', $id_sucursal);
						$correlativo1 = $this->taller->update_correlative('cof', $correlativo, $id_sucursal);
						break;
					case 3:
						$correlativo = $this->taller->get_correlative('ccf', $id_sucursal);
						$correlativo1 = $this->taller->update_correlative('ccf', $correlativo, $id_sucursal);
						break;
				}
				$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
				$id_apertura = $row_ap->id_apertura;
				$caja = $row_ap->caja;

				$data = array(
					'fecha' => $fecha,
					//'hora' => $hora,
					'concepto' => "FINALIZADA REF",
					'indicaciones' => "FINALIZADA REF:" . $referencia,
					'id_cliente' => $id_cliente,
					'envio' => "",
					'id_estado' => 2,
					'id_sucursal_despacho' => $id_sucursal,
					'total' => $total,
					'id_sucursal' => $id_sucursal,
					'id_usuario' => $id_usuario,
					'requiere_imei ' => 0,
					'imei_ingresado' => 0,
					'tipo_doc' => $tipodoc,
					//'referencia' => $referencia,
					'correlativo' => $correlativo,
					'guia' => "",
					'caja' => $caja,
					'id_apertura' => $id_apertura,
				);


				$imei_required = false;
				/*editar encabezado*/
				$this->utils->update('trabajos_taller', $data, "id_trabajo_taller=$id_trabajo_taller");

				/*Cargo los detalles previos*/
				$detalles_previos = $this->taller->get_detail_ci($id_trabajo_taller);
				if ($detalles_previos != NULL) {
					foreach ($detalles_previos as $key) {

						if ($key->tipo_prod == 0) {
							$stock_data = $this->taller->get_stock($key->id_producto, $key->id_color, $id_sucursalO);
							$newstock = ($stock_data->cantidad) + ($key->cantidad);
							$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
						}
					}
				}
				/*eliminar detalles previos*/
				$this->utils->delete("trabajos_taller_detalle", "id_trabajo_taller=$id_trabajo_taller");

				/*nuevos detalles*/
				if ($data_ingreso != NULL) {
					foreach ($data_ingreso as $fila) {
						$id_producto = $fila['id_producto'];
						$costo = $fila['costo'];
						$cantidad = $fila['cantidad'];
						$precio_sugerido = $fila['precio_sugerido'];
						$descuento = $fila['descuento'];
						$precio_final = $fila['precio_final'];
						$subtotal = $fila['subtotal'];
						$color = $fila['color'];
						$estado = $fila['est'];
						$tipo = $fila['tipo']; //"0:PRODUCTO,1:SERVICIO"
						($tipo == 0) ? $id_precio = $fila['id_precio'] : $id_precio = 0;

						$form_data = array(
							'id_trabajo_taller' => $id_trabajo_taller,
							'id_producto' => $id_producto,
							'id_color' => $color,
							'costo' => $costo,
							'precio' => $precio_sugerido,
							'precio_fin' => $precio_final,
							'descuento' => $descuento,
							'cantidad' => $cantidad,
							'subtotal' => $subtotal,
							'condicion' => $estado,
							'tipo_prod' => $tipo,
							'garantia' =>  $this->taller->getGarantia($id_producto, $estado),
							'id_precio_producto' => $id_precio,
						);
						$id_detalle = $this->taller->inAndCon('trabajos_taller_detalle', $form_data);
						$this->utils->update(
							"producto",
							array(
								'precio_sugerido' => $precio_sugerido,
								'costo_s_iva' => $costo,
								'costo_c_iva' => round($costo * 1.13),
							),
							"id_producto=$id_producto"
						);

						if ($tipo == 0 && $estado != "SERVICIO") {
							$stock_data = $this->taller->get_stock($id_producto, $color, $id_sucursal);
							$newstock = ($stock_data->cantidad) - $cantidad;
							$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
						}
					}
				}

				$this->utils->commit();
				$xdatos["type"] = "success";
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Venta guardada correctamente!";
				$xdatos["id_factura"] = $id_trabajo_taller;
				$xdatos["proceso"] = "finalizar";
			}
			echo json_encode($xdatos);
		}
	}

	function up_data_client()
	{
		$this->load->library('user_agent');
		if ($this->input->method(TRUE) == "POST") {
			if ($this->agent->is_browser()) {
				$agent = $this->agent->browser() . ' ' . $this->agent->version();
				$opsys = $this->agent->platform();
			}
			$errors = false;
			$this->utils->begin();
			$id_trabajo_taller = $this->input->post("id_tr_taller");
			$id_venta = $this->input->post("id_vta");
			$concepto = $this->input->post("cncpto");
			$id_cliente = $this->input->post("id_client");
			//$vendedor = $this->input->post("vendedor");
			$clasifica = $this->input->post("clasifica");
			$nomcte = $this->input->post("nombre_cliente");
			$nomcomer = $this->input->post("nombre_cliente");
			$correlativo = $this->input->post("numero_doc");
			$tipo_pago = $this->input->post("tipo_pago");
			$efectivo = $this->input->post("efectivo");
			$direccion = "EL SALVADOR";
			$id_sucursal = $this->session->id_sucursal;
			$id_usuario = $this->session->id_usuario;
			$vendedor = $this->session->id_usuario;
			$fechahoy = date('Y-m-d');
			$hora = date("H:i:s");
			//datos de apertura actual
			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fechahoy);
			$id_apertura = $row_ap->id_apertura;
			$caja = $row_ap->caja;
			//fin datos de apertura actual

			$rowvta = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));
			$row_confdir = $this->taller->get_one_row("config_dir", array('id_sucursal' => $id_sucursal,));
			$tipodoc =	$rowvta->tipo_doc;
			$voucher = "";
			$credito = 0;
			$dias_credito = 0;
			$abono = 0;
			if ($tipo_pago == 0) {
				$tipo_pago = 1;
			}
			if ($tipo_pago == 2) { //si es pago con tarjeta traer el num voucher
				$voucher = $this->input->post("cambio");
			}
			if ($tipo_pago == 3) { //si es pago credito
				$dias_credito = $this->input->post("cambio");
				$credito = 1;
				$abono = $efectivo;
			}

			switch ($tipodoc) {
				case 1:
					$form_data = array(
						'nombre' => $nomcte,
						'nombre_comercial' => $nomcomer,
						'clasifica' => $clasifica,
						'activo' => 1,
					);
					$row_confpos = $this->taller->get_one_row("config_pos", array('id_sucursal' => $id_sucursal, 'alias_tipodoc' => 'tik',));
					//$correlativo1 = $this->taller->update_correlative('tik',$correlativo,$id_sucursal);
					break;
				case 2:

					$nit = $this->input->post("nit");
					$nrc = $this->input->post("nrc");
					$form_data = array(
						'nombre' => $nomcte,
						'nombre_comercial' => $nomcomer,
						'direccion' => $direccion,
						'clasifica' => $clasifica,
						'activo' => 1,
					);
					//$correlativo1 = $this->taller->update_correlative('cof',$correlativo,$id_sucursal);
					break;
				case 3:
					$nit = $this->input->post("nit");
					$nrc = $this->input->post("nrc");
					$form_data = array(
						'nombre' => $nomcte,
						'nombre_comercial' => $nomcomer,
						'direccion' => $direccion,
						'clasifica' => $clasifica,
						'nit' => $nit,
						'nrc' => $nrc,
						'activo' => 1,
					);
					//$correlativo1 = $this->taller->update_correlative('ccf',$correlativo,$id_sucursal);
					break;
			}
			if ($id_cliente < 0) {
				//$id_client=$id_cliente;
				if ($tipodoc > 1) {
					$id_cliente = $this->taller->inAndCon("clientes", $form_data);
				}
				if ($id_cliente == NULL) {
					$errors = true;
				} else {

					$form_cte = array(
						'id_cliente' => $id_cliente,
						//'correlativo'	=>$correlativo,
						'id_estado' => 2,
						'id_usuario' => $vendedor,
						"concepto" => $concepto,
						'tipo_pago' => $tipo_pago,
						'voucher_pago' => $voucher,
						'credito' => $credito,
						'dias_credito' => $dias_credito,
						'hora_fin' => $hora,
						'fecha' => $fechahoy, //add 13-01-2020
						'id_apertura' => $id_apertura,
						'caja' => $caja,
					);
					$this->utils->update("trabajos_taller", $form_cte, "id_trabajo_taller=$id_trabajo_taller");
					$form_cte['concepto'] = "FACTURACION DE TRABAJO DE TALLER NRO. " . $id_trabajo_taller;
					$this->utils->update("ventas", $form_cte, "id_venta=$id_venta");
					if ($tipo_pago == 3) { //si es pago credito
						$abono = $efectivo;
						//$saldo=$rowvta->total-$abono;
						$saldo = $abono;
						$t1 = "cuentas_por_cobrar";
						$t2 = "cuentas_por_cobrar_abonos";
						if ($saldo == 0) {
							$estado_cxc = 1;
						} else {
							$estado_cxc = 0;
						}
						$arr_cxc = array(
							'id_venta' => $id_venta,
							'abono'	=> 0,
							'saldo' => $saldo,
							'estado' => $estado_cxc,
						);
						$id_cxc = $this->taller->inAndCon($t1, $arr_cxc);
						//id_cuentas_por_cobrar, abono, fecha, hora
						/*
						$fecha_abono=date("Y-m-d");
						$arr_cxc_ab = array(
							'id_cuentas_por_cobrar' => $id_cxc,
							'abono'	=>$abono,
							'fecha'=>$fecha_abono,
							'hora' =>$hora,
						);
							$id_cxc_ab=$this->taller->inAndCon($t2,$arr_cxc_ab);
						*/
					}
				}
			} else {
				if ($tipodoc > 1) {
					$this->utils->update(
						"clientes",
						$form_data,
						"id_cliente=$id_cliente"
					);
				}
				$form_cte = array(
					'id_cliente' => $id_cliente,
					'id_usuario' => $vendedor,
					'correlativo'	=> $correlativo,
					'id_estado'	=> 2, //cambiar estadoa Finalizado id:2, cambiar despues
					"concepto" => $concepto,
					'tipo_pago' => $tipo_pago,
					'voucher_pago' => $voucher,
					'credito' => $credito,
					'dias_credito' => $dias_credito,
					'hora_fin' => $hora,
					'fecha' => $fechahoy, //add 13-01-2020
					'id_apertura' => $id_apertura,
					'caja' => $caja,
				);
				$this->utils->update("trabajos_taller", $form_cte, "id_trabajo_taller=$id_trabajo_taller");
				$this->utils->update("ventas", $form_cte, "id_venta=$id_venta");
				if ($tipo_pago == 3) { //si es pago credito
					$abono = $efectivo;
					//$saldo=$rowvta->total-$abono;
					$saldo = $abono;
					$t1 = "cuentas_por_cobrar";
					$t2 = "cuentas_por_cobrar_abonos";
					if ($saldo == 0) {
						$estado_cxc = 1;
					} else {
						$estado_cxc = 0;
					}
					$arr_cxc = array(
						'id_venta' => $id_venta,
						'abono'	=> 0,
						'saldo' => $saldo,
						'estado' => $estado_cxc,
					);
					$id_cxc = $this->taller->inAndCon($t1, $arr_cxc);
					//id_cuentas_por_cobrar, abono, fecha, hora
					/*
						$fecha_abono=date("Y-m-d");
						$arr_cxc_ab = array(
							'id_cuentas_por_cobrar' => $id_cxc,
							'abono'	=>$abono,
							'fecha'=>$fecha_abono,
							'hora' =>$hora,
						);
							$id_cxc_ab=$this->taller->inAndCon($t2,$arr_cxc_ab);
						*/
				}

				$id_client = $id_cliente;
			}
			if ($errors == true) {
				// code...
				$this->utils->rollback();
				$xdatos["type"] = "error";
				$xdatos['title'] = 'Alerta';
				$xdatos["msg"] = "Error al ingresar el registro";
				$xdatos["agent"] = $agent;
			} else {
				$this->utils->commit();
				$facturar = $correlativo;

				$tot_letras = NumeroALetras::convertir($rowvta->total, 'Dolares', false, 'centavos');
				$total_letras = wordwrap(strtoupper($tot_letras), 40) . "\n";

				switch ($tipodoc) {
					case 1:
						$xdatos = $this->print_ticket($id_trabajo_taller, $id_sucursal, $rowvta->correlativo, $rowvta->total, $rowvta, $vendedor);
						break;
					case 2:
						$xdatos = $this->print_cof($id_trabajo_taller, $id_sucursal, $rowvta);
						break;
					case 3:
						$xdatos = $this->print_ccf($id_trabajo_taller, $id_sucursal, $rowvta);
						break;
				}

				$xdatos["type"] = "success";
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Facturacion guardada correctamente!";
				$xdatos["tipodoc"] = $tipodoc;
				$xdatos["id_client"] = $id_cliente;
				$xdatos["total_letras"] = $total_letras;
				$xdatos["opsys"] = $opsys;
				$xdatos["dir_print"] = $row_confdir->dir_print_script; //for Linux
				$xdatos["dir_print_pos"] = $row_confdir->shared_printer_pos; //for win

			}
			echo json_encode($xdatos);
		}
	}
	function devolver($id = -1)
	{
		if ($this->input->method(TRUE) == "GET") {
			//apertura de caja
			$id_usuario = $this->session->id_usuario;
			$fecha = date('Y-m-d');
			$id_sucursal = $this->session->id_sucursal;
			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
			$usuario_ap = NULL;
			if ($row_ap != NULL) {
				$id_apertura = $row_ap->id_apertura;
				$usuario_ap =	$this->taller->get_one_row("usuario", array('id_usuario' => $row_ap->id_usuario,));
			}
			$row_clientes = $this->taller->get_detail_rows("clientes", array('null' => -1,)); //
			//fin apertura caja

			$id = $this->uri->segment(3);
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));
			$rowc = $this->taller->get_one_row("clientes", array('id_cliente' => $row->id_cliente,));
			$rowpc = $this->taller->get_porcent_client($rowc->clasifica);

			$detalles = $this->taller->get_detail_ci($id);
			$detalleservicios = $this->taller->get_detail_serv($id);
			$tipodoc =	$this->taller->get_tipodoc();
			$detalles1 = array();
			if ($detalles != NULL) {
				foreach ($detalles as $detalle) {
					$id_producto = $detalle->id_producto;
					$precio = $detalle->precio;
					$detallesp = $this->precios_producto($id_producto, $precio);
					if ($detallesp != 0) {
						$stock_data = $this->taller->get_stock($id_producto, $detalle->id_color, $row->id_sucursal);
						$detalle->precios = $detallesp["precios"];
						$detalle->stock = $stock_data->cantidad;
						$detalle->id_stock = $stock_data->id_stock;
						$detalle->id_color = $stock_data->id_color;

						$d = $this->taller->get_reservado($id_producto, $id, $detalle->id_color);
						$detalle->reservado = $d->reservado;

						$row_dev = $this->taller->get_dev_ante($id_producto, $id);
						$detalle->dev_ante = $row_dev->dev_ante;


						$estado = "<select class='est'>";
						if ($detalle->condicion == "NUEVO") {
							$estado .= "<option selected value='NUEVO'>NUEVO</option>";
							$estado .= "<option value='USADO'>USADO</option>";
						} else {
							$estado .= "<option value='NUEVO'>NUEVO</option>";
							$estado .= "<option selected value='USADO'>USADO</option>";
						}
						$estado .= "</select>";

						$detalle->estado = $estado;
					}
					array_push($detalles1, $detalle);
				}
			}
			$detalles2 = array();
			if ($detalleservicios != NULL) {

				foreach ($detalleservicios as $detalleserv) {
					$id_producto = $detalleserv->id_producto;
					$row_dev = $this->taller->get_dev_ante($id_producto, $id);
					$detalleserv->dev_ante = $row_dev->dev_ante;
					if ($row_dev != NULL)
						$detalleserv->dev_ante = $row_dev->dev_ante;
					else {
						$detalleserv->dev_ante = 0;
					}
					array_push($detalles2, $detalleserv);
				}
			}


			if ($row && $id != "") {

				$id_usuario = $this->session->id_usuario;
				$fecha = date('Y-m-d');
				$data = array(
					"row" => $row,
					"detalles" => $detalles1,
					"detalleservicios" => $detalles2,
					'tipodoc' => $tipodoc,
					'rowpc' => $rowpc,
					"sucursal" => $this->taller->get_detail_rows("sucursales", array('1' => 1,)),
					"id_sucursal" => $row->id_sucursal,
					"rowc" => $rowc,
					"id_usuario" => $id_usuario,
					"row_ap" => $row_ap,
					"usuario_ap" => $usuario_ap,
				);
				$extras = array(
					'css' => array(
						"css/scripts/ventas.css"
					),
					'js' => array(
						"js/scripts/ventas.js"
					),
				);
				layout("ventas/devolver", $data, $extras);
			} else {
				redirect('errorpage');
			}
		} else if ($this->input->method(TRUE) == "POST") {
			$this->utils->begin();
			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
			$fecha_venta = Y_m_d($this->input->post("fecha"));
			$total_dev = $this->input->post("total");
			$id_cliente = $this->input->post("id_cliente");
			$data_ingreso = json_decode($this->input->post("data_ingreso"), true);

			$rowdoc = $this->taller->get_tipodoc_alias("DEV");
			$id_sucursal = $this->session->id_sucursal;
			$correlativo = $this->taller->get_correlative('dev', $id_sucursal);

			$totcant = count($data_ingreso);
			$id_usuario = $this->session->id_usuario;
			$hora = date("H:i:s");
			$fecha_dev = date('Y-m-d');
			//insertar EN la tabla devolucion  el encabezado
			$tabla = "devoluciones";
			$form_data = array(
				'id_trabajo_taller' => $id_trabajo_taller,
				'cant' => $totcant,
				'monto' => $total_dev,
				'fecha' => $fecha_dev,
				'hora' => $hora,

			);

			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha_dev);
			$id_apertura = $row_ap->id_apertura;
			$caja = $row_ap->caja;
			$correlativo1 = $this->taller->update_correlative('dev', $correlativo, $id_sucursal);
			$id_dev = $this->taller->inAndCon($tabla, $form_data);
			//se inserta tambien en la tabla ventas como tipo devolucion ID 4 DEV
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));
			$id_sucursal = $row->id_sucursal;
			if ($rowdoc != NULL)
				$tipodoc = $rowdoc->idtipodoc;

			$concepto = "DEVOLUCION";
			$data = array(
				'fecha' => $fecha_dev,
				'hora' => $hora,
				'concepto' => $concepto,
				'id_cliente' => $id_cliente,
				'id_estado' => 2,
				'id_sucursal_despacho' => $id_sucursal,
				'total' => $total_dev,
				'id_sucursal' => $id_sucursal,
				'id_usuario' => $id_usuario,
				'tipo_doc' => $tipodoc,
				'referencia' => 0,
				'correlativo' => $correlativo,
				'caja' => $caja,
				'id_apertura' => $id_apertura,
				'id_devolucion' => $id_dev,
			);
			$tabla1 = "trabajos_taller";
			$id_dev_vta = $this->taller->inAndCon($tabla1, $data);

			/*nuevos detalles*/
			if ($data_ingreso != NULL) {
				foreach ($data_ingreso as $fila) {
					$id_producto = $fila['id_producto'];
					$costo = $fila['costo'];
					$cantidad = $fila['cantidad'];
					$id_detalle = $fila['id_detalle'];
					$cant_dev = $fila['cant_dev'];
					$precio_final = $fila['precio_final'];
					$subtotal = $fila['subtotal'];
					$color = $fila['color'];
					$estado = $fila['est'];
					$tipo = $fila['tipo_prod']; //"0:PRODUCTO,1:SERVICIO"

					$form_data = array(
						'id_trabajo_taller' => $id_dev_vta,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'costo' => $costo,
						'precio' => $precio_final,
						'precio_fin' => $precio_final,

						'cantidad' => $cant_dev,
						'subtotal' => $subtotal,
						'condicion' => $estado,
						'tipo_prod' => $tipo,
					);
					//se inserta en la tabla venta_detalle c/u de los items
					$id_detalle = $this->taller->inAndCon('trabajos_taller_detalle', $form_data);


					if ($tipo == 0) {
						$stock_data = $this->taller->get_stock($id_producto, $color, $id_sucursal);
						$newstock = ($stock_data->cantidad) + $cant_dev;
						$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
					}

					$tabla2 = 'devoluciones_det';
					$form_data2 = array(
						'id_dev' => $id_dev,
						'id_trabajo_taller' => $id_trabajo_taller,
						'id_producto' => $id_producto,
						'cant' => $cant_dev,
						'monto' => $costo,
						'id_trabajo_taller_detalle' => $id_detalle,
					);
					$insertar = $this->taller->inAndCon($tabla2, $form_data2);
				}
			}

			$this->utils->commit();
			$xdatos["type"] = "success";
			$xdatos['title'] = 'Informaci??n';
			$xdatos["msg"] = "Facturacion guardada correctamente!";
			$xdatos["proceso"] = "finalizar";

			echo json_encode($xdatos);
		}
	}
	function imei($id = -1)
	{
		if ($this->input->method(TRUE) == "GET") {
			$id = $this->uri->segment(3);
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));
			if ($row && $id != "") {
				$data = array(
					"row" => $row,
					"detalles" => $this->taller->get_detail_ci($id),
				);
				$extras = array(
					'css' => array(),
					'js' => array(
						"js/scripts/ventas_imei.js"
					),
				);
				layout("ventas/cargaimei", $data, $extras);
			} else {
				redirect('errorpage');
			}
		} else if ($this->input->method(TRUE) == "POST") {
			$this->utils->begin();

			$errors = false;
			$array_error = array("Log");
			$data_ingreso = json_decode($this->input->post("data_ingreso"), true);
			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
			foreach ($data_ingreso as $fila) {
				// code...
				$form_data = array(
					'id_producto' => $fila['id_producto'],
					'imei' => $fila['imei'],
					'id_detalle' => $fila['id_detalle'],
					'chain' => $fila['chain'],
					'id_trabajo_taller' => $id_trabajo_taller,
					'vendido' => 1,
				);

				$id_detalle = $this->taller->inAndCon('ventas_imei', $form_data);

				if ($id_detalle == NULL) {
					// code...
					$errors = true;
				}
			}

			if ($errors == true) {
				// code...
				$this->utils->rollback();
				$xdatos["type"] = "error";
				$xdatos['title'] = 'Alerta';
				$xdatos["msg"] = "Error al ingresar el registro";
			} else {
				// code...
				$this->utils->update("trabajos_taller", array('imei_ingresado' => 1,), "id_trabajo_taller=$id_trabajo_taller");
				$this->utils->commit();
				$xdatos["type"] = "success";
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Registo ingresado correctamente!";
			}

			echo json_encode($xdatos);
		}
	}
	function editarimei($id = -1)
	{
		if ($this->input->method(TRUE) == "GET") {
			$id = $this->uri->segment(3);
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id,));

			$info = $this->taller->get_imei_ci($id);
			$detalles = array();
			$c = 0;
			foreach ($info as $key) {
				// code...
				$detalles[$c] = array(
					'id_trabajo_taller' => $key->id_trabajo_taller,
					'id_producto' => $key->id_producto,
					'id_detalle' => $key->id_detalle,
					'nombre' => $key->nombre,
					'chain' => $key->chain,
					'data' => $this->taller->get_imei_ci_det($key->chain),
				);
				$c++;
			}

			if ($row && $id != "") {
				$data = array(
					"row" => $row,
					"detalles" => $detalles,
				);
				$extras = array(
					'css' => array(),
					'js' => array(
						"js/scripts/ventas_imei.js"
					),
				);
				layout("ventas/editarimei", $data, $extras);
			} else {
				redirect('errorpage');
			}
		} else if ($this->input->method(TRUE) == "POST") {
			$this->utils->begin();

			$errors = false;
			$array_error = array("Log");
			$data_ingreso = json_decode($this->input->post("data_ingreso"), true);
			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
			foreach ($data_ingreso as $fila) {
				// code...
				$form_data = array(
					'imei' => $fila['imei'],
				);

				$this->utils->update("ventas_imei", $form_data, "id_imei=$fila[id_imei]");
			}

			$this->utils->commit();
			$xdatos["type"] = "success";
			$xdatos['title'] = 'Informaci??n';
			$xdatos["msg"] = "Registo ingresado correctamente!";


			echo json_encode($xdatos);
		}
	}
	function delete()
	{
		if ($this->input->method(TRUE) == "POST") {
			$id_trabajo_taller = $this->input->post("id");
			$this->utils->begin();
			$row = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));
			$id_sucursal = $row->id_sucursal;
			/*descargar los detalles previos*/
			$detalles_previos = $this->taller->get_detail_rows("trabajos_taller_detalle", array('id_trabajo_taller' => $id_trabajo_taller,));
			if ($detalles_previos) {
				foreach ($detalles_previos as $key) {
					if ($key->tipo_prod == 0) {
						$stock_data = $this->taller->get_stock($key->id_producto, $key->id_color, $id_sucursal);
						$newstock = ($stock_data->cantidad) + ($key->cantidad);
						$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
					}
				}
			}
			/*eliminar detalles previos*/
			$this->utils->delete("trabajos_taller_detalle", "id_trabajo_taller=$id_trabajo_taller");
			$this->utils->delete("trabajos_taller", "id_trabajo_taller=$id_trabajo_taller");


			$this->utils->commit();
			$response["type"] = "success";
			$response["title"] = "Informaci??n";
			$response["msg"] = "Registro eliminado con ??xito!";

			echo json_encode($response);
		}
	}
	function change()
	{
		if ($this->input->method(TRUE) == "POST") {
			$id_trabajo_taller = $this->input->post("id");
			$id_estado = $this->input->post("id_estado");
			$this->utils->begin();
			$this->utils->update("trabajos_taller", array('id_estado' => $id_estado,), "id_trabajo_taller=$id_trabajo_taller");
			$this->utils->commit();
			$response["type"] = "success";
			$response["title"] = "Informaci??n";
			$response["msg"] = "Registro editado con ??xito!";
			echo json_encode($response);
		}
	}
	function garantia($id = -1)
	{
		if ($this->input->method(TRUE) == "GET") {

			$id = $this->uri->segment(3);
			$this->load->library('GarantiaReport');
			$pdf = $this->garantiareport->getInstance('P', 'mm', 'Letter');
			$logo = base_url() . "assets/img/logo.png";
			$pdf->SetMargins(6, 10);
			$pdf->SetLeftMargin(5);
			$pdf->AliasNbPages();
			$pdf->SetAutoPageBreak(true, 15);
			$pdf->AliasNbPages();

			$vg = $this->taller->get_venta($id);
			$id_sucursal = $vg->id_sucursal_despacho;
			$this->db->where("id_sucursal", $id_sucursal);
			$q = $this->db->get("sucursales");
			$dat = $q->row();
			$data = array("empresa" => $dat->nombre, "imagen" => $logo, 'fecha' => $this->input->post("fecha1"));
			$pdf->setear($data);
			$pdf->addPage();

			$l = array(
				's' => 10,
				'c' => 50,
				'v' => 50,
			);
			$array_data = array(
				array('', $l['s'], "C"),
				array("DATOS", $l['c'], "L"),
				array("DETALLE", $l['v'], "L"),
			);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->LineWrite($array_data);

			$pdf->SetFont('Arial', '', 10);
			$array_data = array(
				array('', $l['s'], "C"),
				array("Nombre", $l['c'], "L"),
				array($vg->nombre, $l['v'], "L"),
			);
			$pdf->LineWrite($array_data);

			$array_data = array(
				array('', $l['s'], "C"),
				array("Fecha de Compra", $l['c'], "L"),
				array(d_m_Y($vg->fecha), $l['v'], "L"),
			);
			$pdf->LineWrite($array_data);
			$pdf->Ln(5);

			$pdf->SetFont('Arial', 'B', 9);
			$l = array(
				's' => 10,
				'ma' => 30,
				'mo' => 30,
				'ime' => 65,
				'con' => 20,
				'tim' => 45
			);
			$array_data = array(
				array('', $l['s'], "C"),
				array('Marca', $l['ma'], "L"),
				array('Modelo', $l['mo'], "L"),
				array('Imei', $l['ime'], "C"),
				array('Condici??n', $l['con'], "C"),
				array('Tiempo de Garantia (Dias)', $l['tim'], "C"),

			);
			$pdf->LineWriteB($array_data);

			$pdf->SetFont('Arial', '', 9);

			$dat = $this->taller->get_detail_ci($id);
			foreach ($dat as $key) {

				$im =  $this->taller->get_imei_productos($key->id_detalle);
				$imei = "";

				if ($im) {
					// code...
					$arim = array();
					$p = 0;
					foreach ($im as $ke) {
						$arim[$p] = $ke->imei;
						$p++;
					}

					$imei = implode(", ", $arim);
				}
				$array_data = array(
					array('', $l['s'], "C"),
					array($key->cantidad . "x " . $key->marca, $l['ma'], "L"),
					array($key->modelo, $l['mo'], "L"),
					array($imei, $l['ime'], "C"),
					array($key->condicion, $l['con'], "C"),
					array($key->garantia, $l['tim'], "C"),

				);
				$pdf->LineWriteB($array_data);
			}
			$pdf->Ln(5);
			//function SetStyle($tag, $family, $style, $size, $color, $indent=-1)
			$pdf->SetStyle("p", "arial", "N", 9, "0,0,0", 0);
			$pdf->SetStyle("b", "arial", "BN", 9, "0,0,0");

			$dat = $this->taller->get_one_row("report_parrafo", array('tipo' => "GarantiaE",));
			$pdf->WriteTag(0, 4, $dat->texto, 0, "J", 0, 0);
			$pdf->Ln(5);

			$this->db->where("tipo", "GarantiaEX");
			$this->db->where("id_sucursal", $id_sucursal);
			$this->db->order_by('orden', 'ASC');
			$query = $this->db->get("report_detail");

			$dat = $query->result();
			$l = array(
				's' => 9,
				'c' => 5,
				'v' => 192,
			);
			foreach ($dat as $key) {
				// code...
				$array_data = array(
					array('', $l['s'], "C"),
					array(("*"), $l['c'], "R"),
					array($key->texto, $l['v'], "L"),
				);
				$pdf->LineWrite($array_data);
			}

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(205, 10, "Procedimiento a seguir en caso de garant??a:", 0, 1, 'L');

			$this->db->where("tipo", "GarantiaEC");
			$this->db->where("id_sucursal", $id_sucursal);
			$this->db->order_by('orden', 'ASC');
			$query = $this->db->get("report_detail");

			$pdf->SetFont('Arial', '', 9);
			$dat = $query->result();
			$i = 1;
			foreach ($dat as $key) {
				// code...
				$array_data = array(
					array('', $l['s'], "C"),
					array(("$i."), $l['c'], "R"),
					array($key->texto, $l['v'], "L"),
				);
				$pdf->LineWrite($array_data);
				$i++;
			}

			$pdf->Output();
		} else {
			redirect('errorpage');
		}
	}

	// FUNCIONES PARA GENERAR LA FACTURACION
	public function detalle_servicio($id = 0)
	{
		if ($id == 0) {
			$id_producto = $this->input->post("id");
			$id_color = $this->input->post("id_s");
			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
		}
		//SELECT `id_servicio`, `id_categoria`, `nombre`, `costo_s_iva`, `costo_c_iva`, `cesc`,
		//`precio_sugerido`, `precio_minimo`, `dias_garantia`, `activo`, `deleted` FROM `servicio` WHERE 1
		$prods = $this->taller->get_row_servicios($id_producto);
		$xdatos["precio_sugerido"] = $prods->precio_sugerido;
		$xdatos["precio_minimo"] = $prods->precio_minimo;
		$xdatos["costo"] = number_format($prods->costo_s_iva, 2, ".", "");
		$xdatos["costo_iva"] = number_format($prods->costo_c_iva, 2, ".", "");
		echo json_encode($xdatos);
	}
	public function detalle_producto($id = 0)
	{
		$id_sucursal = $this->session->id_sucursal;
		if ($id == 0) {
			$id_producto = $this->input->post("id");
			$clasifica = $this->input->post("clasifica");
			$id_color = $this->input->post("id_s");
			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
		}
		$lista = "";
		if ($id_color != -1)
			$stock_data = $this->taller->get_stock($id_producto, $id_color, $id_sucursal);
		$prods = $this->taller->get_producto($id_producto);
		$preciosS = $this->taller->get_detail_rows("producto_precio", array('id_producto' => $id_producto, 'id_listaprecio' => $clasifica));

		$precios = $this->taller->get_detail_rows("producto_precio", array('id_producto' => $id_producto));
		$colores = $this->taller->get_detail_rows("producto_color", array('id_producto' => $id_producto,));

		$d = $this->taller->get_reservado($id_producto, $id_trabajo_taller, $id_color);

		$color_select = "";
		if ($colores) {
			$color_select .= "<select class='form-control color' style='width:100%;'>";
			foreach ($colores as $key) {
				$color_select .= "<option value='" . $key->id_color . "'>" . $key->color . "</option>";
			}
			$color_select .= "/<select>";
		} else {
			$color_select .= "<select class='form-control color sel' style='width:100%;'>";
			$color_select .= "<option value='0'>SIN COLOR</option>";
			$color_select .= "/<select>";
		}

		$lista .= "<select class='form-control precios sel' style='width:100%;'>";
		$costo = 0;
		$costo_iva = 0;
		//echo $preciosS[0]->id_precio."#";
		foreach ($precios as $row_por) {
			$id_porcentaje = $row_por->id_precio;
			$costo = $row_por->costo;
			$costo_iva = $row_por->costo_iva;

			$precio = $row_por->porcentaje;
			if ($preciosS[0]->id_precio == $row_por->id_precio) {
				$lista .= "<option value='" . $precio . "' precio='" . $precio . "' id_precio='" . $row_por->id_precio . "' selected>" . number_format($precio, 2, ".", ",") . "</option>";
			} else {
				// code...
				$lista .= "<option value='" . $precio . "' precio='" . $precio . "' id_precio='" . $row_por->id_precio . "'>" . number_format($precio, 2, ".", ",") . "</option>";
			}
		}
		$lista .= "</select>";
		$xdatos["precio_sugerido"] = $prods->precio_sugerido;
		$xdatos["precio_ini"] = $precio;
		$xdatos["precios"] = $lista;
		$xdatos["stock"] = $stock_data->cantidad + $d->reservado;
		$xdatos["id_s"] = $stock_data->id_stock;
		$xdatos["marca"] = $prods->marca;
		$xdatos["modelo"] = $prods->modelo;
		$xdatos["costo"] = number_format($costo, 2, ".", "");
		$xdatos["costo_iva"] = number_format($costo_iva, 2, ".", "");
		echo json_encode($xdatos);
	}
	public function precios_producto($id = 0, $precioe = 0)
	{
		$id_sucursal = $this->session->id_sucursal;

		$precios = $this->taller->get_precios_exis($id);

		// $precios=$this->taller->get_detail_rows("producto_precio",array('id_producto' =>$id_producto,'id_listaprecio'=>$clasifica));
		$lista = "";
		$lista .= "<select class='form-control precios sel' style='width:100%;'>";
		$costo = 0;
		$costo_iva = 0;
		foreach ($precios as $row_por) {
			$id_porcentaje = $row_por->id_precio;
			$costo = $row_por->costo;
			$costo_iva = $row_por->costo_iva;
			//$precio = $row_por->total_iva;
			$precio = $row_por->precio_venta;
			$lista .= "<option value='" . $precio . "' precio='" . $precio . "'";
			if ($precio == $precioe) {
				$lista .= " selected ";
			}
			$lista .= ">$" . number_format($precio, 2, ".", ",") . "</option>";
		}
		$lista .= "</select>";
		$xdatos["precios"] = $lista;
		return $xdatos;
	}
	function get_productos()
	{
		$query = $this->input->post("query");
		$id_sucursal = $this->input->post("id_sucursal");
		$rows = $this->taller->get_productos($query, $id_sucursal);
		$rows2 = $this->taller->get_servicios($query, $id_sucursal);
		$output = array();
		if ($rows != NULL) {
			foreach ($rows as $row) {
				$output[] = array(

					'producto' => $row->id_producto . "|" . $row->nombre . " " . $row->marca . " " . $row->modelo . " " . $row->color . "|" . $row->id_color,
				);
			}
		}
		if ($rows2 != NULL) {
			foreach ($rows2 as $row) {
				$output[] = array(

					'producto' => $row->id_servicio . "|" . $row->nombre . " (SERVICIO)" . "|" . "SERVICIO",
				);
			}
		}
		echo json_encode($output);
	}
	function get_clientes()
	{
		$query = $this->input->post("query");
		$rows = $this->taller->get_clientes($query);
		$output = array();
		if ($rows != NULL) {
			foreach ($rows as $row) {
				$output[] = array(
					//'producto' => $row->id_producto."|".$row->nombre." ".$row->marca." ".$row->modelo,
					'cliente' => $row->id_cliente . "|" . $row->nombre . "|" . $row->clasifica,
				);
			}
		}
		echo json_encode($output);
	}
	function get_porcent_cliente()
	{
		$clasifica = $this->input->post("clasifica");
		$row = $this->taller->get_one_row("clientes", array('activo' => 1, 'deleted' => 0, 'id_cliente' => $clasifica));
		//fin apertura caja
		//$row = $this->taller->get_porcent_client($clasifica);
		if ($row != NULL) {
			$porcent_clasifica = $row->clasifica;
		} else {
			$porcent_clasifica = 0;
		}
		$xdatos["porc_clasifica"] = $porcent_clasifica;
		$xdatos["type"] = "success";
		$xdatos['title'] = 'Alerta';
		$xdatos["msg"] = "Cliente Seleccionado";
		echo json_encode($xdatos);
	}



	// FUNCIONES PARA GUARDAR LA INFO DE FACTURACION
	function facturar()
	{
		if ($this->input->method(TRUE) == "GET") {
			$tipodoc =	$this->taller->get_tipodoc();
			$id_usuario = $this->session->id_usuario;
			$id_sucursal = $this->session->id_sucursal;
			$fecha = date('Y-m-d');
			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
			$usuario_ap = NULL;
			if ($row_ap != NULL) {
				$id_apertura = $row_ap->id_apertura;
				$usuario_ap =	$this->taller->get_one_row("usuario", array('id_usuario' => $row_ap->id_usuario,));
			}
			$row_clientes = $this->taller->get_detail_rows("clientes", array('null' => -1,)); //
			$data = array(
				"sucursal" => $this->taller->get_detail_rows("sucursales", array('1' => 1,)),
				"id_sucursal" => $this->session->id_sucursal,
				"tipodoc" => $tipodoc,
				"row_ap" => $row_ap,
				"row_clientes" => $row_clientes,
				"id_usuario" => $id_usuario,
				"usuario_ap" => $usuario_ap,
			);

			$extras = array(
				'css' => array(
					"css/scripts/taller.css"
				),
				'js' => array(
					"js/scripts/taller.js"
				),
			);

			layout("taller/facturar", $data, $extras);
		} else if ($this->input->method(TRUE) == "POST") {
			$this->load->model("ProductosModel", "productos");
			$this->utils->begin();
			$concepto = $this->input->post("concepto");
			$fecha = Y_m_d($this->input->post("fecha"));
			$total = $this->input->post("total");

			$id_cliente = $this->input->post("client");
			$data_ingreso = json_decode($this->input->post("data_ingreso"), true);
			$id_sucursal = $this->input->post("id_sucursal");
			$tipodoc = $this->input->post("tipodoc");
			$id_usuario = $this->session->id_usuario;
			$hora = date("H:i:s");
			$fecha_corr = $this->taller->get_date_correlative($id_sucursal);

			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
			$id_apertura = $row_ap->id_apertura;
			$caja = $row_ap->caja;
			/*
			if($fecha==$fecha_corr){
				$referencia = $this->taller->get_correlative('refdia',$id_sucursal);
	       $this->utils->update("correlativo",array("refdia" =>$referencia, ),"id_sucursal=".$id_sucursal);
			}else{
					$referencia =1;
					$this->utils->update("correlativo",array('fecha' => $fecha,"refdia" =>$referencia, ),"id_sucursal=".$id_sucursal);
			}
			*/
			$referencia = 0;
			switch ($tipodoc) {
				case 1:
					$correlativo = $this->taller->get_correlative('tik', $id_sucursal);
					break;
				case 2:
					$correlativo = $this->taller->get_correlative('cof', $id_sucursal);
					break;
				case 3:
					$correlativo = $this->taller->get_correlative('ccf', $id_sucursal);
					break;
			}

			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'indicaciones' => "TRABAJO DE TALLER",
				'id_cliente' => $id_cliente,
				'id_estado' => 1, //probar cambiar a estado 1 pendiente para ver lo de actualizarlo a la hora de activar form data_client finalizar!!!!!!!
				'id_sucursal_despacho' => $id_sucursal,
				'correlativo' => $correlativo,
				'total' => $total,
				'id_sucursal' => $id_sucursal,
				'id_usuario' => $id_usuario,
				'tipo_doc' => $tipodoc,
				'referencia' => 0,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
				'guia' => "",
				'caja' => $caja,
				'id_apertura' => $id_apertura,
			);

			$imei_required = false;

			$id_factura = $this->taller->inAndCon('trabajos_taller', $data);
			if ($id_factura != NULL) {
				if ($data_ingreso != NULL) {
					foreach ($data_ingreso as $fila) {
						$id_producto = $fila['id_producto'];
						$costo = $fila['costo'];
						$cantidad = $fila['cantidad'];
						$precio_sugerido = $fila['precio_sugerido'];
						$descuento = $fila['descuento'];
						$precio_final = $fila['precio_final'];
						$subtotal = $fila['subtotal'];
						$color = $fila['color'];
						$tipo = $fila['tipo']; //"0:PRODUCTO,1:SERVICIO"

						$estado = $fila['est'];

						$form_data = array(
							'id_trabajo_taller' => $id_factura,
							'id_producto' => $id_producto,
							'id_color' => $color,
							'costo' => $costo,
							'precio' => $precio_sugerido,
							'precio_fin' => $precio_final,
							'descuento' => $descuento,
							'cantidad' => $cantidad,
							'subtotal' => $subtotal,
							'condicion' => $estado,
							'tipo_prod' => $tipo,
							'garantia' =>  $this->taller->getGarantia($id_producto, $estado),
						);
						$id_detalle = $this->taller->inAndCon('trabajos_taller_detalle', $form_data);
						if ($tipo == 0) {
							$stock_data = $this->taller->get_stock($id_producto, $color, $id_sucursal);
							$newstock = ($stock_data->cantidad) - $cantidad;
							$this->utils->update("stock", array('cantidad' => $newstock,), "id_stock=" . $stock_data->id_stock);
						}
						if ($this->taller->has_imei_required($id_producto)) {
							$imei_required = true;
						}
					}
				}
				if ($imei_required) {
					// code...
					$this->utils->update("trabajos_taller", array('requiere_imei' => 1,), "id_trabajo_taller=$id_factura");
				}
				$this->utils->commit();
				$xdatos["type"] = "success";
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Registo ingresado correctamente!";
				$xdatos["id_factura"] = $id_factura;
				$xdatos["proceso"] = "facturar";
			} else {
				$this->utils->rollback();
				$xdatos["type"] = "error";
				$xdatos['title'] = 'Alerta';
				$xdatos["msg"] = "Error al ingresar el registro";
			}


			echo json_encode($xdatos);
		}
	}
	//crear formato impresion ticket
	function finalizaref()
	{
		if ($this->input->method(TRUE) == "GET") {
			$tipodoc =	$this->taller->get_tipodoc();
			$id_usuario = $this->session->id_usuario;
			$id_sucursal = $this->session->id_sucursal;
			$fecha = date('Y-m-d');
			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
			$usuario_ap = NULL;
			if ($row_ap != NULL) {
				$id_apertura = $row_ap->id_apertura;
				$usuario_ap =	$this->taller->get_one_row("usuario", array('id_usuario' => $row_ap->id_usuario,));
			}
			$row_clientes = $this->taller->get_detail_rows("clientes", array('null' => -1,)); //
			$data = array(
				"sucursal" => $this->taller->get_detail_rows("sucursales", array('1' => 1,)),
				"id_sucursal" => $this->session->id_sucursal,
				"tipodoc" => $tipodoc,
				"row_ap" => $row_ap,
				"row_clientes" => $row_clientes,
				"id_usuario" => $id_usuario,
				"usuario_ap" => $usuario_ap,
			);

			$extras = array(
				'css' => array(
					"css/scripts/ventas.css"
				),
				'js' => array(
					"js/scripts/ventas.js"
				),
			);

			layout("ventas/finalizaref", $data, $extras);
		}
	}
	//cargar venta por referencia
	function cargar_venta($id = -1)
	{
		if ($this->input->method(TRUE) == "POST") {
			$fecha = date('Y-m-d');
			$referencia = $this->input->post("referencia");
			$venta =	$this->taller->get_one_row("trabajos_taller", array('referencia' => $referencia, 'fecha' => $fecha, "id_estado" => 1,));

			if ($venta != NULL) {
				$id = $venta->id_trabajo_taller;
				$detalles = $this->taller->get_detail_ci($id);
				$rowc = $this->taller->get_one_row("clientes", array('id_cliente' => $venta->id_cliente,));
				$rowpc = $this->taller->get_porcent_client($rowc->clasifica);
				$clasifica = $rowc->clasifica;
				$detalles1 = array();
				if ($detalles != NULL) {

					foreach ($detalles as $detalle) {
						$id_producto = $detalle->id_producto;
						$precio = $detalle->precio;
						$qty_sold = $detalle->cantidad;
						$preciosS = $this->taller->get_detail_rows("producto_precio", array('id_producto' => $id_producto, 'id_listaprecio' => $clasifica));

						$precios = $this->taller->get_detail_rows("producto_precio", array('id_producto' => $id_producto));
						$stock_data = $this->taller->get_stock($id_producto, $detalle->id_color, $venta->id_sucursal);
						$lista = "";
						$lista .= "<select class='form-control precios sel' style='width:100%;'>";
						$costo = 0;
						$costo_iva = 0;
						foreach ($precios as $row_por) {
							$id_porcentaje = $row_por->id_precio;
							$costo = $row_por->costo;
							$costo_iva = $row_por->costo_iva;
							$precio = $row_por->porcentaje;
							//echo $preciosS[0]->id_precio;
							if ($detalle->id_precio_producto == $row_por->id_precio) {
								// code...
								$lista .= "<option value='" . $precio . "' precio='" . $precio . "' id_precio='" . $row_por->id_precio . "' selected>" . number_format($precio, 2, ".", ",") . "</option>";
							} else {
								// code...
								$lista .= "<option value='" . $precio . "' precio='" . $precio . "' id_precio='" . $row_por->id_precio . "'>" . number_format($precio, 2, ".", ",") . "</option>";
							}
						}
						$lista .= "</select>";
						$detalle->precios = $lista;
						$detalle->stock = $stock_data->cantidad + $qty_sold;
						$detalle->id_stock = $stock_data->id_stock;
						$detalle->id_color = $stock_data->id_color;
						$d = $this->taller->get_reservado($id_producto, $id, $detalle->id_color);
						$detalle->reservado = $d->reservado;

						$estado = "<select class='est'>";
						if ($detalle->condicion == "NUEVO") {
							$estado .= "<option selected value='NUEVO'>NUEVO</option>";
							$estado .= "<option value='USADO'>USADO</option>";
						} else {
							$estado .= "<option value='NUEVO'>NUEVO</option>";
							$estado .= "<option selected value='USADO'>USADO</option>";
						}
						$estado .= "</select>";

						$detalle->estado = $estado;
						//}
						array_push($detalles1, $detalle);
					}
				}
				$detalleservicios = $this->taller->get_detail_serv($id);
				$xdatos['id_cliente'] = $venta->id_cliente;
				$fecha_dmy = d_m_Y($venta->fecha);

				$xdatos["venta"] = $venta;
				$xdatos["id_trabajo_taller"] = $venta->id_trabajo_taller;
				$xdatos["fecha"] = $fecha_dmy;
				$xdatos["total"] = $venta->total;
				$xdatos["tipo_doc"] = $venta->tipo_doc;
				$xdatos["detprod"] = $detalles1;
				$xdatos["detserv"] = $detalleservicios;
				$xdatos["type"] = "success";
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Venta cargada correctamente!";
				$xdatos["proceso"] = "cargar";
			} else {
				$xdatos["type"] = "error";
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Referencia de Venta no Encontrada!";
				$xdatos["proceso"] = "finalizar";
			}
			echo json_encode($xdatos);
		}
	}

	//cargar datos de  venta por referencia
	function cargar_ref($id = -1)
	{

		if ($this->input->method(TRUE) == "GET") {
			//$referencia= $this->uri->segment(3);
			$referencia = $this->input->get("referencia");
			$fecha = date('Y-m-d');

			$venta =	$this->taller->get_one_row("trabajos_taller", array('referencia' => $referencia, 'fecha' => $fecha,));
			$row =	$this->taller->get_one_row("trabajos_taller", array('referencia' => $referencia, 'fecha' => $fecha,));
			$id = $venta->id_trabajo_taller;

			$id_usuario = $this->session->id_usuario;
			$fecha = date('Y-m-d');
			$id_sucursal = $this->session->id_sucursal;
			$row_ap = $this->taller->get_caja_activa($id_sucursal, $id_usuario, $fecha);
			$usuario_ap = NULL;
			if ($row_ap != NULL) {
				$id_apertura = $row_ap->id_apertura;
				$usuario_ap =	$this->taller->get_one_row("usuario", array('id_usuario' => $row_ap->id_usuario,));
			}
			$row_clientes = $this->taller->get_detail_rows("clientes", array('null' => -1,)); //
			//fin apertura caja


			$rowc = $this->taller->get_one_row("clientes", array('id_cliente' => $row->id_cliente,));
			$rowpc = $this->taller->get_porcent_client($rowc->clasifica);

			$detalles = $this->taller->get_detail_ci($id);
			$detalleservicios = $this->taller->get_detail_serv($id);
			$tipodoc =	$this->taller->get_tipodoc();
			$detalles1 = array();
			if ($detalles != NULL) {
				foreach ($detalles as $detalle) {
					$id_producto = $detalle->id_producto;
					$precio = $detalle->precio;
					$detallesp = $this->precios_producto($id_producto, $precio);
					if ($detallesp != 0) {
						$stock_data = $this->taller->get_stock($id_producto, $detalle->id_color, $row->id_sucursal);
						$detalle->precios = $detallesp["precios"];
						$detalle->stock = $stock_data->cantidad;
						$detalle->id_stock = $stock_data->id_stock;
						$detalle->id_color = $stock_data->id_color;

						$d = $this->taller->get_reservado($id_producto, $id, $detalle->id_color);
						$detalle->reservado = $d->reservado;

						$estado = "<select class='est'>";
						if ($detalle->condicion == "NUEVO") {
							$estado .= "<option selected value='NUEVO'>NUEVO</option>";
							$estado .= "<option value='USADO'>USADO</option>";
						} else {
							$estado .= "<option value='NUEVO'>NUEVO</option>";
							$estado .= "<option selected value='USADO'>USADO</option>";
						}
						$estado .= "</select>";

						$detalle->estado = $estado;
					}
					array_push($detalles1, $detalle);
				}
			}
			if ($row && $id != "") {

				$id_usuario = $this->session->id_usuario;
				$fecha = date('Y-m-d');
				$data = array(
					"row" => $row,
					"detalles" => $detalles1,
					"detalleservicios" => $detalleservicios,
					'tipodoc' => $tipodoc,
					'rowpc' => $rowpc,
					"sucursal" => $this->taller->get_detail_rows("sucursales", array('1' => 1,)),
					"id_sucursal" => $row->id_sucursal,
					"rowc" => $rowc,
					"row_clientes" => $row_clientes,
					"id_usuario" => $id_usuario,
					"row_ap" => $row_ap,
					"usuario_ap" => $usuario_ap,
				);
				$extras = array(
					'css' => array(
						"css/scripts/ventas.css"
					),
					'js' => array(
						"js/scripts/ventas.js"
					),
				);
				layout("ventas/finalizaref", $data, $extras);
			} else {
				redirect('errorpage');
			}
		} else if ($this->input->method(TRUE) == "POST") {
			$referencia = $this->input->post("referencia");
			$xdatos["type"] = "success";
			$xdatos['title'] = 'Informaci??n';
			$xdatos["msg"] = "Venta guardada correctamente!";
			$xdatos["proceso"] = "finalizar";

			echo json_encode($xdatos);
		}
	}
	//impresion documentos
	function print_ticket($id_trabajo_taller, $id_sucursal, $correlativo, $total, $rowvta, $vendedor)
	{
		//echo $vendedor;
		//encabezado
		$id_usuario = $rowvta->id_usuario;
		$row_hf = $this->taller->get_one_row("config_pos", array('id_sucursal' => $id_sucursal, 'alias_tipodoc' => 'TIK',));
		$row_user = $this->taller->get_one_row("usuario", array('id_usuario' => $id_usuario,));

		//Procedemos a obtener los datos del vendedor
		$row_vendedor = $this->taller->get_one_row("usuario", array('id_usuario' => $vendedor,));

		$hstring = "";
		$line1 = str_repeat("_", 42) . "\n";

		$hstring .= chr(27) . chr(33) . chr(16); //FONT double size
		$hstring .= chr(27) . chr(97) . chr(1); //Center
		if ($row_hf->header1 != '')

			$hstring .= chr(13) . $row_hf->header1 . "\n";
		$hstring .= chr(27) . chr(33) . chr(0); //FONT A normal size
		if ($row_hf->header2 != '')
			$hstring .= chr(13) . $row_hf->header2 . "\n";
		if ($row_hf->header3 != '')
			$hstring .= chr(13) . $row_hf->header3 . "\n";
		if ($row_hf->header4 != '')
			$hstring .= chr(13) . $row_hf->header4 . "\n";
		if ($row_hf->header5 != '')
			$hstring .= chr(13) . $row_hf->header5 . "\n";
		if ($row_hf->header6 != '')
			$hstring .= chr(13) . $row_hf->header6 . "\n";
		if ($row_hf->header7 != '')
			$hstring .= chr(13) . $row_hf->header7 . "\n";
		if ($row_hf->header8 != '')
			$hstring .= chr(13) . $row_hf->header8 . "\n";
		if ($row_hf->header9 != '')
			$hstring .= chr(13) . $row_hf->header9 . "\n";
		if ($row_hf->header10 != '')
			$hstring .= chr(13) . $row_hf->header10 . "\n";

		//pie
		if ($row_hf->footer1 != '')
			$pstring = chr(13) . $row_hf->footer1 . "\n";
		if ($row_hf->footer2 != '')
			$pstring .= chr(13) . $row_hf->footer2 . "\n";
		if ($row_hf->footer3 != '')
			$pstring .= chr(13) . $row_hf->footer3 . "\n";
		if ($row_hf->footer4 != '')
			$pstring .= chr(13) . $row_hf->footer4 . "\n";
		if ($row_hf->footer5 != '')
			$pstring .= chr(13) . $row_hf->footer5 . "\n";
		if ($row_hf->footer6 != '')
			$pstring .= chr(13) . $row_hf->footer6 . "\n";
		if ($row_hf->footer7 != '')
			$pstring .= chr(13) . $row_hf->footer7 . "\n";
		if ($row_hf->footer8 != '')
			$pstring .= chr(13) . $row_hf->footer8 . "\n";
		if ($row_hf->footer9 != '')
			$pstring .= chr(13) . $row_hf->footer9 . "\n";
		if ($row_hf->footer10 != '')
			$pstring .= chr(13) . $row_hf->footer10 . "\n";
		//detalles productos
		$det_ticket = "";
		$hstring .= chr(13) . " FECHA: " .	d_m_Y($rowvta->fecha) . " HORA:" . $rowvta->hora . "\n";
		$hstring .= chr(13) . " CAJA #: " . $rowvta->caja . "\n";
		$hstring .= chr(13) . " CAJERO: " . $row_user->nombre . "\n";
		//Procedemos a insertar el vendedor
		$hstring .= chr(13) . "VENDEDOR: " . $row_user->nombre . "\n";
		$tiq = str_pad($correlativo, 10, '0', STR_PAD_LEFT);
		$hstring .= chr(13) . " TICKET #: " . $tiq . "\n";
		$det_ticket .= chr(27) . chr(97) . chr(0); //Left
		$det_ticket .= chr(13) . $line1 . "\n"; // Print text Lin
		//$det_ticket.=chr(13)."\n"; // Print text
		//$det_ticket.= chr(27).chr(97).chr(0); //Center
		$th = chr(13) . " DESCRIPCION    CANT.    P.U      SUBTOTAL" . "\n";
		$det_ticket .= chr(13) . $th;
		$det_ticket .= chr(13) . $line1;
		$detalleproductos = $this->taller->get_detail_ci($id_trabajo_taller);
		$espacio = " ";
		$margen_izq1 = AlignMarginText::leftmargin($espacio, 2);
		$margen_izq2 = AlignMarginText::leftmargin($espacio, 3);
		if ($detalleproductos != NULL) {
			foreach ($detalleproductos as $detalle) {
				$id_producto = $detalle->id_producto;
				$descripcion = $detalle->nombre . " " . $detalle->marca . " " . $detalle->modelo . " " . $detalle->color;
				$precio_fin = "$ " . $detalle->precio_fin;
				$cantidad = $detalle->cantidad;
				$subtotal = "$ " . $detalle->subtotal;
				$desc = AlignMarginText::onelineleft($descripcion, 32, 1, $espacio);
				$pre = AlignMarginText::rightaligner($precio_fin, $espacio, 12);
				$cant = AlignMarginText::rightaligner($cantidad, $espacio, 5);
				$subt = AlignMarginText::rightaligner($subtotal, $espacio, 12);
				$det_ticket .= $desc . "\n";
				$det_ticket .= $margen_izq2 . $cant . " X " . $margen_izq1 . $pre . $margen_izq1 . " = " . $subt . "\n";
			}
		}
		//detalles servicios
		$detalleservicios = $this->taller->get_detail_serv($id_trabajo_taller);
		if ($detalleservicios != NULL) {
			foreach ($detalleservicios as $detalle) {
				$id_producto = $detalle->id_producto;
				$descripcion = $detalle->nombre;
				$precio_fin = "$ " . $detalle->precio_fin;
				$cantidad = $detalle->cantidad;
				$subtotal = "$ " . $detalle->subtotal;
				$desc = AlignMarginText::onelineleft($descripcion, 32, 1, $espacio);
				//$espacio="#";
				$pre = AlignMarginText::rightaligner($precio_fin, $espacio, 12);
				$cant = AlignMarginText::rightaligner($cantidad, $espacio, 5);
				$subt = AlignMarginText::rightaligner($subtotal, $espacio, 12);
				$det_ticket .= $desc . "\n";
				$det_ticket .= $margen_izq2 . $cant . " X " . $margen_izq1 . $pre . $margen_izq1 . " = " . $subt . "\n";
			}
		}
		$det_ticket .= chr(13) . $line1;
		$det_ticket .= chr(27) . chr(33) . chr(0); //FONT A
		$det_ticket .= chr(27) . chr(97) . chr(1); //Center align
		$totales = chr(27) . chr(33) . chr(16); //FONT A
		$totales .= chr(27) . chr(97) . chr(2); //Right align
		$totals = "  TOTAL   $ " . $total . "   " . "\n";
		$lentot = strlen($totals);
		$totales .= $totals;
		$totales .= chr(27) . chr(33) . chr(0); //FONT A
		$l2 = str_repeat("_", $lentot) . "\n";
		$totales .= $l2;
		$xdatos["encabezado"] = $hstring;
		$xdatos["totales"] = $totales;
		$xdatos["cuerpo"] = $det_ticket;
		$xdatos["pie"] = $pstring;
		return $xdatos;
	}
	function print_cof($id_trabajo_taller, $id_sucursal, $rowvta)
	{

		//Cliente
		$row_cte = $this->taller->get_one_row("clientes", array('id_cliente' => $rowvta->id_cliente,));

		list($anio, $mes, $dia) = explode("-", $rowvta->fecha);
		//inicio header print_cof
		$det_factura = "";
		$hstring = "";
		$espacio = " ";
		for ($n = 0; $n < 8; $n++) {
			//$hstring.= chr(10); //Line Feed
			$hstring .= chr(13) . "\n"; // Print text
		}
		$nombre = wordwrap(strtoupper($row_cte->nombre), 60);
		$direccion = wordwrap(strtoupper($row_cte->direccion), 65);
		$sp = AlignMarginText::leftmargin($espacio, 51);
		$hstring .= $sp . $dia . "  -  " . $mes . "   -  " . $anio . "\n";
		$sp1 = AlignMarginText::leftmargin($espacio, 10);
		$hstring .= chr(13) . "\n\n"; // Print text
		$hstring .= chr(13) . $sp1 . $nombre . "\n";
		$hstring .= chr(13) . $sp1 . "  " . $direccion . "\n";
		for ($n = 0; $n < 3; $n++) {
			$hstring .= chr(13) . "\n"; // Print text
		}
		//fin header print_cof
		//$row_confpos=$this->taller->get_one_row("config_dir", array('id_sucursal' => $id_sucursal,));
		//detalle de la venta
		//$rowvta = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));
		//detalles productos
		$detalleproductos = $this->taller->get_detail_ci($id_trabajo_taller);
		$margen_izq0 = AlignMarginText::leftmargin($espacio, 1);
		$margen_izq = AlignMarginText::leftmargin($espacio, 3);
		$lineas = 0;
		if ($detalleproductos != NULL) {
			foreach ($detalleproductos as $detalle) {
				$id_producto = $detalle->id_producto;
				$descripcion = $detalle->nombre . " " . $detalle->marca . " " . $detalle->modelo . " " . $detalle->color;
				$precio_fin = $detalle->precio_fin;
				$cantidad = $detalle->cantidad;
				$subtotal = $detalle->subtotal;
				$cant = AlignMarginText::rightaligner($cantidad, $espacio, 6);
				$desc = AlignMarginText::onelineleft($descripcion, 42, 2, $espacio);
				$pre = "$ " . AlignMarginText::rightaligner($precio_fin, $espacio, 10);
				$subt = "$ " . AlignMarginText::rightaligner($subtotal, $espacio, 12);
				$det_factura .= $cant . $desc . $margen_izq0 . $pre . $margen_izq . $subt . " \n";
				$lineas++;
			}
		}
		//detalles servicios
		$detalleservicios = $this->taller->get_detail_serv($id_trabajo_taller);

		if ($detalleservicios != NULL) {
			foreach ($detalleservicios as $detalle) {
				$id_producto = $detalle->id_producto;
				$descripcion = $detalle->nombre;
				$precio_fin = $detalle->precio_fin;
				$cantidad = $detalle->cantidad;
				$subtotal = $detalle->subtotal;
				$cant = AlignMarginText::rightaligner($cantidad, $espacio, 6);
				$desc = AlignMarginText::onelineleft($descripcion, 42, 2, $espacio);
				$pre = "$ " . AlignMarginText::rightaligner($precio_fin, $espacio, 10);
				$subt = "$ " . AlignMarginText::rightaligner($subtotal, $espacio, 12);
				$det_factura .= $cant . $desc . $margen_izq0 . $pre . $margen_izq . $subt . " \n";
				$lineas++;
			}
		}
		$nlineas = 20; //numero de lineas maxima para el formato
		$lin_add = 0;
		if ($lineas <= $nlineas) {
			$lin_add = $nlineas - $lineas + 1;
		}
		for ($n = 0; $n < $lin_add; $n++) {
			//$hstring.= chr(10); //Line Feed
			$det_factura .= chr(13) . "\n"; // Print text
		}
		//totales
		//	$hstring.=chr(13).$row_cte->nombre."\n";
		$tot_letras = NumeroALetras::convertir($rowvta->total, 'Dolares', false, 'ctvs');
		//$total_letras = wordwrap(strtoupper($tot_letras),30) . "\n";
		$ln_txt = 34;
		$total_let = AlignMarginText::wordwrap1(strtoupper($tot_letras), $ln_txt);
		$tmplinea = array();
		$ln = 0;
		foreach ($total_let as $total_txt1) {
			$ln = $ln + 1;
			$tmplinea[] = trim($total_txt1);
		}
		$totales = "";
		$long_lin_tot = 62;
		$margen_txt_totals = AlignMarginText::leftmargin($espacio, 4);
		$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot - $ln_txt);
		$total_fin = "$ " . AlignMarginText::rightaligner($rowvta->total, $espacio, 13);
		if ($ln == 1) {
			$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot - strlen($tmplinea[0]));
			$totales .= $margen_txt_totals . $tmplinea[0];
			$totales .= $margen_totals . $total_fin . "\n";
			$totales .= chr(13) . "\n"; // Print text
			$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot + 4);
			$totales .= $margen_totals . " $ " . $rowvta->total . "\n";
			$totales .= chr(13) . "\n"; // Print text
		}
		if ($ln == 2) {
			$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot - strlen($tmplinea[0]));
			$totales .= $margen_txt_totals . $tmplinea[0];
			$totales .= $margen_totals . $total_fin . "\n";
			$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot - strlen($tmplinea[1]));
			$totales .= $margen_txt_totals . $tmplinea[1] . "\n";
			$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot + 4);
			$totales .= $margen_totals . $total_fin . "\n";
			$totales .= chr(13) . "\n"; // Print text
		}
		if ($ln == 3 || $ln == 4) {
			$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot - strlen($tmplinea[0]));
			$totales .= $margen_txt_totals . $tmplinea[0];
			$totales .= $margen_totals . $total_fin . "\n";
			$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot - strlen($tmplinea[1]));
			$totales .= $margen_txt_totals . $tmplinea[1] . "\n";
			$totales .= $margen_txt_totals . $tmplinea[2];
			$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot - strlen($tmplinea[2]));
			$totales .= $margen_totals . $total_fin . "\n";
			$totales .= chr(13) . "\n"; // Print text
		}
		$margen_totals = AlignMarginText::leftmargin($espacio, $long_lin_tot + 4);
		$totales .= chr(13) . "\n"; // Print text
		$totales .= $margen_totals . $total_fin . "\n";
		$xdatos["encabezado"] = $hstring;
		$xdatos["cuerpo"] = $det_factura;
		$xdatos["totales"] = $totales;
		$xdatos["pie"] = ".";
		return $xdatos;
	}
	function print_ccf($id_trabajo_taller, $id_sucursal)
	{

		$info_factura = "";
		//header print_cof
		$row_confpos = $this->taller->get_one_row("config_dir", array('id_sucursal' => $id_sucursal,));
		$info_factura .= "DESCRIPCION  CANT.  P. UNIT    SUBTOT.\n|";
		//encabezado de la venta
		$rowvta = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));
		//detalles productos
		$detalleproductos = $this->taller->get_detail_ci($id_trabajo_taller);
		$espacio = "&nbsp;";
		$espacio = " ";
		$margen_izq = AlignMarginText::leftmargin($espacio, 2);
		if ($detalleproductos != NULL) {

			foreach ($detalleproductos as $detalle) {
				$id_producto = $detalle->id_producto;
				$descripcion = $detalle->marca . " " . $detalle->modelo . " " . $detalle->color;
				$precio_fin = $detalle->precio_fin;
				$cantidad = $detalle->cantidad;
				$subtotal = $detalle->subtotal;
				//AlignMarginText::onelineleft(texto,longitud,margin_izq,caracter_espacios);
				//AlignMarginText::rightaligner($input,$caracter = " ",$width)
				$desc = AlignMarginText::onelineleft($descripcion, 20, 1, $espacio);
				$pre = AlignMarginText::rightaligner($precio_fin, $espacio, 12);
				$cant = AlignMarginText::rightaligner($cantidad, $espacio, 12);
				$subt = AlignMarginText::rightaligner($subtotal, $espacio, 12);
				$info_factura .= $desc . $margen_izq . $cantidad . $margen_izq . $pre . $margen_izq . $subt . " \n";
			}
		}
		//detalles servicios
		$detalleservicios = $this->taller->get_detail_serv($id_trabajo_taller);
		if ($detalleservicios != NULL) {
			foreach ($detalleservicios as $detalle) {
				$id_producto = $detalle->id_producto;
				$descripcion = $detalle->nombre;
				$precio_fin = $detalle->precio_fin;
				$cantidad = $detalle->cantidad;
				$subtotal = $detalle->subtotal;
				$desc = AlignMarginText::onelineleft($descripcion, 20, 1, $espacio);
				$pre = AlignMarginText::rightaligner($precio_fin, $espacio, 12);
				$cant = AlignMarginText::rightaligner($cantidad, $espacio, 12);
				$subt = AlignMarginText::rightaligner($subtotal, $espacio, 12);
				$info_factura .= $desc . $margen_izq . $cantidad . $margen_izq . $pre . $margen_izq . $subt . " \n";
			}
		}
		return $info_factura;
	}

	function print_voucher($id_trabajo_taller, $id_sucursal, $correlativo, $rowvta, $vendedor)
	{
		//echo $vendedor;
		//encabezado
		$id_usuario = $rowvta->id_usuario;
		$row_hf = $this->taller->get_one_row("config_pos", array('id_sucursal' => $id_sucursal, 'alias_tipodoc' => 'TIK',));
		$row_user = $this->taller->get_one_row("usuario", array('id_usuario' => $id_usuario,));

		//Procedemos a obtener los datos del vendedor
		$row_vendedor = $this->taller->get_one_row("usuario", array('id_usuario' => $vendedor,));

		$hstring = "";
		$line1 = str_repeat("_", 42) . "\n";

		$hstring .= chr(27) . chr(33) . chr(16); //FONT double size
		$hstring .= chr(27) . chr(97) . chr(1); //Center
		if ($row_hf->header1 != '')

			$hstring .= chr(13) . $row_hf->header1 . "\n";
		$hstring .= chr(27) . chr(33) . chr(0); //FONT A normal size
		if ($row_hf->header2 != '')
			$hstring .= chr(13) . $row_hf->header2 . "\n";
		if ($row_hf->header3 != '')
			$hstring .= chr(13) . $row_hf->header3 . "\n";
		if ($row_hf->header4 != '')
			$hstring .= chr(13) . $row_hf->header4 . "\n";
		if ($row_hf->header5 != '')
			$hstring .= chr(13) . $row_hf->header5 . "\n";
		if ($row_hf->header6 != '')
			$hstring .= chr(13) . $row_hf->header6 . "\n";
		if ($row_hf->header7 != '')
			$hstring .= chr(13) . $row_hf->header7 . "\n";
		if ($row_hf->header8 != '')
			$hstring .= chr(13) . $row_hf->header8 . "\n";
		if ($row_hf->header9 != '')
			$hstring .= chr(13) . $row_hf->header9 . "\n";
		if ($row_hf->header10 != '')
			$hstring .= chr(13) . $row_hf->header10 . "\n";

		//pie
		if ($row_hf->footer1 != '')
			$pstring = chr(13) . $row_hf->footer1 . "\n";
		if ($row_hf->footer2 != '')
			$pstring .= chr(13) . $row_hf->footer2 . "\n";
		if ($row_hf->footer3 != '')
			$pstring .= chr(13) . $row_hf->footer3 . "\n";
		if ($row_hf->footer4 != '')
			$pstring .= chr(13) . $row_hf->footer4 . "\n";
		if ($row_hf->footer5 != '')
			$pstring .= chr(13) . $row_hf->footer5 . "\n";
		if ($row_hf->footer6 != '')
			$pstring .= chr(13) . $row_hf->footer6 . "\n";
		if ($row_hf->footer7 != '')
			$pstring .= chr(13) . $row_hf->footer7 . "\n";
		if ($row_hf->footer8 != '')
			$pstring .= chr(13) . $row_hf->footer8 . "\n";
		if ($row_hf->footer9 != '')
			$pstring .= chr(13) . $row_hf->footer9 . "\n";
		if ($row_hf->footer10 != '')
			$pstring .= chr(13) . $row_hf->footer10 . "\n";
		//detalles productos
		$det_ticket = "";
		$hstring .= chr(13) . " FECHA: " .	d_m_Y($rowvta->fecha) . " HORA:" . $rowvta->hora . "\n";
		$hstring .= chr(13) . " CAJA #: " . $rowvta->caja . "\n";
		$hstring .= chr(13) . " CAJERO: " . $row_user->nombre . "\n";
		//Procedemos a insertar el vendedor
		$hstring .= chr(13) . "VENDEDOR: " . $row_user->nombre . "\n";
		$tiq = str_pad($correlativo, 10, '0', STR_PAD_LEFT);
		$hstring .= chr(13) . " TICKET #: " . $tiq . "\n";
		$det_ticket .= chr(27) . chr(97) . chr(0); //Left
		$det_ticket .= chr(13) . $line1 . "\n"; // Print text Lin
		//$det_ticket.=chr(13)."\n"; // Print text
		//$det_ticket.= chr(27).chr(97).chr(0); //Center
		$th = chr(13) . " DESCRIPCION    CANT.    P.U      SUBTOTAL" . "\n";
		$det_ticket .= chr(13) . $th;
		$det_ticket .= chr(13) . $line1;
		$detalleproductos = $this->taller->get_detail_ci($id_trabajo_taller);
		$espacio = " ";
		$margen_izq1 = AlignMarginText::leftmargin($espacio, 2);
		$margen_izq2 = AlignMarginText::leftmargin($espacio, 3);

		$det_ticket .= chr(13) . $line1;
		$det_ticket .= chr(27) . chr(33) . chr(0); //FONT A
		$det_ticket .= chr(27) . chr(97) . chr(1); //Center align
		$totales = chr(27) . chr(33) . chr(16); //FONT A
		$totales .= chr(27) . chr(97) . chr(2); //Right align
		$totals = "  TOTAL   $ " . "   " . "\n";
		$lentot = strlen($totals);
		$totales .= $totals;
		$totales .= chr(27) . chr(33) . chr(0); //FONT A
		$l2 = str_repeat("_", $lentot) . "\n";
		$totales .= $l2;
		$xdatos["encabezado"] = $hstring;
		$xdatos["totales"] = $totales;
		$xdatos["cuerpo"] = $det_ticket;
		$xdatos["pie"] = $pstring;
		return $xdatos;
	}

	function printdoc($id = -1)
	{
		if ($this->input->method(TRUE) == "POST") {
			if ($this->agent->is_browser()) {
				$agent = $this->agent->browser() . ' ' . $this->agent->version();
				$opsys = $this->agent->platform();
			}
			$id_trabajo_taller = $this->input->post("id_trabajo_taller");
			$rowvta = $this->taller->get_one_row("trabajos_taller", array('id_trabajo_taller' => $id_trabajo_taller,));
			if ($rowvta != NULL) {
				$id_sucursal = $rowvta->id_sucursal;
				$row_confdir = $this->taller->get_one_row("config_dir", array('id_sucursal' => $id_sucursal,));
				$tot_letras = NumeroALetras::convertir($rowvta->total, 'Dolares', false, 'centavos');
				$total_letras = wordwrap(strtoupper($tot_letras), 40) . "\n";
				$tipodoc = $rowvta->tipo_doc;
				switch ($tipodoc) {
					case 1:
						$xdatos = $this->print_ticket($id_trabajo_taller, $id_sucursal, $rowvta->correlativo, $rowvta->total, $rowvta, 0);
						break;
					case 2:
						$xdatos = $this->print_cof($id_trabajo_taller, $id_sucursal, $rowvta);
						break;
					case 3:
						$xdatos = $this->print_ccf($id_trabajo_taller, $id_sucursal, $rowvta);
						break;
				}
				$xdatos["type"] = "success";
				$xdatos['title'] = 'Informaci??n';
				$xdatos["msg"] = "Documento impreso correctamente!";

				$xdatos["tipodoc"] = $tipodoc;
				$xdatos["id_client"] = $rowvta->id_cliente;
				$xdatos["total_letras"] = $total_letras;
				$xdatos["opsys"] = $opsys;
				$xdatos["dir_print"] = $row_confdir->dir_print_script; //for Linux
				$xdatos["dir_print_pos"] = $row_confdir->shared_printer_pos; //for win


				echo json_encode($xdatos);
			}
		}
	}

	/* Mostrar modal de Creaci??n de clientes */
	function new_data_client($id = -1)
	{

		if ($this->input->method(TRUE) == "GET") {
			$clasifica_cliente = $this->clientes->get_clasifica_cliente();
			$data = array(
				"clasifica_cliente" => $clasifica_cliente,
			);
			$this->load->view("taller/new_client_modal.php", $data);
		} else {
			redirect('errorpage');
		}
	}

	function save_data_client()
	{
		if ($this->input->method(TRUE) == "POST") {
			$errors = false;
			$this->utils->begin();
			$nomcte = strtoupper($this->input->post("nombre"));
			$nit = $this->input->post("nit");
			$dui = $this->input->post("dui");
			$nrc = $this->input->post("nrc");
			$clasifica = $this->input->post("clasifica");
			$form_data = array(
				'nombre' => $nomcte,
				'nombre_comercial' => $nomcte,
				'direccion' => "SAN MIGUEL, EL SALVADOR",
				'clasifica' => $clasifica,
				'dui' => $dui,
				'nit' => $nit,
				'nrc' => $nrc,
				'departamento' => 13,
				'municipio' => 81,
				'activo' => 1,
			);
			$id_cliente = $this->taller->inAndCon("clientes", $form_data);
			if ($id_cliente == NULL) {
				$errors = true;
			}
			if ($errors == true) {

				$this->utils->rollback();
				$xdatos["type"] = "error";
				$xdatos['title'] = 'Alerta';
				$xdatos["msg"] = "Error al ingresar el registro";
				$xdatos["id_cliente"] = -1;
				$xdatos["nomcte"] = " ";
			} else {
				$this->utils->commit();

				$xdatos["type"] = "success";
				$xdatos['title'] = 'Alerta';
				$xdatos["msg"] = "Exito al ingresar el registro";
				$xdatos["id_cliente"] = $id_cliente;
				$xdatos["nomcte"] = $nomcte;
			}
			echo json_encode($xdatos);
		}
	}

	
}

/* End of file Taller.php */
