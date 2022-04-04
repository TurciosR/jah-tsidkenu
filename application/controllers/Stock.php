<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {
	/*
	Global table name
	*/
	private $table = "stock";
	private $pk = "id_stock";

	function __construct()
	{
		parent::__construct();
		$this->load->model('UtilsModel',"utils");
		$this->load->model("ProductosModel","productos");
		$this->load->helper("upload_file");
	}

	public function index()
	{

		$this->load->model("InventarioModel","inventario");
		$data = array(
			"titulo"=> "Stock",
			"icono"=> "mdi mdi-archive",
			"buttons" => array(
				0 => array(
					"icon"=> "mdi mdi-plus",
					'url' => 'productos',
					'txt' => ' Productos',
					'modal' => false,
				),
			),
			"selects" => array(
				0 => array(
					"name" => "sucursales",
					"data" => $this->inventario->get_detail_rows("sucursales",array('1' => 1, )),
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
				"Barcode"=>10,
				"Categoria"=>20,
				"Descripcion"=>20,
				"Marca"=>20,
				"Modelo"=>20,
				"Color"=>10,
				"Stock"=>10,
				"Detalles"=>10,
			),
		);
		$extras = array(
			'css' => array(
			),
			'js' => array(
				"js/scripts/stock.js"
			),
		);
		layout("template/admin",$data,$extras);
	}

	function get_data_stock(){
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
			0 => 'producto.id_producto',
			1 => 'producto.nombre',
			2 => 'producto.codigo_barra',
			3 => 'producto.modelo',
			4 => 'categoria.nombre',
			5 => 'producto.marca',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}

		$row = $this->productos->get_collection_stock($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);

		if ($row != 0) {
			$data = array();
			foreach ($row as $rows) {

				$state = $rows->activo;
				if($state==1){
					$show_text = "<span class='badge badge-success font-bold'>Activo<span>";
				}
				else{
					$show_text = "<span class='badge badge-danger font-bold'>Inactivo<span>";
				}

				$menudrop = "<div class='btn-group'>
				<button data-toggle='dropdown' class='btn btn-success dropdown-toggle' aria-expanded='false'><i class='mdi mdi-menu' aria-haspopup='false'></i> Menu</button>
				<ul class='dropdown-menu dropdown-menu-right' x-placement='bottom-start'>";
				$menudrop .= "<li><a  data-toggle='modal' data-target='#viewModal' data-refresh='true'  role='button' class='detail' data-id=".$rows->id_stock."><i class='mdi mdi-eye-check' ></i> Detalles</a></li>";

				$menudrop .= "</ul></div>";

				$this->db->select("producto_color.color");
				$this->db->from("stock");
				$this->db->join("producto_color","producto_color.id_color=stock.id_color","left");
				$this->db->where("id_stock",$rows->id_stock);
				$query = $this->db->get();

				$dato = $query->row();

				$data[] = array(
					$rows->codigo_barra,
					$rows->categoria,
					$rows->nombre,
					$rows->marca,
					$rows->modelo,
					$dato->color,
					$rows->stock,
					$menudrop,
				);
			}
			$total = $this->productos->total_rows_stock($id_sucursal);
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
		$this->load->model("InventarioModel","inventario");
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);

			$sd = $this->inventario->get_one_row("stock",array('id_stock' => $id,));
			if($id!="" && $sd){
				$data_b=0;
				$data = array(
					"exis_p"=> $data_b,
					"precios" => $this->inventario->get_detail_rows("producto_precio",array('id_producto' => $sd->id_producto, )),
				);
				$this->load->view("productos/ver_detalle.php",$data);
			}else{
				redirect('errorpage');
			}
		}
	}

}

/* End of file Productos.php */
