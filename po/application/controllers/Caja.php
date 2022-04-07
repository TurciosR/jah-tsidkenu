<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caja extends CI_Controller {

	public function index(){
		validar_session($this);
		$data = array(
			'nombre_archivo' => 'Administrar Caja',
			'icono' => 'fa fa-user-circle',
			'urljs' => 'funciones_caja.js',
			'tabla'=> array(
				'#' => 1,
				'RESPONSABLE' => 3,
				'CONCEPTO' => 3,
				'FECHA' => 1,
				'MONTO'=>1,
				'TIPO'=>2,
				'ACCIONES'=>1,
			),
		);
		$this->load->helper('template_helper');
		template('caja/admin',$data);
	}

	public function get_data()
	{
		$this->load->model('Caja_model');

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
			0 => 'concepto',
			1 => 'responsable',
			2 => 'total',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->session->id_sucursal;
		$caja = $this->Caja_model->get_caja($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);

		if ($caja != 0) {
			$data = array();
			$num = 1;
			foreach ($caja as $rows) {

				$menudrop = "<div class='btn-group'>
				<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
				<ul class='dropdown-menu dropdown-primary'>";
				$filename = base_url() . "Caja/editar_movimiento_view";
				$menudrop .= "<li><a data-toggle='modal' href='" . $filename . "/" .$rows->id_movimiento. "' data-target='#editEModal' data-refresh='true'><i class=\"fa fa-pencil\"></i> Editar</a></li>";
				$filename = base_url() . "Caja/borrar_movimiento_caja";
				$menudrop .= "<li><a  data='".$rows->id_movimiento."' class='eliminar'><i class='fa fa-eraser'></i> Eliminar</a></li>";
				$menudrop .= "</ul></div>";

				$data[] = array(
					$num,
					$rows->responsable,
					$rows->concepto,
					$rows->fecha,
					$rows->total,
					$rows->tipo,
					$menudrop,
				);

				$num++;
			}
			$total = $this->Caja_model->total_caja($id_sucursal);
			$output = array(
				"draw" => $draw,
				"recordsTotal" => $total,
				"recordsFiltered" => $total,
				"data" => $data
			);
		} else {
			$data[] = array(
				"",
				"No se encontraron registros",
				"",
				"",
				"",
				"",
				"",
			);
			$output = array(
				"draw" => 0,
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => $data
			);
		}
		echo json_encode($output);
		exit();
	}

	function  agregar_ingreso_view() {
		$this->load->helper('template_helper');
		$this->load->model('Caja_model');
		$this->load->view('caja/agregar_ingreso');

	}
	function agregar_ingreso()
	{

		$this->load->model('Caja_model');
		date_default_timezone_set("America/El_Salvador");
		$encargado = $this->input->post("encargado");
		$concepto = $this->input->post("concepto");
		$monto = $this->input->post("monto");
		$fecha = date("Y-m-d");
		$id_sucursal=$this->session->id_sucursal;

		$tabla = "mov_caja";
		$form_data = array(
			'fecha' => $fecha,
			'total' => $monto,
			'concepto' => $concepto,
			'responsable'=>$encargado,
			'entrada' => 1,
			'id_sucursal' => $id_sucursal,
		);
		$total = $this->Caja_model->existe_mov_caja($form_data);
		if ($total==0) {
			$insetar = $this->Caja_model->save($tabla, $form_data);
			$id_mov= $this->Caja_model->_insert_id();
			if($insetar)
			{
				$xdatos['typeinfo']='Success';
				$xdatos['msg']='Ingreso agregado correctamente !';
				$xdatos['id_mov']=$id_mov;
			}
			else
			{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Error al realizar el ingreso !'._error();
			}

		}else {
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Esta movimiento de caja  ya existe!';
		}
		$xdatos['url'] = base_url("Caja");
		echo json_encode($xdatos);

	}
	function  agregar_egreso_view() {
		$this->load->helper('template_helper');
		$this->load->model('Caja_model');
		$this->load->view('caja/agregar_egreso');

	}
	function agregar_egreso()
	{

		$this->load->model('Caja_model');
		date_default_timezone_set("America/El_Salvador");
		$encargado = $this->input->post("encargado");
		$concepto = $this->input->post("concepto");
		$monto = $this->input->post("monto");
		$fecha = date("Y-m-d");
		$id_sucursal=$this->session->id_sucursal;

		$tabla = "mov_caja";
		$form_data = array(
			'fecha' => $fecha,
			'total' => $monto,
			'concepto' => $concepto,
			'responsable'=>$encargado,
			'salida' => 1,
			'id_sucursal' => $id_sucursal,
		);
		$total = $this->Caja_model->existe_mov_caja($form_data);
		if ($total==0) {
			$insetar = $this->Caja_model->save($tabla, $form_data);
			$id_mov= $this->Caja_model->_insert_id();
			if($insetar)
			{
				$xdatos['typeinfo']='Success';
				$xdatos['msg']='Egreso agregado correctamente !';
				$xdatos['id_mov']=$id_mov;
			}
			else
			{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Error al realizar el egreso !'._error();
			}

		}else {
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Esta movimiento de caja  ya existe!';
		}
		$xdatos['url'] = base_url("Caja");
		echo json_encode($xdatos);

	}


	function editar_movimiento_view($id=-1){
		$this->load->helper('template_helper');
		$this->load->model('Caja_model');
		$rows1 = $this->Caja_model->get($id);
		$tipo="Salida";
		if ($rows1->entrada) {
			$tipo="Entrada";
		}
		$data = array(
			'id_movimiento'=>$rows1->id_movimiento,
			'total'=>$rows1->total,
			'concepto'=>$rows1->concepto,
			'responsable'=>$rows1->responsable,
			'tipo'=>$tipo,
		);
		$this->load->view('caja/editar_movimiento',$data);
	}
	function editar_movimiento()
	{
		$this->load->model('Caja_model');
		date_default_timezone_set("America/El_Salvador");
		$encargado = $this->input->post("encargado");
		$concepto = $this->input->post("concepto");
		$monto = $this->input->post("monto");
		$id_movimiento = $this->input->post("id_movimiento");
		$tipo = $this->input->post("tipo");
		$fecha = date("Y-m-d");
		$id_sucursal=$this->session->id_sucursal;

		$tabla = "mov_caja";
		$form_data = array(
			'total' => $monto,
			'concepto' => $concepto,
			'responsable'=>$encargado,
		);
		$datos = array(
			'total' => $monto,
			'concepto' => $concepto,
			'responsable'=>$encargado,
			'id_movimiento!='=>$id_movimiento,
		);
		$total = $this->Caja_model->existe_mov_caja($datos);
		if ($total==0) {
			$insetar = $this->Caja_model->update($id_movimiento, $form_data);
			if($insetar)
			{
				$xdatos['typeinfo']='Success';
				$xdatos['msg']=$tipo.' editada correctamente !';
				$xdatos['id_mov']=$id_movimiento;
			}
			else
			{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Error al realizar el '.$tipo.' !'._error();
			}

		}else {
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Esta movimiento de caja  ya existe!';
		}
		$xdatos['url'] = base_url("Caja");
		echo json_encode($xdatos);

	}
	function eliminar_movimento(){
		$this->load->model('Caja_model');
		$id_movimiento = $this->input->post("id");
		$delete = $this->Caja_model->delete("mov_caja", $id_movimiento);
		if($delete)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Movimiento eliminado correctamente!';
		}
		else
		{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Movimiento no pudo ser eliminado!';
		}

		$xdatos['url'] = base_url("Caja");

		echo json_encode($xdatos);
	}


}

/* End of file Gerencias.php */
