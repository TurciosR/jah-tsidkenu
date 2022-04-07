<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reportes extends CI_Controller
{
	public function index()
	{
		header("Location:".base_url()."Dashboard");
	}
	public function generar_corte_caja(){
		$this->load->model("Reportes_model");
		$hasta = date("d-m-Y");
		$desde = date("d-m-Y",strtotime($hasta."- 1 year"));
		$data = array(
			'nombre_archivo' => 'Generar Reporte Corte Caja',
			'icono' => 'fa fa-file-pdf-o',
			"desde"=>$desde,
			"hasta"=>$hasta,
			"urlpost"=>"Reportes/Corte_caja",
		);
		$this->load->helper('template_helper');
		template('reports/reporte_corte_caja', $data);
	}

	public function Corte_caja(){
		validar_session($this);
		$this->load->model('Utils_model');
		$this->load->helpers('utilities_helper');
		$this->load->model('Reportes_model');
		$fecha1 = ($this->input->post("fecha1"));
		$fecha2 = ($this->input->post("fecha2"));
		if ($fecha1 != "" && $fecha2 != "") {
			list($a, $m, $d) = explode("-", ($fecha1));
			list($a1, $m1, $d1) = explode("-", ($fecha2));
			if ($a == $a1) {
				if ($m == $m1) {
					$fech = "DEL $d AL $d1 DE " . nombre_mes($m) . " DE $a";
				} else {
					$fech = "DEL $d DE " . nombre_mes($m) . " AL $d1 DE " . nombre_mes($m1) . " DE $a";
				}
			} else {
				$fech = "DEL $d DE " . nombre_mes($m) . " DEL $a AL $d1 DE " . nombre_mes($m1) . " DE $a1";
			}
		}
		$this->load->add_package_path(APPPATH . 'third_party/fpdf');
		$this->load->library('pdf');
		$this->fpdf = new Pdf();
		$this->fpdf->SetTopMargin(0);
		$this->fpdf->SetLeftMargin(16);
		//Numeracion de paginas
		$this->fpdf->AliasNbPages();
		//Salto automatico de pagina margen de 20 mm
		$this->fpdf->SetAutoPageBreak(true, 20);
		//Agrega la pagina a trabajar
		$this->fpdf->AddPage();
		//Seteo de fuente Times New Roman 12
		$this->fpdf->SetFont('Helvetica', 'B', 12);
		//Ruta del logo de la organizacion
		$id_sucursal=$this->session->id_sucursal;
		$rowsu=$this->Reportes_model->get_sucursal($id_sucursal);
		$nombre_sucursal=$rowsu->nombre;

		$this->fpdf->Cell(180, 7,utf8_decode($nombre_sucursal), 0, 1, "C");
		$this->fpdf->SetFont('Helvetica', 'B', 10);
		$this->fpdf->Cell(180, 5, utf8_decode("CORTE CAJA"), 0, 1, "C");
		$this->fpdf->Cell(180, 5, utf8_decode($fech), 0, 1, "C");
		$this->fpdf->Ln(5);
		$this->fpdf->SetFillColor(255, 255, 255);
		$this->fpdf->SetTextColor(0, 0, 0);

		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$this->fpdf->Cell(20, 5, utf8_decode("FECHA"), 1, 0, "C", 1);
		$this->fpdf->Cell(22, 5, utf8_decode("VENTAS"), 1, 0, "C", 1);
		$this->fpdf->Cell(22, 5, utf8_decode("ABONOS"), 1, 0, "C", 1);
		$this->fpdf->Cell(22, 5, utf8_decode("INGRESOS"), 1, 0, "C", 1);
		$this->fpdf->Cell(22, 5, utf8_decode("EGRESOS"), 1, 0, "C", 1);
		$this->fpdf->Cell(22, 5, utf8_decode("TOTAL"), 1, 0, "C", 1);
		$this->fpdf->Cell(22, 5, utf8_decode("TOTAL CAJA"), 1, 0, "C", 1);
		$this->fpdf->Cell(22, 5, utf8_decode("DIFERENCIA"), 1, 1, "C", 1);

		$y = $this->fpdf->GetY();
		$datos = $this->Reportes_model->get_corte_caja(Y_m_d($fecha1),Y_m_d($fecha2));
		foreach ($datos as $row) {
			$fecha=d_m_Y($row->fecha);
			$hora=$row->hora;
			$ingresos=number_format($row->ingresos,2);
			$abonos=number_format($row->abono,2);
			$otros_ingresos=number_format($row->otros_ingresos,2);
			$egresos=number_format($row->egresos,2);
			$total_efectivo=number_format($row->total_efectivo,2);
			$efectivo_caja=number_format($row->efectivo_caja,2);
			$observaciones=$row->observaciones;
			$diferencia=$efectivo_caja-$total_efectivo;
			$array_data = array(
				0 => array($fecha,28,20,"L"),
				1 => array($ingresos,19,22,"R"),
				2 => array($abonos,19,22,"R"),
				3 => array($otros_ingresos,18,22,"R"),
				4 => array($egresos,10,22,"R"),
				5 => array($total_efectivo,14,22,"R"),
				6 => array($efectivo_caja,14,22,"R"),
				7 => array(number_format($diferencia,2),14,22,"R"),
			);
			$data=array_procesor($array_data);
			$total_lineas=count($data[0]["valor"]);
			$total_columnas=count($data);
			$this->fpdf->SetFont('Helvetica', '', 9);
			for ($e=0; $e < $total_lineas; $e++) {
				for ($j=0; $j < $total_columnas; $j++) {
					$salto=0;
					$abajo="LRT";
					if ($j==$total_columnas-1) {
						$salto=1;
					}
					if ($e<=$total_lineas-1) {
						$abajo="LR";
					}
					if ($e==$total_lineas-1) {
						$abajo="LRB";
					}
					$this->fpdf->Cell($data[$j]["size"][$e],5,utf8_decode($data[$j]["valor"][$e]),$abajo,$salto,$data[$j]["aling"][$e]);
				}
			}
		}
		ob_clean();
		$this->fpdf->Output("corte_caja.pdf", "I");
	}
}
