<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traslado extends CI_Controller {
	/*
	Global table name
	*/
	private $table = "stock";
	private $pk = "id_producto";

	function __construct()
	{
		parent::__construct();
		$this->load->model('UtilsModel',"utils");
		$this->load->model("TrasladoModel","ventas");
		$this->load->model("ReportesModel","reportes");
	}

	public function index()
	{
		$id_usuario=$this->session->id_usuario;
		$id_sucursal=$this->session->id_sucursal;
		$usuario_tipo =	$this->ventas->get_one_row("usuario", array('id_usuario' => $id_usuario,));
		if($usuario_tipo!=NULL){
			if($usuario_tipo->admin==1 || $usuario_tipo->super_admin==1){
					$sucursales=$this->ventas->get_detail_rows("sucursales",array('1' => 1, ));
			}else {
					$sucursales=$this->ventas->get_detail_rows("sucursales", array('id_sucursal' => $id_sucursal,));
			}
	 }else {
			$sucursales=$this->ventas->get_detail_rows("sucursales",array('1' => 1, ));
	 }
		$data = array(
			"titulo"=> "Traslados",
			"icono"=> "mdi mdi-cart",
			"buttons" => array(
				0 => array(
					"icon"=> "mdi mdi-plus",
					'url' => 'Traslado/agregar',
					'txt' => ' Nuevo Traslado',
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
					"selected" => $id_sucursal,
				),
			),
			"table"=>array(
				"ID"=>5,
				"Fecha"=>10,
				"Origen"=>10,
				"Destino"=>10,
				"Total"=>10,
				"Detalle"=>30,
				"Estado"=>10,
				"Acciones"=>10,
			),
		);
		$extras = array(
			'css' => array(
			),
			'js' => array(
				"js/scripts/traslados.js"
			),
		);
		layout("template/admin",$data,$extras);
	}

	function get_data(){
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
			0 => 'v.id_traslado',
			1 => 'v.fecha',
			2 => 'CONCAT(s1.nombre," ",s1.direccion)',
			3 => 'CONCAT(s2.nombre," ",s2.direccion)',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}

		$row = $this->ventas->get_collection($order, $search, $valid_columns, $length, $start, $dir, $id_sucursal);
		//print_r($row);
		if ($row != 0) {
			$data = array();
			foreach ($row as $rows) {
				//procedemos a obtener el detalle del traslado
				$detalleT = $this->ventas->get_detalle_traslado($rows->id_traslado);
				$filename = base_url("Traslado/reporte_traslado/".$rows->id_sucursal_despacho."/".$rows->id_sucursal_destino."/".$rows->id_traslado);
				$filenameTicket = base_url("Traslado/imprimir_ticket/".$rows->id_traslado."/".$rows->id_sucursal_despacho);

				$menudrop = "<div class='btn-group'>
				<button data-toggle='dropdown' class='btn btn-success dropdown-toggle' aria-expanded='false'><i class='mdi mdi-menu' aria-haspopup='false'></i> Menu</button>
				<ul class='dropdown-menu dropdown-menu-right' x-placement='bottom-start'>";

				$menudrop .= "<li><a  data-toggle='modal' data-target='#viewModal' data-refresh='true'  role='button' class='detail' data-id=".$rows->id_traslado."><i class='mdi mdi-eye-check' ></i> Detalles</a></li>";
				$menudrop .= "<li><a  class='detail' href='".$filename."'><i class='mdi mdi-file-pdf' ></i> Generar Reporte</a></li>";
				$menudrop .= "<li><a  class='imprimir_ticket' traslado='".$rows->id_traslado."' sucursal='".$rows->id_sucursal_despacho."' sucursal_destino='".$rows->id_sucursal_destino."'><i class='mdi mdi-file-pdf' ></i> Imprimir Ticket</a></li>";
				$menudrop .= "</ul></div>";

				//procedemos a validar si se mostraran los precios
				if($this->session->admin==1 || $this->session->super_admin==1){
					$validarCostos = "";
				}
				else {
					// code...
					$validarCostos = "hidden";
				}
				//procedemos a validar el estado del traslado
				if($rows->estado==0){
					$estado = "PENDIENTE";
				}
				else if($rows->estado==1){
					$estado = "FINALIZADO";
				}
				else{
					$estado = "ANULADO";
				}
				$data[] = array(
					$rows->id_traslado,
					$rows->fecha,
					$rows->suc1,
					$rows->suc2,
					"<div $validarCostos>".$rows->total."</div>",
					$detalleT->detalle_t,
					$estado,
					$menudrop,
				);
			}
			$total = $this->ventas->total_rows();
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

	function detalle($id=-1){
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$rows = $this->ventas->get_detail_ci($id);
			//procedemos a validar si se mostraran los precios
			if($this->session->admin==1 || $this->session->super_admin==1){
				$validarCostos = "";
			}
			else {
				// code...
				$validarCostos = "hidden";
			}
			if($rows && $id!=""){
				$data = array(
					"rows"=>$rows,
					"process" => "traslado",
					"ocultar" => $validarCostos
				);
				$this->load->view("inventario/ver_detalle.php",$data);
			}else{
				redirect('errorpage');
			}
		}
	}

	function change_state($id=-1){
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->ventas->get_one_row("ventas",array('id_venta' => $id, ));
			$rows = $this->ventas->get_detail_rows("estado",array('1' => 1, ));
			if($rows && $id!=""){
				$data = array(
					"row"=>$row,
					"rows"=>$rows,
				);
				$this->load->view("ventas/change_state.php",$data);
			}else{
				redirect('errorpage');
			}
		}
	}

	function agregar(){
		if($this->input->method(TRUE) == "GET"){
			$id_usuario=$this->session->id_usuario;
			$id_sucursal=$this->session->id_sucursal;
			$usuario_tipo =	$this->ventas->get_one_row("usuario", array('id_usuario' => $id_usuario,));
			if($usuario_tipo!=NULL){
	 		  if($usuario_tipo->admin==1 || $usuario_tipo->super_admin==1){
	 					$sucursales=$this->ventas->get_detail_rows("sucursales",array('1' => 1, ));
	 			}else {
	 					$sucursales=$this->ventas->get_detail_rows("sucursales", array('id_sucursal' => $id_sucursal,));
	 			}
	 	 }else {
	 	 	 	$sucursales=$this->ventas->get_detail_rows("sucursales",array('1' => 1, ));
	 	 }

			$data = array(
				"sucursal_envio"=>$sucursales,
				"sucursal"=>$this->ventas->get_detail_rows("sucursales",array('1' => 1, )),
				"id_sucursal" => $this->session->id_sucursal,
			);

			$extras = array(
				'css' => array(
				),
				'js' => array(
					"js/scripts/traslados.js"
				),
			);

			layout("traslado/guardar",$data,$extras);
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->load->model("ProductosModel","productos");
			$this->utils->begin();
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$instrucciones = $this->input->post("instrucciones");
			$total = $this->input->post("total");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_sucursal = $this->input->post("sucursal");
			$id_sucursal_destino = $this->input->post("sucursal_destino");
			$id_usuario = $this->session->id_usuario;
			$hora = date("H:i:s");

			$correlativo = $this->ventas->get_max_correlative('tr',$id_sucursal);

			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'indicaciones ' => $instrucciones,
				'id_sucursal_despacho' => $id_sucursal,
				'id_sucursal_destino' => $id_sucursal_destino,
				'correlativo' => $correlativo,
				'total' => $total,
				'id_sucursal' => $id_sucursal,
				'id_usuario' => $id_usuario,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
				'guia' => "",
			);

			$imei_required = false;

			$id_venta = $this->ventas->inAndCon('traslado',$data);
			if($id_venta!=NULL){

				foreach ($data_ingreso as $fila)
				{
					$id_producto = $fila['id_producto'];
					$costo = $fila['costo'];
					$cantidad = $fila['cantidad'];
					$precio_sugerido = $fila['precio_sugerido'];
					$subtotal = $fila['subtotal'];
					$color = $fila['color'];
					$estado = $fila['est'];

					//Descarga Del origen
					$form_data = array(
						'id_traslado' => $id_venta,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'costo' => $costo,
						'precio' => $precio_sugerido,
						'cantidad' => $cantidad,
						'subtotal' => $subtotal,
						'condicion' => $estado,
						'garantia' =>  $this->ventas->getGarantia($id_producto,$estado),
						'carga' => 0,
					);
					$id_detalle = $this->ventas->inAndCon('traslado_detalle',$form_data);
					/*
					$stock_data = $this->ventas->get_stock($id_producto,$color,$id_sucursal);
					$newstock = ($stock_data->cantidad)-$cantidad;
					$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);

					//Carga en el destino
					$stock_data = $this->ventas->get_stock($id_producto,$color,$id_sucursal_destino);
					$newstock = ($stock_data->cantidad)+$cantidad;
					$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);

					*/
					if ($this->ventas->has_imei_required($id_producto)) {
						// code...
						$imei_required=true;
					}
				}

				if ($imei_required) {
					// code...
					$this->utils->update("ventas",array('requiere_imei' => 1, ),"id_venta=$id_venta");
				}
				$this->utils->commit();
				$xdatos["type"]="success";
				$xdatos['title']='Información';
				$xdatos["msg"]="Registo ingresado correctamente!";
			}
			else {
				$this->utils->rollback();
				$xdatos["type"]="error";
				$xdatos['title']='Alerta';
				$xdatos["msg"]="Error al ingresar el registro";
			}


			echo json_encode($xdatos);
		}
	}

	function imei($id=-1){
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->ventas->get_one_row("ventas", array('id_venta' => $id,));
			if($row && $id!=""){
				$data = array(
					"row"=>$row,
					"detalles"=>$this->ventas->get_detail_ci($id),
				);
				$extras = array(
					'css' => array(
					),
					'js' => array(
						"js/scripts/ventas_imei.js"
					),
				);
				layout("ventas/cargaimei",$data,$extras);
			}else{
				redirect('errorpage');
			}
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->utils->begin();

			$errors = false;
			$array_error= array("Log");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_venta = $this->input->post("id_venta");
			foreach ($data_ingreso as $fila) {
				// code...
				$form_data = array(
					'id_producto' => $fila['id_producto'],
					'imei' => $fila['imei'],
					'id_detalle' => $fila['id_detalle'],
					'chain' => $fila['chain'],
					'id_venta' => $id_venta,
					'vendido' => 1,
				);

				$id_detalle = $this->ventas->inAndCon('ventas_imei',$form_data);

				if ($id_detalle==NULL) {
					// code...
					$errors = true;
				}
			}

			if ($errors==true) {
				// code...
				$this->utils->rollback();
				$xdatos["type"]="error";
				$xdatos['title']='Alerta';
				$xdatos["msg"]="Error al ingresar el registro";
			}
			else {
				// code...
				$this->utils->update("ventas",array('imei_ingresado' => 1, ),"id_venta=$id_venta");
				$this->utils->commit();
				$xdatos["type"]="success";
				$xdatos['title']='Información';
				$xdatos["msg"]="Registo ingresado correctamente!";
			}

			echo json_encode($xdatos);

		}
	}

	function editarimei($id=-1){
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->ventas->get_one_row("ventas", array('id_venta' => $id,));

			$info = $this->ventas->get_imei_ci($id);
			$detalles = array();
			$c=0;
			foreach ($info as $key) {
				// code...
				$detalles[$c]=array(
					'id_venta' => $key->id_venta,
					'id_producto' => $key->id_producto,
					'id_detalle' => $key->id_detalle,
					'nombre' => $key->nombre,
					'chain' => $key->chain,
					'data' => $this->ventas->get_imei_ci_det($key->chain),
				);
				$c++;
			}

			if($row && $id!=""){
				$data = array(
					"row"=>$row,
					"detalles"=>$detalles,
				);
				$extras = array(
					'css' => array(
					),
					'js' => array(
						"js/scripts/ventas_imei.js"
					),
				);
				layout("ventas/editarimei",$data,$extras);
			}else{
				redirect('errorpage');
			}
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->utils->begin();

			$errors = false;
			$array_error= array("Log");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_venta = $this->input->post("id_venta");
			foreach ($data_ingreso as $fila) {
				// code...
				$form_data = array(
					'imei' => $fila['imei'],
				);

				$this->utils->update("ventas_imei",$form_data,"id_imei=$fila[id_imei]");
			}

			$this->utils->commit();
			$xdatos["type"]="success";
			$xdatos['title']='Información';
			$xdatos["msg"]="Registo ingresado correctamente!";


			echo json_encode($xdatos);

		}
	}
	function delete(){
		if($this->input->method(TRUE) == "POST"){
			$id_venta = $this->input->post("id");
			$this->utils->begin();
			$row = $this->ventas->get_one_row("ventas", array('id_venta' => $id_venta,));
			$id_sucursal = $row->id_sucursal;
			/*descargar los detalles previos*/
			$detalles_previos = $this->ventas->get_detail_rows("ventas_detalle", array('id_venta' => $id_venta, ));
			foreach ($detalles_previos as $key) {
				// code...
				$stock_data = $this->ventas->get_stock($key->id_producto,$key->id_color,$id_sucursal);
				$newstock = ($stock_data->cantidad)+($key->cantidad);
				$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
			}
			/*eliminar detalles previos*/
			$this->utils->delete("ventas_detalle","id_venta=$id_venta");
			$this->utils->delete("ventas","id_venta=$id_venta");


			$this->utils->commit();
			$response["type"] = "success";
			$response["title"] = "Información";
			$response["msg"] = "Registro eliminado con éxito!";

			echo json_encode($response);
		}
	}
	function change(){
		if($this->input->method(TRUE) == "POST"){
			$id_venta = $this->input->post("id");
			$id_estado = $this->input->post("id_estado");
			$this->utils->begin();
			$this->utils->update("ventas",array('id_estado' => $id_estado, ),"id_venta=$id_venta");
			$this->utils->commit();
			$response["type"] = "success";
			$response["title"] = "Información";
			$response["msg"] = "Registro editado con éxito!";
			echo json_encode($response);
		}
	}

	public function detalle_producto($id=0)
	{
		//$id_sucursal = $this->session->id_sucursal;
		if($id == 0)
		{
			$id_producto = $this->input->post("id");
			$id_color = $this->input->post("id_s");
			$id_venta = $this->input->post("id_venta");
			$id_sucursal = $this->input->post("id_sucursal");
		}
		$lista = "";
		$stock_data = $this->ventas->get_stock($id_producto,$id_color,$id_sucursal);
		$prods = $this->ventas->get_producto($id_producto);
		$precios = $this->ventas->get_precios_exis($id_producto);
		$colores = $this->ventas->get_detail_rows("producto_color", array('id_producto' => $id_producto,));

		$d = $this->ventas->get_reservado($id_producto,$id_venta,$id_color);

		$color_select="";
		if ($colores) {
			$color_select.="<select class='form-control color' style='width:100%;'>";
			foreach ($colores as $key) {
				$color_select.="<option value='".$key->id_color."'>".$key->color."</option>";
			}
			$color_select.="/<select>";
		}
		else {
			$color_select.="<select class='form-control color sel' style='width:100%;'>";
			$color_select.="<option value='0'>SIN COLOR</option>";
			$color_select.="/<select>";
		}
		//procedemos a validar si se mostraran los precios
		if($this->session->admin==1 || $this->session->super_admin==1){
			$validarCostos = "";
		}
		else {
			// code...
			$validarCostos = "hidden";
		}
		$lista .= "<div $validarCostos><select class='form-control precios sel' style='width:100%;'>";
		$costo = 0;
		$costo_iva = 0;
		foreach ($precios as $row_por)
		{
			$id_porcentaje = $row_por->id_precio;
			$costo = $row_por->costo;
			$costo_iva = $row_por->costo_iva;
			$precio = $row_por->total_iva;

			$lista .= "<option value='".$precio."' precio='".$precio."'>$".number_format($precio,2,".",",")."</option>";
		}
		$lista .= "</select></div>";
		$xdatos["precio_sugerido"]=$prods->precio_sugerido;
		$xdatos["precios"] = $lista;
		$xdatos["stock"] = $stock_data->cantidad+$d->reservado;
		$xdatos["id_s"] = $stock_data->id_stock;
		$xdatos["id_sucursal"] = $id_sucursal;
		$xdatos["marca"] = $prods->marca;
		$xdatos["modelo"] = $prods->modelo;
		$xdatos["costo"] = number_format($costo,2,".","");
		$xdatos["costo_iva"] = number_format($costo_iva,2,".","");
		$xdatos["ocultar"] = $validarCostos;
		echo json_encode($xdatos);
	}
	public function precios_producto($id=0,$precioe=0)
	{
		$id_sucursal = $this->session->id_sucursal;

		$precios = $this->ventas->get_precios_exis($id);
		$lista= "";
		$lista .= "<select class='form-control precios sel' style='width:100%;'>";
		$costo = 0;
		$costo_iva = 0;
		foreach ($precios as $row_por)
		{
			$id_porcentaje = $row_por->id_precio;
			$costo = $row_por->costo;
			$costo_iva = $row_por->costo_iva;
			$precio = $row_por->total_iva;

			$lista .= "<option value='".$precio."' precio='".$precio."'";
			if($precio == $precioe)
			{
				$lista.= " selected ";
			}
			$lista.= ">$".number_format($precio,2,".",",")."</option>";
		}
		$lista .= "</select>";
		$xdatos["precios"] = $lista;
		return $xdatos;
	}


	function get_productos(){
		$query = $this->input->post("query");
		$id_sucursal = $this->input->post("id_sucursal");
		$rows = $this->ventas->get_productos($query,$id_sucursal);
		$output = array();
		if($rows!=NULL) {
			foreach ($rows as $row) {
				$output[] = array(
					//'producto' => $row->id_producto."|".$row->nombre." ".$row->marca." ".$row->modelo,
					'producto' => $row->id_producto."|".$row->modelo." ".$row->color."|".$row->id_color."|".$row->nombre."|".$row->modelo."|".$row->marca."|".$row->codigo_barra."|".$row->color,
				);
			}
		}
		echo json_encode($output);
	}
	function get_clientes(){
		$query = $this->input->post("query");
		$rows = $this->ventas->get_clientes($query);
		$output = array();
		if($rows!=NULL) {
			foreach ($rows as $row) {
				$output[] = array(
					//'producto' => $row->id_producto."|".$row->nombre." ".$row->marca." ".$row->modelo,
					'cliente' => $row->id_cliente."|".$row->nombre,
				);
			}
		}
		echo json_encode($output);
	}
	function reporte_traslado(){
		$sucursalDespacho = $this->uri->segment(3);
		$sucursalDestino = $this->uri->segment(4);//sucursal
		$idTraslado = $this->uri->segment(5);//id traslado
		//procedemos a obtener los datos de la sucursal
		$arrSucursalOrigen = $this->reportes->get_row_sucursal($sucursalDespacho);
		$arrSucursalDestino = $this->reportes->get_row_sucursal($sucursalDestino);

		$this->load->library('Report');
		$pdf = $this->report->getInstance('P','mm', 'Letter');
		$logo = "assets/img/logo.png";
		$pdf->SetMargins(6, 10);
		$pdf->SetLeftMargin(5);
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(true, 15);
		$pdf->AliasNbPages();
		$data = array("empresa" => "Jah","imagen" => $logo, 'fecha' =>"14-10-1998", 'titulo' => "");
		$pdf->setear($data);
		$pdf->addPage();
		$pdf->SetFont('Arial','B',11);

		$l = array(
			's' => 10,
			'tit' =>185,
		);
		$array_data = array(
			array('',$l['s'],"C"),
			array("REPORTE DE EXISTENCIAS",$l['tit'],"C"),
		);
		$pdf->LineWrite($array_data);
		$pdf->LN(5);

		$pdf->SetFont('Arial','B',10);
		$l = array(
			's' => 10,
			'izq' =>90,
			'der' =>95,
		);

		$array_data = array(
			array('',$l['s'],"C"),
			array("ORIGEN: ".$arrSucursalOrigen->nombre,$l['izq'],"C"),
			array("DESTINO: ".$arrSucursalDestino->nombre,$l['der'],"C"),
		);
		$pdf->LineWrite($array_data);
		$pdf->LN(5);

		$l = array(
			's' => 10,
			'det' =>150,
			'can' =>35,
		);
		$array_data = array(
			array('',$l['s'],"C"),
			array("Producto",$l['det'],"L"),
			array("Cantidad",$l['can'],"L"),
		);
		$pdf->LineWriteB($array_data);
		$data = $this->ventas->get_detail_ci($idTraslado);
		$pdf->SetFont('Arial','',10);
		foreach ($data as $arrData) {
			# code...
			$array_data = array(
				array('',$l['s'],"C"),
				array($arrData->nombre." ".$arrData->marca." ".$arrData->modelo." ".$arrData->color,$l['det'],"L"),
				array($arrData->cantidad,$l['can'],"R"),
			);
			$pdf->LineWriteB($array_data);
		}
		$pdf->Output();
	}

	function imprimir_ticket(){
		$this->load->helper('print_helper');
		$this->load->library('user_agent');
		$sucursal = $this->input->post("sucursal");
		$sucursalDestino = $this->input->post("sucursal_destino");

		$traslado = $this->input->post("traslado");
		if ($this->agent->is_browser())
		{
			$agent = $this->agent->browser().' '.$this->agent->version();
			$opsys = $this->agent->platform();
		}

		//echo $idTraslado;
		$row_confdir=$this->ventas->get_one_row("config_dir", array('id_sucursal' => $sucursal,));

		$xdatos["ticket"]=print_traslado($traslado, $sucursal, $sucursalDestino);
		$xdatos["type"]="success";
		$xdatos['title']='Información';
		$xdatos["msg"]="Ticket impreso correctamente!";
		$xdatos["opsys"]=$opsys;
		$xdatos["dir_print"] =$row_confdir->dir_print_script; //for Linux
		$xdatos["dir_print_pos"] =$row_confdir->shared_printer_pos; //for win

		$data = $this->ventas->get_detail_ci($traslado);
		//var_dump($data);
		echo json_encode($xdatos);
	}
}

/* End of file Ventas.php */
