<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends CI_Controller {
	/*
	Global table name
	*/
	private $table = "stock";
	private $pk = "id_producto";

	function __construct()
	{
		parent::__construct();
		$this->load->model('UtilsModel',"utils");
		$this->load->model("ComprasModel","compras");
	}
	/*********************************************************/
	/*********************************************************/
	/************************CARGAS***************************/
	/*********************************************************/
	/*********************************************************/
	public function index()
	{
		$data = array(
			"titulo"=> "Compras",
			"icono"=> "mdi mdi-archive",
			"buttons" => array(
				0 => array(
					"icon"=> "mdi mdi-plus",
					'url' => 'compras/cargar',
					'txt' => 'Compras',
					'modal' => false,
				),
			),
			"selects" => array(
				0 => array(
					"name" => "sucursales",
					"data" => $this->compras->get_detail_rows("sucursales",array('1' => 1, )),
					"id" => "id_sucursal",
					"text" => array(
						"nombre",
						"direccion",
					),
					"separator" => " ",
					"selected" => $this->session->id_sucursal,
				),
			),
			"table"=>array(
				"ID"=>4,
				"Fecha"=>10,
				"Hora"=>10,
				"Concepto"=>30,
				"Correlativo"=>10,
				"Total"=>10,
				"Responsable"=>15,
				"Acciones"=>10,
			),
			"proceso" => 'carga',
		);
		$extras = array(
			'css' => array(
			),
			'js' => array(
				"js/scripts/compras.js"
			),
		);
		layout("template/admin",$data,$extras);
	}

	function get_data_carga(){
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
			0 => 'c.fecha',
			1 => 'c.concepto',
			2 => 'c.correlativo',
			3 => 'c.total',
			4 => 'u.nombre',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}

		$row = $this->compras->get_collection_compra($order, $search, $valid_columns, $length, $start, $dir, $id_sucursal);

		if ($row != 0) {
			$data = array();
			foreach ($row as $rows) {

				$menudrop = "<div class='btn-group'>
				<button data-toggle='dropdown' class='btn btn-success dropdown-toggle' aria-expanded='false'><i class='mdi mdi-menu' aria-haspopup='false'></i> Menu</button>
				<ul class='dropdown-menu dropdown-menu-right' x-placement='bottom-start'>";
				$filename = base_url("compras/editar/");
				$menudrop .= "<li><a role='button' href='" . $filename.$rows->id_compra. "' ><i class='mdi mdi-square-edit-outline' ></i> Editar</a></li>";
				$menudrop .= "<li><a  class='delete_row'  id=" . $rows->id_compra . " ><i class='mdi mdi-trash-can-outline'></i> Eliminar</a></li>";
				$menudrop .= "<li><a  data-toggle='modal' data-target='#viewModal' data-refresh='true'  role='button' class='detail' data-id=".$rows->id_compra."><i class='mdi mdi-eye-check' ></i> Detalles</a></li>";
				$menudrop .= "</ul></div>";
				$data[] = array(
					$rows->id_compra,
					$rows->fecha,
					$rows->hora,
					$rows->concepto,
					$rows->correlativo,
					$rows->total,
					$rows->nombre,
					$menudrop,
				);
			}
			$total = $this->compras->total_rows_compra();
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
					$rows = $this->compras->get_detail_ci($id);
					if($rows && $id!=""){
							$data = array(
									"rows"=>$rows,
									"process" => "cargas",
							);
							$this->load->view("compras/ver_detalle.php",$data);
					}else{
							redirect('errorpage');
					}
			}
	}
	function detalle_des($id=-1){
			if($this->input->method(TRUE) == "GET"){
					$id = $this->uri->segment(3);
					$rows = $this->compras->get_detail_di($id);
					if($rows && $id!=""){
							$data = array(
									"rows"=>$rows,
									"process" => "descarga",
							);
							$this->load->view("compras/ver_detalle.php",$data);
					}else{
							redirect('errorpage');
					}
			}
	}

	function cargar(){
		if($this->input->method(TRUE) == "GET"){
    	$proveedores=$this->utils->get_detail_rows("proveedores",array('1' => 1, ));
			$row_tipodoc=$this->utils->get_detail_rows("tipodoc",array('provee' =>1,));//
			$data = array(
				"sucursal"=>$this->compras->get_detail_rows("sucursales",array('1' => 1, )),
				"id_sucursal" => $this->session->id_sucursal,
				"proveedores"=>$proveedores,
				"tipodoc"=>$row_tipodoc,
			);

			$extras = array(
				'css' => array(
				),
				'js' => array(
					"js/scripts/compras.js"
				),
			);

			layout("compras/carga",$data,$extras);
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->load->model("ProductosModel","productos");
			$this->utils->begin();
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$total = $this->input->post("total");
			$total_final = $this->input->post("total_final");
			$total_iva = $this->input->post("total_iva");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_sucursal = $this->input->post("sucursal");
			$id_usuario = $this->session->id_usuario;
			$alias_tipodoc = $this->input->post("tipo_doc");
			$numero_doc= $this->input->post("numero_doc");
			$dias_credito= $this->input->post("numero_dias");
			$id_proveedor = $this->input->post("id_proveedor");
			$hora = date("H:i:s");

			$correlativo = $this->compras->get_max_correlative('ci',$id_sucursal);

			//procedemos a verificar el tipo de documento
			/*
			if ($alias_tipodoc=="CCF"||$alias_tipodoc=="IMP") {
				// credito fiscal...
				$ivaT = $total - ($total/1.13);
			}
			else {
				// consumidor final...
				$ivaT = 0;
			}
			*/
			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'iva' => $total_iva,
				'total' => $total_final,
				'id_sucursal' => $id_sucursal,
				'correlativo' => $correlativo,
				'id_usuario' => $id_usuario,
				'finalizada' => 1,
				'alias_tipodoc'=>$alias_tipodoc,
				'numero_doc'=>$numero_doc,
				'dias_credito'=>$dias_credito,
				'id_proveedor'=>$id_proveedor,
			);

			$imei_required = false;

			$id_compra = $this->compras->inAndCon('compra',$data);
			if($id_compra!=NULL){

				foreach ($data_ingreso as $fila)
				{
					$costo = $fila['costo'];
					$subtotal = $fila['subtotal'];

					//procedemos a verificar el tipo de documento
					if ($alias_tipodoc=="CCF"||$alias_tipodoc=="IMP") {
						// credito fiscal...
						$iva = $costo *0.13;
						$costo_iva = $costo + $iva;
						$ivaS = ($subtotal*0.13);
						//$subtotal = $subtotal + ($subtotal*0.13);
					}
					else{
						// consumidor final...
						$iva = $costo - ($costo/1.13);
						$costo_iva = $costo;
						$ivaS = 0;
					}
					$id_producto = $fila['id_producto'];
					$cantidad = $fila['cantidad'];
					$precio_sugerido = $fila['precio_sugerido'];
					$color = $fila['color'];

					$form_data = array(
						'id_compra' => $id_compra,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'iva' => $iva,
						'costo' => $costo,
						'costo_iva' => $costo_iva,
						'precio' => $precio_sugerido,
						'cantidad' => $cantidad,
						'iva_subtotal' => $ivaS,
						'subtotal' => $subtotal+$ivaS,
					);
					$id_detalle = $this->compras->inAndCon('detalle_compra',$form_data);
					$this->utils->update(
						"producto",
						array(
							'precio_sugerido' => $precio_sugerido,
							'costo_s_iva' => $costo,
							'costo_c_iva' => round($costo*1.13),
						 ),
						 "id_producto=$id_producto"
						);
					$stock_data = $this->compras->get_stock($id_producto,$color,$id_sucursal);
					$newstock = ($stock_data->cantidad)+$cantidad;
					$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
					$precios = $this->productos->get_precios_exis($id_producto);
					$this->compras->update_cost($costo,$id_producto,$precios);

					if ($this->compras->has_imei_required($id_producto)) {
						// code...
						$imei_required=true;
					}
				}

				if ($imei_required) {
					// code...
					//$this->utils->update("compra",array('requiere_imei' => 1, ),"id_compra=$id_compra");
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

	function editar($id=-1){

		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->compras->get_one_row("compra", array('id_compra' => $id,));
			$proveedores=$this->utils->get_detail_rows("proveedores",array('1' => 1, ));
			$row_tipodoc=$this->utils->get_detail_rows("tipodoc",array('provee' =>1,));//
			if($row && $id!=""){
				$data = array(
					"row"=>$row,
					"detalles"=>$this->compras->get_detail_ci($id),
					"sucursal"=>$this->compras->get_detail_rows("sucursales",array('1' => 1, )),
					"id_sucursal" => $row->id_sucursal,
					"proveedores"=>$proveedores,
					"tipodoc"=>$row_tipodoc,
				);
				$extras = array(
					'css' => array(
					),
					'js' => array(
						"js/scripts/compras.js"
					),
				);
				layout("compras/editar",$data,$extras);
			}else{
				redirect('errorpage');
			}
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->load->model("ProductosModel","productos");
			$this->utils->begin();
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$total = $this->input->post("total");
			$total_final = $this->input->post("total_final");
			$total_iva = $this->input->post("total_iva");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_usuario = $this->session->id_usuario;
			$id_sucursal = $this->input->post("sucursal");
			$id_compra = $this->input->post("id_compra");
			$alias_tipodoc = $this->input->post("tipo_doc");
			$numero_doc= $this->input->post("numero_doc");
			$dias_credito= $this->input->post("numero_dias");
			$id_proveedor = $this->input->post("id_proveedor");
			$hora = date("H:i:s");
			$row = $this->compras->get_one_row("compra", array('id_compra' => $id_compra,));
			$id_sucursalO = $row->id_sucursal;

			//procedemos a verificar el tipo de documento
			/*
			if ($alias_tipodoc=="CCF"||$alias_tipodoc=="IMP") {
				// credito fiscal...
				$ivaT = $total - ($total/1.13);
				//$total = $total + $ivaT;
			}
			else{
				// consumidor final...
				$ivaT = 0;
			}
			*/
			if ($id_sucursalO==$id_sucursal) {
				// code...
				$data = array(
					'fecha' => $fecha,
					'hora' => $hora,
					'concepto' => $concepto,
					'iva' => $total_iva,
					'total' => $total_final,
					'id_sucursal' => $id_sucursal,
					'id_usuario' => $id_usuario,

					'finalizada' => 1,
					'alias_tipodoc'=>$alias_tipodoc,
					'numero_doc'=>$numero_doc,
					'dias_credito'=>$dias_credito,
						'id_proveedor'=>$id_proveedor,
				);
			}
			else {
				// code...
				$correlativo = $this->compras->get_max_correlative('ci',$id_sucursal);
				$data = array(
					'fecha' => $fecha,
					'hora' => $hora,
					'concepto' => $concepto,
					'iva' => $total_iva,
					'total' => $total_final,
					'id_sucursal' => $id_sucursal,
					'id_usuario' => $id_usuario,

					'correlativo' => $correlativo,
					'finalizada' => 1,
					'alias_tipodoc'=>$alias_tipodoc,
					'numero_doc'=>$numero_doc,
					'dias_credito'=>$dias_credito,
						'id_proveedor'=>$id_proveedor,
				);
			}
			$imei_required = false;
			/*editar encabezado*/
			$this->utils->update('compra',$data,"id_compra=$id_compra");

			/*descargar los detalles previos*/
			$detalles_previos = $this->compras->get_detail_ci($id_compra);
			foreach ($detalles_previos as $key) {
				// code...
				$stock_data = $this->compras->get_stock($key->id_producto,$key->id_color,$id_sucursalO);
				$newstock = ($stock_data->cantidad)-($key->cantidad);
				$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
			}
			/*eliminar detalles previos*/
			$this->utils->delete("detalle_compra","id_compra=$id_compra");

			/*nuevos detalles*/
			foreach ($data_ingreso as $fila)
			{
				$costo = $fila['costo'];
				$subtotal = $fila['subtotal'];

				//procedemos a verificar el tipo de documento

				if ($alias_tipodoc=="CCF"||$alias_tipodoc=="IMP") {
					// credito fiscal...
					$iva = $costo *0.13;
					$costo_iva = $costo + $iva;
					$ivaS = ($subtotal*0.13);
					//$subtotal = $subtotal + ($subtotal*0.13);
				}
				else{
					// consumidor final...
					$iva = $costo - ($costo/1.13);
					$costo_iva = $costo;
					$ivaS = 0;
				}
				//echo $subtotal;
				$id_producto = $fila['id_producto'];
				$cantidad = $fila['cantidad'];
				$precio_sugerido = $fila['precio_sugerido'];
				$color = $fila['color'];

				$form_data = array(
					'id_compra' => $id_compra,
					'id_producto' => $id_producto,
					'id_color' => $color,
					'iva' => $iva,
					'costo' => $costo,
					'costo_iva' => $costo_iva,
					'precio' => $precio_sugerido,
					'cantidad' => $cantidad,
					'iva_subtotal' => $ivaS,
					'subtotal' => $subtotal+$ivaS,
				);
				$id_detalle = $this->compras->inAndCon('detalle_compra',$form_data);
				$this->utils->update(
					"producto",
					array(
						'precio_sugerido' => $precio_sugerido,
						'costo_s_iva' => $costo,
						'costo_c_iva' => round($costo*1.13),
					 ),
					 "id_producto=$id_producto"
					);
				$stock_data = $this->compras->get_stock($id_producto,$color,$id_sucursal);
				$newstock = ($stock_data->cantidad)+$cantidad;
				$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
				$precios = $this->productos->get_precios_exis($id_producto);
				$this->compras->update_cost($costo,$id_producto,$precios);
				if ($this->compras->has_imei_required($id_producto)) {
					// code...
					$imei_required=true;
				}
			}

			if ($imei_required) {
				// code...
				//$this->utils->update("compra",array('requiere_imei' => 1, ),"id_compra=$id_compra");
			}
			$this->utils->commit();
			$xdatos["type"]="success";
			$xdatos['title']='Información';
			$xdatos["msg"]="Registo ingresado correctamente!";

			echo json_encode($xdatos);
		}
	}

	function imei($id=-1){
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->compras->get_one_row("compra", array('id_compra' => $id,));
			if($row && $id!=""){
				$data = array(
					"row"=>$row,
					"detalles"=>$this->compras->get_detail_ci($id),
				);
				$extras = array(
					'css' => array(
					),
					'js' => array(
						"js/scripts/ingreso_imei.js"
					),
				);
				layout("compras/cargaimei",$data,$extras);
			}else{
				redirect('errorpage');
			}
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->utils->begin();

			$errors = false;
			$array_error= array("Log");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_compra = $this->input->post("id_compra");
			foreach ($data_ingreso as $fila) {
				// code...
				$form_data = array(
					'id_producto' => $fila['id_producto'],
					'imei' => $fila['imei'],
					'id_detalle' => $fila['id_detalle'],
					'chain' => $fila['chain'],
					'id_compra' => $id_compra,
					'vendido' => 0,
				);

				$id_detalle = $this->compras->inAndCon('compra_imei',$form_data);

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
				$this->utils->update("compra",array('imei_ingresado' => 1, ),"id_compra=$id_compra");
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
			$row = $this->compras->get_one_row("compra", array('id_compra' => $id,));

			$info = $this->compras->get_imei_ci($id);
			$detalles = array();
			$c=0;
			foreach ($info as $key) {
				// code...
				$detalles[$c]=array(
					'id_compra' => $key->id_compra,
					'id_producto' => $key->id_producto,
					'id_detalle' => $key->id_detalle,
					'nombre' => $key->nombre,
					'chain' => $key->chain,
					'data' => $this->compras->get_imei_ci_det($key->chain),
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
						"js/scripts/ingreso_imei.js"
					),
				);
				layout("compras/editarimei",$data,$extras);
			}else{
				redirect('errorpage');
			}
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->utils->begin();

			$errors = false;
			$array_error= array("Log");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_compra = $this->input->post("id_compra");
			foreach ($data_ingreso as $fila) {
				// code...
				$form_data = array(
					'imei' => $fila['imei'],
				);

				$this->utils->update("compra_imei",$form_data,"id_imei=$fila[id_imei]");
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
			$id_compra = $this->input->post("id");
			$this->utils->begin();
			$row = $this->compras->get_one_row("compra", array('id_compra' => $id_compra,));
			$id_sucursal = $row->id_sucursal;
			/*descargar los detalles previos*/
			$detalles_previos = $this->compras->get_detail_ci($id_compra);
			foreach ($detalles_previos as $key) {
				// code...
				$stock_data = $this->compras->get_stock($key->id_producto,$key->id_color,$id_sucursal);
				$newstock = ($stock_data->cantidad)-($key->cantidad);
				$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
			}
			/*eliminar detalles previos*/
			$this->utils->delete("detalle_compra","id_compra=$id_compra");
			$this->utils->delete("compra","id_compra=$id_compra");


			$this->utils->commit();
			$response["type"] = "success";
			$response["title"] = "Información";
			$response["msg"] = "Registro eliminado con éxito!";

			echo json_encode($response);
		}
	}
	/*********************************************************/
	/*********************************************************/
	/************************CARGAS***************************/
	/*********************************************************/
	/*********************************************************/

	/*********************************************************/
	/*********************************************************/
	/************************DESCARGAS************************/
	/*********************************************************/
	/*********************************************************/
	public function descargas()
	{
		$data = array(
			"titulo"=> "Descargas de Compras",
			"icono"=> "mdi mdi-archive",
			"buttons" => array(
				0 => array(
					"icon"=> "mdi mdi-plus",
					'url' => 'compras/descargar',
					'txt' => ' Descarga de Compras',
					'modal' => false,
				),
			),
			"table"=>array(
				"ID"=>4,
				"Fecha"=>10,
				"Hora"=>10,
				"Concepto"=>30,
				"Correlativo"=>10,
				"Total"=>10,
				"Responsable"=>15,
				"Acciones"=>10,
			),
			"proceso" => 'descarga',
		);
		$extras = array(
			'css' => array(
			),
			'js' => array(
				"js/scripts/compras.js"
			),
		);
		layout("template/admin",$data,$extras);
	}

	function get_data_descarga(){
		$draw = intval($this->input->post("draw"));
		$start = intval($this->input->post("start"));
		$length = intval($this->input->post("length"));

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
			0 => 'cp.fecha',
			1 => 'cp.concepto',
			2 => 'cp.correlativo',
			3 => 'cp.total',
			4 => 'u.nombre',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}

		$row = $this->compras->get_collection_descarga($order, $search, $valid_columns, $length, $start, $dir);

		if ($row != 0) {
			$data = array();
			foreach ($row as $rows) {

				$menudrop = "<div class='btn-group'>
				<button data-toggle='dropdown' class='btn btn-success dropdown-toggle' aria-expanded='false'><i class='mdi mdi-menu' aria-haspopup='false'></i> Menu</button>
				<ul class='dropdown-menu dropdown-menu-right' x-placement='bottom-start'>";
				$filename = base_url("compras/editar_descarga/");
				if ($rows->imei_ingresado==0) {
					// code...
					$menudrop .= "<li><a role='button' href='" . $filename.$rows->id_descarga. "' ><i class='mdi mdi-square-edit-outline' ></i> Editar</a></li>";
					if ($rows->requiere_imei==1) {
						// code...
						$menudrop .= "<li><a role='button' href='" .  base_url("compras/imei_descarga/").$rows->id_descarga. "' ><i class='mdi mdi-text-box-check' ></i> Ingresar IMEI's</a></li>";
					}
					$menudrop .= "<li><a  class='delete_row_des'  id=" . $rows->id_descarga . " ><i class='mdi mdi-trash-can-outline'></i> Eliminar</a></li>";
				}
				else {
					// code...
					$menudrop .= "<li><a role='button' href='" .  base_url("compras/editarimei_descarga/").$rows->id_descarga. "' ><i class='mdi mdi-file-document-edit-outline' ></i> Editar IMEI's</a></li>";

				}

				$menudrop .= "<li><a  data-toggle='modal' data-target='#viewModal' data-refresh='true'  role='button' class='detail_des' data-id=".$rows->id_descarga."><i class='mdi mdi-eye-check' ></i> Detalles</a></li>";

				$menudrop .= "</ul></div>";


				$data[] = array(
					$rows->id_descarga,
					$rows->fecha,
					$rows->hora,
					$rows->concepto,
					$rows->correlativo,
					$rows->total,
					$rows->nombre,
					$menudrop,
				);
			}
			$total = $this->compras->total_rows_descarga();
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

	function descargar(){
		if($this->input->method(TRUE) == "GET"){
			$extras = array(
				'css' => array(
				),
				'js' => array(
					"js/scripts/compras.js"
				),
			);
			$data = array();
			layout("compras/descarga",$data,$extras);
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->load->model("ProductosModel","productos");
			$this->utils->begin();
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$total = $this->input->post("total");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_sucursal =$this->session->id_sucursal;
			$id_usuario = $this->session->id_usuario;
			$hora = date("H:i:s");

			$correlativo = $this->compras->get_max_correlative('di',$id_sucursal);

			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'total' => $total,
				'id_sucursal' => $id_sucursal,
				'correlativo' => $correlativo,
				'id_usuario' => $id_usuario,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
			);

			$imei_required = false;

			$id_compra = $this->compras->inAndCon('compras_descarga',$data);
			if($id_compra!=NULL){

				foreach ($data_ingreso as $fila)
				{
					$id_producto = $fila['id_producto'];
					$costo = $fila['costo'];
					$cantidad = $fila['cantidad'];
					$precio_sugerido = $fila['precio_sugerido'];
					$subtotal = $fila['subtotal'];
					$color = $fila['color'];

					$form_data = array(
						'id_descarga' => $id_compra,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'costo' => $costo,
						'precio' => $precio_sugerido,
						'cantidad' => $cantidad,
						'subtotal' => $subtotal,
					);
					$id_detalle = $this->compras->inAndCon('compras_descarga_detalle',$form_data);
					/*$this->utils->update(
						"producto",
						array(
							'precio_sugerido' => $precio_sugerido,
							'costo_s_iva' => $costo,
							'costo_c_iva' => round($costo*1.13),
						 ),
						 "id_producto=$id_producto"
					 );*/
					$stock_data = $this->compras->get_stock($id_producto,$color,$id_sucursal);
					$newstock = ($stock_data->cantidad)-$cantidad;
					$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
					$precios = $this->productos->get_precios_exis($id_producto);
					//$this->compras->update_cost($costo,$id_producto,$precios);

					if ($this->compras->has_imei_required($id_producto)) {
						// code...
						$imei_required=true;
					}
				}

				if ($imei_required) {
					// code...
					//$this->utils->update("compras_descarga",array('requiere_imei' => 1, ),"id_descarga=$id_compra");
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

	function editar_descarga($id=-1){

		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->compras->get_one_row("compras_descarga", array('id_descarga' => $id,));
			$detalles = $this->compras->get_detail_di($id);
			$detalles1 = array();
			foreach ($detalles as $detalle)
			{
				$id_producto = $detalle->id_producto;
				$precio = $detalle->precio;
				$detallesp = $this->precios_producto($id_producto, $precio);
				if($detallesp != 0)
				{
					$stock_data = $this->compras->get_stock($id_producto,$detalle->id_color,$this->session->id_sucursal);
					$detalle->precios = $detallesp["precios"];
					$detalle->stock = $stock_data->cantidad;
					$detalle->id_stock = $stock_data->id_stock;
					$detalle->id_color = $stock_data->id_color;

				}
				array_push($detalles1,$detalle);
			}
			if($row && $id!=""){
				$data = array(
					"row"=>$row,
					"detalles"=>$detalles1
				);
				$extras = array(
					'css' => array(
					),
					'js' => array(
						"js/scripts/compras.js"
					),
				);
				layout("compras/editar_descarga",$data,$extras);
			}else{
				redirect('errorpage');
			}
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->utils->begin();
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$total = $this->input->post("total");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_sucursal =$this->session->id_sucursal;
			$id_usuario = $this->session->id_usuario;
			$id_compra = $this->input->post("id_compra");
			$hora = date("H:i:s");

			//$correlativo = $this->compras->get_max_correlative('di',$id_sucursal);

			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'total' => $total,
				'id_sucursal' => $id_sucursal,
				'id_usuario' => $id_usuario,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
			);

			$imei_required = false;

			/*editar encabezado*/
			$this->utils->update('compras_descarga',$data,"id_descarga=$id_compra");

			/*descargar los detalles previos*/
			$detalles_previos = $this->compras->get_detail_di($id_compra);
			foreach ($detalles_previos as $key) {
				// code...
				$stock_data = $this->compras->get_stock($key->id_producto,$key->id_color,$id_sucursal);
				$newstock = ($stock_data->cantidad)+($key->cantidad);
				$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
			}
			/*eliminar detalles previos*/
			$this->utils->delete("compras_descarga_detalle","id_descarga=$id_compra");

			/*nuevos detalles*/
			foreach ($data_ingreso as $fila)
			{
				$id_producto = $fila['id_producto'];
				$costo = $fila['costo'];
				$cantidad = $fila['cantidad'];
				$precio_sugerido = $fila['precio_sugerido'];
				$subtotal = $fila['subtotal'];
				$color = $fila['color'];

				$form_data = array(
					'id_descarga' => $id_compra,
					'id_producto' => $id_producto,
					'id_color' => $color,
					'costo' => $costo,
					'precio' => $precio_sugerido,
					'cantidad' => $cantidad,
					'subtotal' => $subtotal,
				);
				$id_detalle = $this->compras->inAndCon('compras_descarga_detalle',$form_data);
				$stock_data = $this->compras->get_stock($id_producto,$color,$id_sucursal);
				$newstock = ($stock_data->cantidad)-$cantidad;
				$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
				if ($this->compras->has_imei_required($id_producto)) {
					// code...
					$imei_required=true;
				}
			}

			if ($imei_required) {
				// code...
				$this->utils->update("compras_descarga",array('requiere_imei' => 1, ),"id_descarga=$id_compra");
			}
			$this->utils->commit();
			$xdatos["type"]="success";
			$xdatos['title']='Información';
			$xdatos["msg"]="Registo ingresado correctamente!";

			echo json_encode($xdatos);
		}
	}

	function imei_descarga($id=-1){
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->compras->get_one_row("compras_descarga", array('id_descarga' => $id,));
			if($row && $id!=""){
				$data = array(
					"row"=>$row,
					"detalles"=>$this->compras->get_detail_di($id),
				);
				$extras = array(
					'css' => array(
					),
					'js' => array(
						"js/scripts/descargo_imei.js"
					),
				);
				layout("compras/cargaimei_descarga",$data,$extras);
			}else{
				redirect('errorpage');
			}
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->utils->begin();

			$errors = false;
			$array_error= array("Log");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_compra = $this->input->post("id_compra");
			foreach ($data_ingreso as $fila) {
				// code...
				$form_data = array(
					'id_producto' => $fila['id_producto'],
					'imei' => $fila['imei'],
					'id_detalle' => $fila['id_detalle'],
					'chain' => $fila['chain'],
					'id_descarga' => $id_compra,
					'vendido' => 0,
				);

				$id_detalle = $this->compras->inAndCon('compras_descarga_imei',$form_data);

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
				$this->utils->update("compras_descarga",array('imei_ingresado' => 1, ),"id_descarga=$id_compra");
				$this->utils->commit();
				$xdatos["type"]="success";
				$xdatos['title']='Información';
				$xdatos["msg"]="Registo ingresado correctamente!";
			}

			echo json_encode($xdatos);

		}
	}

	function editarimei_descarga($id=-1){
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->compras->get_one_row("compras_descarga", array('id_descarga' => $id,));

			$info = $this->compras->get_imei_di($id);
			$detalles = array();
			$c=0;
			foreach ($info as $key) {
				// code...
				$detalles[$c]=array(
					'id_compra' => $key->id_descarga,
					'id_producto' => $key->id_producto,
					'id_detalle' => $key->id_detalle,
					'nombre' => $key->nombre,
					'chain' => $key->chain,
					'data' => $this->compras->get_imei_di_det($key->chain),
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
						"js/scripts/descargo_imei.js"
					),
				);
				layout("compras/editarimei_descarga",$data,$extras);
			}else{
				redirect('errorpage');
			}
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->utils->begin();

			$errors = false;
			$array_error= array("Log");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_compra = $this->input->post("id_compra");
			foreach ($data_ingreso as $fila) {
				// code...
				$form_data = array(
					'imei' => $fila['imei'],
				);

				$this->utils->update("compras_descarga_imei",$form_data,"id_imei=$fila[id_imei]");
			}

			$this->utils->commit();
			$xdatos["type"]="success";
			$xdatos['title']='Información';
			$xdatos["msg"]="Registo ingresado correctamente!";


			echo json_encode($xdatos);

		}
	}
	function delete_descarga(){
		if($this->input->method(TRUE) == "POST"){
			$id_compra = $this->input->post("id");
			$this->utils->begin();
			$row = $this->compras->get_one_row("compras_descarga", array('id_descarga' => $id_compra,));
			$id_sucursal = $row->id_sucursal;
			/*descargar los detalles previos*/
			$detalles_previos = $this->compras->get_detail_di($id_compra);
			foreach ($detalles_previos as $key) {
				// code...
				$stock_data = $this->compras->get_stock($key->id_producto,$key->id_color,$id_sucursal);
				$newstock = ($stock_data->cantidad)+($key->cantidad);
				$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);
			}
			/*eliminar detalles previos*/
			$this->utils->delete("compras_descarga_detalle","id_descarga=$id_compra");
			$this->utils->delete("compras_descarga","id_descarga=$id_compra");


			$this->utils->commit();
			$response["type"] = "success";
			$response["title"] = "Información";
			$response["msg"] = "Registro eliminado con éxito!";

			echo json_encode($response);
		}
	}
	/*********************************************************/
	/*********************************************************/
	/***********************DESCARGAS*************************/
	/*********************************************************/
	/*********************************************************/
	public function detalle_producto_stock($id=0)
	{
		$id_sucursal = $this->session->id_sucursal;
		if($id == 0)
		{
			$id_producto = $this->input->post("id");
			$id_color = $this->input->post("id_s");
		}
		$lista = "";
		$stock_data = $this->compras->get_stock($id_producto,$id_color,$id_sucursal);
		$prods = $this->compras->get_producto($id_producto);
		$precios = $this->compras->get_precios_exis($id_producto);
		$colores = $this->compras->get_detail_rows("producto_color", array('id_producto' => $id_producto,));

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

		$lista .= "<select class='form-control precios sel' style='width:100%;'>";
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
		$lista .= "</select>";

		$xdatos["precio_sugerido"]=$prods->precio_sugerido;
		$xdatos["precios"] = $lista;
		$xdatos["stock"] = $stock_data->cantidad;
		$xdatos["id_s"] = $stock_data->id_stock;
		$xdatos["colores"]=$color_select;
		$xdatos["marca"] = $prods->marca;
		$xdatos["modelo"] = $prods->modelo;
		$xdatos["costo"] = number_format($costo,2,".","");
		$xdatos["costo_iva"] = number_format($costo_iva,2,".","");
		echo json_encode($xdatos);
	}

	public function detalle_producto($id=0)
	{
		$id_sucursal = $this->session->id_sucursal;
		if($id == 0)
		{
			$id_producto = $this->input->post("id");
		}
		$lista = "";
		$prods = $this->compras->get_producto($id_producto);
		$precios = $this->compras->get_precios_exis($id_producto);
		$colores = $this->compras->get_detail_rows("producto_color", array('id_producto' => $id_producto,));
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

		$lista .= "<select class='form-control precios sel' style='width:100%;'>";
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
		$lista .= "</select>";

		//$stock_data = $this->compras->get_stock($id_producto,$id_sucursal);
		$xdatos["precio_sugerido"]=$prods->precio_sugerido;
		$xdatos["precios"] = $lista;
		//$xdatos["stock"] = $stock_data->cantidad;
		$xdatos["colores"]=$color_select;
		$xdatos["marca"] = $prods->marca;
		$xdatos["modelo"] = $prods->modelo;
		$xdatos["costo"] = number_format($costo,2,".","");
		$xdatos["costo_iva"] = number_format($costo_iva,2,".","");
		echo json_encode($xdatos);
	}
	public function precios_producto($id=0,$precioe=0)
	{
		$id_sucursal = $this->session->id_sucursal;

		$precios = $this->compras->get_precios_exis($id);
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
	$rows = $this->compras->get_productos($query);
	$output = array();
	if($rows!=NULL) {
		foreach ($rows as $row) {
			$output[] = array(
				//'producto' => $row->id_producto."|".$row->nombre." ".$row->marca." ".$row->modelo,
				'producto' => $row->id_producto."|".$row->nombre."|".$row->modelo."|".$row->marca."|".$row->codigo_barra,
			);
		}
	}
	echo json_encode($output);
}
function get_productos_stock(){
	$query = $this->input->post("query");
	$id_sucursal = $this->session->id_sucursal;
	$rows = $this->compras->get_productos_stock($query,$id_sucursal);
	$output = array();
	if($rows!=NULL) {
		foreach ($rows as $row) {
			$output[] = array(
				//'producto' => $row->id_producto."|".$row->nombre." ".$row->marca." ".$row->modelo,
				'producto' => $row->id_producto."|".$row->modelo." ".$row->color."|".$row->id_color,
			);
		}
	}
	echo json_encode($output);
}

}

/* End of file Compras.php */
