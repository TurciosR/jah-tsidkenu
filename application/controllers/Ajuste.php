<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajuste extends CI_Controller {
	/*
	Global table name
	*/
	private $table = "stock";
	private $pk = "id_producto";

	function __construct()
	{
		parent::__construct();
		$this->load->model('UtilsModel',"utils");
		$this->load->model("InventarioModel","inventario");
	}
	public function admin()
	{
		$data = array(
			"titulo"=> "Ajuste de Inventario",
			"icono"=> "mdi mdi-archive",
			"buttons" => array(
				0 => array(
					"icon"=> "mdi mdi-plus",
					'url' => 'Ajuste/agregar',
					'txt' => ' Ajuste de Inventario',
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
			"proceso" => 'carga',
		);
		$extras = array(
			'css' => array(
			),
			'js' => array(
				"js/scripts/ajuste.js"
			),
		);
		layout("template/admin",$data,$extras);
	}

	function agregar(){
		if($this->input->method(TRUE) == "GET"){

			$data = array(
				"sucursal"=>$this->inventario->get_detail_rows("sucursales",array('1' => 1, )),
				"id_sucursal" => $this->session->id_sucursal,
			);

			$extras = array(
				'css' => array(
				),
				'js' => array(
					"js/scripts/ajuste.js"
				),
			);

			layout("ajuste/ajuste",$data,$extras);
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->load->model("ProductosModel","productos");
			$this->utils->begin();
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$total = $this->input->post("total");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			//$id_sucursal =$this->session->id_sucursal;
			$id_sucursal = $this->input->post("sucursal");
			$id_usuario = $this->session->id_usuario;
			$hora = date("H:i:s");

			$correlativo = $this->inventario->get_max_correlative('aj',$id_sucursal);

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

			$id_carga = $this->inventario->inAndCon('inventario_ajuste',$data);
			if($id_carga!=NULL){

				foreach ($data_ingreso as $fila)
				{
					$id_producto = $fila['id_producto'];
					$costo = $fila['costo'];
					$cantidad = $fila['cantidad'];
					$precio_sugerido = $fila['precio_sugerido'];
					$subtotal = $fila['subtotal'];
					$color = $fila['color'];

					/*obtenemos el stock a este momento*/
					$stock_data = $this->inventario->get_stock($id_producto,$color,$id_sucursal);

					$form_data = array(
						'id_ajuste' => $id_carga,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'costo' => $costo,
						'precio' => $precio_sugerido,
						'stock_anterior '=> $stock_data->cantidad,
						'cantidad' => $cantidad,
						'subtotal' => $subtotal,
					);
					$id_detalle = $this->inventario->inAndCon('inventario_ajuste_detalle',$form_data);

					$newstock = $cantidad;
					$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);

					if ($this->inventario->has_imei_required($id_producto)) {
						// code...
						$imei_required=true;
					}
				}

				/*if ($imei_required) {
					// code...
					$this->utils->update("inventario_descarga",array('requiere_imei' => 1, ),"id_descarga=$id_carga");
				}*/
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

	function get_productos(){
		$query = $this->input->post("query");
		$id_sucursal = $this->input->post("id_sucursal");
		$rows = $this->inventario->get_productos_stock($query,$id_sucursal);
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

	public function detalle_producto($id=0)
	{
		//$id_sucursal = $this->session->id_sucursal;
		if($id == 0)
		{
			$id_sucursal = $this->input->post("id_sucursal");
			$id_producto = $this->input->post("id");
			$id_color = $this->input->post("id_s");
		}
		$lista = "";
		$stock_data = $this->inventario->get_stock($id_producto,$id_color,$id_sucursal);
		$prods = $this->inventario->get_producto($id_producto);
		$precios = $this->inventario->get_precios_exis($id_producto);
		$colores = $this->inventario->get_detail_rows("producto_color", array('id_producto' => $id_producto,));

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

	function get_data_ajuste(){
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

		$row = $this->inventario->get_collection_ajuste($order, $search, $valid_columns, $length, $start, $dir);

		if ($row != 0) {
			$data = array();
			foreach ($row as $rows) {

				$menudrop = "<div class='btn-group'>
				<button data-toggle='dropdown' class='btn btn-success dropdown-toggle' aria-expanded='false'><i class='mdi mdi-menu' aria-haspopup='false'></i> Menu</button>
				<ul class='dropdown-menu dropdown-menu-right' x-placement='bottom-start'>";
				$filename = base_url("inventario/editar_descarga/");

				$menudrop .= "<li><a  data-toggle='modal' data-target='#viewModal' data-refresh='true'  role='button' class='detail_aj' data-id=".$rows->id_ajuste."><i class='mdi mdi-eye-check' ></i> Detalles</a></li>";

				$menudrop .= "</ul></div>";


				$data[] = array(
					$rows->id_ajuste,
					$rows->fecha,
					$rows->hora,
					$rows->concepto,
					$rows->correlativo,
					$rows->total,
					$rows->nombre,
					$menudrop,
				);
			}
			$total = $this->inventario->total_rows_ajuste();
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

	function detalle_aj($id=-1){
			if($this->input->method(TRUE) == "GET"){
					$id = $this->uri->segment(3);
					$rows = $this->inventario->get_detail_aj($id);
					if($rows && $id!=""){
							$data = array(
									"rows"=>$rows,
									"process" => "ajuste",
							);
							$this->load->view("inventario/ver_detalle.php",$data);
					}else{
							redirect('errorpage');
					}
			}
	}

}



/* End of file Ajuste.php */
