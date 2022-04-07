<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#reporte ingresos egresos por dia
class Inventario extends CI_Controller {

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
			'nombre_archivo' => 'Reporte de Inventario',
			'icono' => 'fa fa-file-pdf-o',
			"sucur"=>$dat,
			"urlpost"=>"Ried/Resumen_rango",
		);
		$this->load->helper('template_helper');
		template('inventario/inventario', $data);
	}

	public function report()
	{
		if($this->input->method(TRUE) == "POST"){
			$id = $this->uri->segment(3);
			$this->load->library('ReporteIn');
			$pdf = $this->reportein->getInstance('P','mm', 'Letter');
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

			$data = array("empresa" => $dat->nombre,"imagen" => $logo,"2020-01-01");
			$pdf->setear($data);
			$pdf->addPage();

			$this->db->select("codigo,marca,casa,existencia");
			$this->db->from("aro");
			$this->db->where("sucursal",$id_sucursal);
			$this->db->where("existencia>=",$this->input->post("mostrar"));
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$data = $query->result();
				foreach ($data as $key) {
					$array_data = array(
						array($key->codigo,50,"C"),
						array($key->marca,50,"L"),
						array($key->casa,50,"L"),
						array($key->existencia,55,"C"),
					);
					$pdf->LineWrite($array_data);

				}
			}
			$pdf->Output();
		}
		else {
			// code...
			redirect('errorpage');
		}
	}

}
 ?>
