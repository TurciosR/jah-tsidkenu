<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#reporte ingresos egresos por dia
class Libreta extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Utils_model',"utils");
	}
	public function index()
	{
		header("Location:".base_url()."Dashboard");
	}

	public function ver(){
		$this->load->model("Libreta_model");
		$hasta = date("d-m-Y");
		$desde = date("d-m-Y",strtotime($hasta."- 1 month"));
		if ($this->session->admin==1) {
			$this->db->where("1",1);
			$query  = $this->db->get("sucursal");
		}
		else {
			$this->db->where("id",$this->session->id_sucursal);
			$query  = $this->db->get("sucursal");

		}

		if ($query->num_rows() > 0) {
			// code...
			$dat = $query->result();
		}
		$data = array(
			'nombre_archivo' => 'Libreta de ingresos y egresos',
			'icono' => 'fa fa-file-pdf-o',
			"desde"=>$desde,
			"hasta"=>$hasta,
			"sucur"=>$dat,
			"urlpost"=>"Ried/Resumen_rango",
		);
		$this->load->helper('template_helper');
		template('libreta/libreta', $data);
	}

	public function get_data()
	{
		$this->load->model('Libreta_model');
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
			0 => 'id',
			1 => 'fecha',
			2 => 'descripcion',
			3 => 'venta',
			4 => 'ingreso',
			7 => '(DATE_FORMAT(libreta.fecha, "%d-%m-%Y"))'
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->input->post('id_sucursal');
		$ini=Y_m_d($this->input->post('ini'));
		$fin=Y_m_d($this->input->post('fin'));

		$acumulado = 0;
		$movs = $this->Libreta_model->get_movs($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal,$ini,$fin);
		if ($movs != 0) {
			$data = array();
			$num = 1;
			foreach ($movs as $rows) {

				$acumulado = $acumulado+$rows->venta;

				$elim = "
				<button class='btn btn-info edit' id=$rows->id> <i class='fa fa-edit'></i></button>
				<button class='btn btn-danger elim' id=$rows->id> <i class='fa fa-trash'></i></button>
				";
				$data[] = array(
					$rows->id,
					$rows->fecha,
					$rows->descripcion,
					$rows->venta,
					$rows->ingreso,
					/*number_format($acumulado,2),*/
					$elim
				);

				$num++;
			}

			$total = $this->Libreta_model->total_rows();
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
				"draw" => 0,
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => $data
			);
		}
		echo json_encode($output);
		exit();
	}

	public function agregar()
	{
		// code...
		$venta = $this->input->post("venta");
		$ingreso = $this->input->post("ingreso");
		$sucursal = $this->input->post("sucursal");
		$concepto = $this->input->post("concepto");
		$fecha = Y_m_d($this->input->post("fecha"));

		$id_usuario =  $this->session->id_usuario;
		$data = array(
			'venta' => $venta,
			'ingreso' => $ingreso,
			'id_sucursal' => $sucursal,
			'descripcion' => $concepto,
			'fecha' => $fecha,
			'id_usuario' => $id_usuario,
			'borrado' => 0,
		);
		$this->db->insert("libreta",$data);
		$xdatos['typeinfo']='Success';
		$xdatos['msg']='Dato ingresado';

		echo json_encode($xdatos);
	}

	public function editar()
	{
		// code...
		$venta = $this->input->post("venta");
		$ingreso = $this->input->post("ingreso");
		$sucursal = $this->input->post("sucursal");
		$concepto = $this->input->post("concepto");
		$fecha = Y_m_d($this->input->post("fecha"));
		$id_usuario =  $this->session->id_usuario;
		$id = $this->input->post("idl");

		$data = array(
			'venta' => $venta,
			'ingreso' => $ingreso,
			'id_sucursal' => $sucursal,
			'descripcion' => $concepto,
			'fecha' => $fecha,
			'id_usuario' => $id_usuario,
			'borrado' => 0,
		);

		foreach ($data as $key => $value) {
			// code...
				$this->db->set($key, $value);
		}
		$this->db->where('id', $id);
		$this->db->update('libreta');
		$xdatos['typeinfo']='Success';
		$xdatos['msg']='Dato Editado';

		echo json_encode($xdatos);
	}

	public function borrar() {
		// code...
		$id = $this->input->post("id");

		$formdata = array(
			'borrado' => 1,
		);

		$this->db->set('borrado', 1);
		$this->db->where('id', $id);
		$this->db->update('libreta');

		$xdatos['typeinfo']='Success';
		$xdatos['msg']='Dato borrado';

		echo json_encode($xdatos);
	}

	public function report()
	{
		if($this->input->method(TRUE) == "POST"){
			$id = $this->uri->segment(3);
			$this->load->library('ReporteIE');
			$pdf = $this->reporteie->getInstance('P','mm', 'Letter');
			$logo = base_url()."assets/img/logo1.png";
			$pdf->SetMargins(10, 10);
			$pdf->SetLeftMargin(5);
			$pdf->AliasNbPages();
			$pdf->SetAutoPageBreak(true, 15);
			$pdf->AliasNbPages();
			$pdf->SetFont('Arial','',10);

			$id_sucursal = $this->input->post("idsucursal");
			$this->db->where("id",$id_sucursal);
			$q = $this->db->get("sucursal");
			$dat = $q->row();

			$ini =  Y_m_d($this->input->post("inicio"));
			$fin =  Y_m_d($this->input->post("fin"));
			$data = array("empresa" => $dat->nombre,"imagen" => $logo, 'fecha' => $this->input->post("fecha1"));
			$pdf->setear($data);
			$pdf->addPage();

			$l = array(
				't' => 10,
				'n' => 25,
				'd' => 95,
				'c' => 25,
				's' => 25,
				'a' => 25,
			);

			$total = array(
				'Ingre' => 0,
				'Venta' => 0,
			);

			$pdf->Cell(205,5,"Detalle Ingresos y Egresos del ".$this->input->post("inicio")." al ".$this->input->post("fin"),0,1,'C',0);
			$pdf->Cell($l['t'],5,"ID",1,0,'C',0);
			$pdf->Cell($l['n'],5,"FECHA",1,0,'C',0);
			$pdf->Cell($l['d'],5,"DESCRIPCION",1,0,'C',0);
			$pdf->Cell($l['c'],5,"VENTA",1,0,'C',0);
			$pdf->Cell($l['s'],5,"INGRESO",1,0,'C',0);
			$pdf->Cell($l['a'],5,"ACUMULADO",1,1,'C',0);

			$this->db->select('id,descripcion, DATE_FORMAT(fecha, "%d-%m-%Y") as fecha,venta, ingreso');
			$this->db->where("id_sucursal", $id_sucursal);
			$this->db->where("fecha>=", $ini);
			$this->db->where("fecha<=", $fin);
			$this->db->where("borrado", 0);
			$this->db->order_by("fecha","ASC");

			$acumulado= 0;
			$query = $this->db->get('libreta');
			if ($query->num_rows() > 0){
				$data = $query->result();
				foreach ($data as $key) {
					// code...
					//dato, tamaño , aliniacion

					$acumulado = $acumulado + $key->venta;
					$array_data = array(
						array($key->id,$l['t'],"C"),
						array($key->fecha,$l['n'],"C"),
						array($key->descripcion,$l['d'],"L"),
						array("$".number_format($key->venta,2),$l['c'],"R"),
						array("$".number_format($key->ingreso,2),$l['s'],"R"),
						array("$".number_format($acumulado,2),$l['a'],"R"),
					);
					$total['Venta'] = $total['Venta'] + $key->venta;
					$total['Ingre'] = $total['Ingre'] + $key->ingreso;
					$pdf->LineWrite($array_data);
				}
			}
			else {
				$pdf->Cell(10,5,"NO HAY DATOS",0,1,'L',0);
			}
			$pdf->Cell($l['t']+$l['d']+$l['n'],5,"TOTAL","T",0,'C',0);
			$pdf->Cell($l['c'],5,"$".number_format(	$total['Venta'],2),"T",0,'R',0);
			$pdf->Cell($l['s'],5,"$".number_format(	$total['Ingre'],2),"T",0,'R',0);
			$pdf->Cell($l['a'],5,"$".number_format(	$acumulado,2),"T",0,'R',0);

			$pdf->Ln(5);

			$pdf->Output();
		}
		else {
			// code...
			redirect('errorpage');
		}
	}

	public function getitem()
	{
		$this->load->model('Libreta_model');
		$id = $this->input->post("id");
		$dat =  $this->Libreta_model->get_one_row("libreta",array('id' => $id, ));

		$xdatos['id'] = $dat->id;
		$xdatos['fecha'] = d_m_Y($dat->fecha);
		$xdatos['venta'] = $dat->venta;
		$xdatos['ingreso'] = $dat->ingreso;
		$xdatos['id_sucursal'] = $dat->id_sucursal;
		$xdatos['descripcion'] = $dat->descripcion;

		echo json_encode($xdatos);

	}

}
 ?>
