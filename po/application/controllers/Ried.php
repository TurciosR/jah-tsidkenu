<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#reporte ingresos egresos por dia
class Ried extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Utils_model',"utils");
	}
	public function index()
	{
		header("Location:".base_url()."Dashboard");
	}

	public function generar(){
		$this->load->model("Reportes_model");
		$desde = date("d-m-Y");
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
			'nombre_archivo' => 'Generar Reporte Ingresos Egresos diario',
			'icono' => 'fa fa-file-pdf-o',
			"desde"=>$desde,
			"sucur"=>$dat,
			"urlpost"=>"Ried/Resumen",
		);
		$this->load->helper('template_helper');
		template('reports/reporte_ingresos_egresos_dia', $data);
	}

	public function generar_rango(){
		$this->load->model("Reportes_model");
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
			'nombre_archivo' => 'Generar Reporte Ingresos Egresos diario',
			'icono' => 'fa fa-file-pdf-o',
			"desde"=>$desde,
			"hasta"=>$hasta,
			"sucur"=>$dat,
			"urlpost"=>"Ried/Resumen_rango",
		);
		$this->load->helper('template_helper');
		template('reports/reporte_ingresos_egresos_rango', $data);
	}

  public function Resumen()
	{
    if($this->input->method(TRUE) == "POST"){
			$id = $this->uri->segment(3);
      $this->load->library('ReporteRied');
      $pdf = $this->reporteried->getInstance('P','mm', 'Letter');
      $logo = base_url()."assets/img/logo1.png";
      $pdf->SetMargins(10, 10);
      $pdf->SetLeftMargin(5);
      $pdf->AliasNbPages();
      $pdf->SetAutoPageBreak(true, 15);
      $pdf->AliasNbPages();
      $pdf->SetFont('Arial','',10);

			$id_sucursal = $this->input->post("sucursal");
			$this->db->where("id",$id_sucursal);
			$q = $this->db->get("sucursal");
			$dat = $q->row();

      $data = array("empresa" => $dat->nombre,"imagen" => $logo, 'fecha' => $this->input->post("fecha1"));
      $pdf->setear($data);
			$pdf->addPage();

			$l = array(
				't' => 20,
				'n' => 20,
				'd' => 105,
				'c' => 30,
				's' => 30,
			);

			$total = array(
				'FacFin' => 0,
				'FacRet' => 0,
				'FacAbo' => 0,
				'Ing' => 0,
				'Egre' => 0,
			);

			$pdf->Cell(205,5,"Detalle Facturas Finalizadas",0,1,'C',0);
			$pdf->Cell($l['t'],5,"Tipo",1,0,'C',0);
			$pdf->Cell($l['n'],5,"Numero",1,0,'C',0);
			$pdf->Cell($l['d'],5,"Descripcion",1,0,'C',0);
			$pdf->Cell($l['c'],5,"Cantidad",1,0,'C',0);
			$pdf->Cell($l['s'],5,"Subtotal",1,1,'C',0);

      $this->db->select("factura_detalle.descripcion, factura_detalle.cantidad, factura_detalle.sub_total,factura.*");
      $this->db->from("factura_detalle");
      $this->db->join("factura","factura.id_factura = factura_detalle.id_factura");
      $this->db->where('factura.fecha', Y_m_d($this->input->post("fecha1")));
      $this->db->where('factura.id_sucursal', $id_sucursal);
      $this->db->where('factura.estado', 1);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0){
        $data = $query->result();
        foreach ($data as $key) {
          // code...
          //dato, tamaño , aliniacion
          $array_data = array(
            array($key->tipo,$l['t'],"C"),
            array($key->num_doc,$l['n'],"C"),
            array($key->descripcion,$l['d'],"L"),
            array($key->cantidad,$l['c'],"R"),
            array("$".number_format($key->sub_total,2),$l['s'],"R"),
          );
					$total['FacFin'] = $total['FacFin'] + $key->sub_total;
          $pdf->LineWrite($array_data);
        }
			}
			else {
				$pdf->Cell(10,5,"NO HAY DATOS",0,1,'L',0);
			}
			$pdf->Cell($l['t']+$l['d']+$l['n']+$l['c'],5,"TOTAL","T",0,'C',0);
			$pdf->Cell($l['s'],5,"$".number_format(	$total['FacFin'],2),"T",1,'R',0);

			$pdf->Ln(5);


			/*Retencion*/
			$l = array(
				't' => 20,
				'n' => 20,
				'ci' => 135,
				's' => 30,
			);

			$pdf->Cell(205,5,"Detalle Retencion Facturas Finalizadas",0,1,'C',0);
			$pdf->Cell($l['t'],5,"Tipo",1,0,'C',0);
			$pdf->Cell($l['n'],5,"Numero",1,0,'C',0);
			$pdf->Cell($l['ci'],5,"Cliente",1,0,'C',0);
			$pdf->Cell($l['s'],5,"Subtotal",1,1,'C',0);

			$this->db->select("factura.*,cliente.nombre");
			$this->db->from("factura");
			$this->db->join("cliente","cliente.id = factura.id_cliente","left");
			$this->db->where('factura.fecha', Y_m_d($this->input->post("fecha1")));
      $this->db->where('factura.id_sucursal', $id_sucursal);
      $this->db->where('factura.estado', 1);
			$this->db->where('factura.retencion > 0');
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$data = $query->result();
				foreach ($data as $key) {
					// code...
					//dato, tamaño , aliniacion
					$array_data = array(
						array($key->tipo,$l['t'],"C"),
						array($key->num_doc,$l['n'],"C"),
						array($key->nombre,$l['ci'],"L"),
						array("$".number_format($key->retencion,2),$l['s'],"R"),
					);
					$total['FacRet'] = $total['FacRet'] + $key->retencion;
					$pdf->LineWrite($array_data);
				}
			}
			else {
				$pdf->Cell(10,5,"NO HAY DATOS",0,1,'L',0);
			}

			$pdf->Cell($l['t']+$l['n']+$l['ci'],5,"TOTAL","T",0,'C',0);
			$pdf->Cell($l['s'],5,"$".number_format(	$total['FacRet'],2),"T",1,'R',0);


			$pdf->Ln(5);


			/*abonos a cuentas*/
			$l = array(
				't' => 30,
				'n' => 30,
				'ci' => 115,
				's' => 30,
			);
			$pdf->Cell(205,5,"Abonos a Cuentas por cobrar",0,1,'C',0);
			$pdf->Cell($l['t'],5,"Fecha Cuenta",1,0,'C',0);
			$pdf->Cell($l['n'],5,"Numero Doc",1,0,'C',0);
			$pdf->Cell($l['ci'],5,"Cliente",1,0,'C',0);
			$pdf->Cell($l['s'],5,"Abono",1,1,'C',0);

			$this->db->select("cuenta.fecha,abono.abono,cliente.nombre, abono.numero_doc");
			$this->db->from("abono");
			$this->db->join("cuenta","cuenta.id_cuenta=abono.id_cuenta");
			$this->db->join("cliente","cliente.id = cuenta.id_cliente","left");
			$this->db->where('abono.fecha', Y_m_d($this->input->post("fecha1")));
      $this->db->where('cuenta.id_sucursal', $id_sucursal);
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$data = $query->result();
				foreach ($data as $key) {
					// code...
					//dato, tamaño , aliniacion
					$array_data = array(
						array(d_m_Y($key->fecha),$l['t'],"C"),
						array($key->numero_doc,$l['n'],"C"),
						array($key->nombre,$l['ci'],"L"),
						array("$".number_format($key->abono,2),$l['s'],"R"),
					);
					$total['FacAbo'] = $total['FacAbo'] + $key->abono;
					$pdf->LineWrite($array_data);
				}
			}
			else {
				$pdf->Cell(10,5,"NO HAY DATOS",0,1,'L',0);
			}

			$pdf->Cell($l['t']+$l['n']+$l['ci'],5,"TOTAL","T",0,'C',0);
			$pdf->Cell($l['s'],5,"$".number_format(	$total['FacAbo'],2),"T",1,'R',0);

			$pdf->Ln(5);

			/*movimentos de caja*/
			$l = array(
				'c' => 75,
				'r' => 70,
				'e' => 30,
				's' => 30,
			);
			$pdf->Cell(205,5,"Movimientos de caja",0,1,'C',0);
			$pdf->Cell($l['c'],5,"Concepto",1,0,'C',0);
			$pdf->Cell($l['r'],5,"Responsable",1,0,'C',0);
			$pdf->Cell($l['e'],5,"Entrada",1,0,'C',0);
			$pdf->Cell($l['s'],5,"Salida",1,1,'C',0);

			$this->db->select("mov_caja.concepto, mov_caja.responsable,IF(mov_caja.entrada=1,mov_caja.total,0) as ingreso,IF(mov_caja.salida=1,mov_caja.total,0) as salida,mov_caja.id_sucursal");
			$this->db->from("mov_caja");
			$this->db->where('mov_caja.fecha', Y_m_d($this->input->post("fecha1")));
      $this->db->where('mov_caja.id_sucursal', $id_sucursal);
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$data = $query->result();
				foreach ($data as $key) {
					// code...
					//dato, tamaño , aliniacion
					$array_data = array(
						array($key->concepto,$l['c'],"C"),
						array($key->responsable,$l['r'],"C"),
						array("$".number_format($key->ingreso,2),$l['e'],"R"),
						array("$".number_format($key->salida,2),$l['s'],"R"),
					);
					$total['Ing'] = $total['Ing'] + $key->ingreso;
					$total['Egre'] = $total['Egre'] + $key->salida;
					$pdf->LineWrite($array_data);
				}
			}
			else {
				$pdf->Cell(10,5,"NO HAY DATOS",0,1,'L',0);
			}

			$pdf->Cell($l['c']+$l['r'],5,"TOTAL","T",0,'C',0);
			$pdf->Cell($l['e'],5,"$".number_format(	$total['Ing'],2),"T",0,'R',0);
			$pdf->Cell($l['s'],5,"$".number_format(	$total['Egre'],2),"T",1,'R',0);


			$pdf->Ln(5);
			$pdf->Cell(30,5,"(+) Fact. Finalizadas",0,0,'L',0);
			$pdf->Cell(30,5,"$".number_format(	$total['FacFin'],2),0,1,'R',0);
			$pdf->Cell(30,5,"(-) Retencion",0,0,'L',0);
			$pdf->Cell(30,5,"$".number_format(	$total['FacRet'],2),0,1,'R',0);
			$pdf->Cell(30,5,"(+) Abono a Cuenta",0,0,'L',0);
			$pdf->Cell(30,5,"$".number_format(	$total['FacAbo'],2),0,1,'R',0);
			$pdf->Cell(30,5,"(-) Egresos",0,0,'L',0);
			$pdf->Cell(30,5,"$".number_format(	$total['Ing'],2),0,1,'R',0);
			$pdf->Cell(30,5,"(+) Ingresos",0,0,'L',0);
			$pdf->Cell(30,5,"$".number_format(	$total['Egre'],2),0,1,'R',0);
			$pdf->Cell(30,5,"TOTAL","T",0,'C',0);
			$pdf->Cell(30,5,"$".number_format($total['FacFin']-$total['FacRet']+$total['FacAbo']-$total['Ing']+$total['Egre'],2),"T",1,'R',0);

			//SELECT mov_caja.concepto, mov_caja.responsable,IF(mov_caja.entrada=1,mov_caja.total,0) as ingreso,IF(mov_caja.salida=1,mov_caja.total,0) as salida,mov_caja.id_sucursal FROM mov_caja

			$pdf->Output();
    }
    else {
      // code...
      redirect('errorpage');
    }
  }

	public function Resumen_rango()
	{
    if($this->input->method(TRUE) == "POST"){
			$id = $this->uri->segment(3);
      $this->load->library('ReporteRiedR');
      $pdf = $this->reporteriedr->getInstance('P','mm', 'Letter');
      $logo = base_url()."assets/img/logo1.png";
      $pdf->SetMargins(10, 10);
      $pdf->SetLeftMargin(5);
      $pdf->AliasNbPages();
      $pdf->SetAutoPageBreak(true, 15);
      $pdf->AliasNbPages();
      $pdf->SetFont('Arial','',10);

			$id_sucursal = $this->input->post("sucursal");
			$this->db->where("id",$id_sucursal);
			$q = $this->db->get("sucursal");
			$dat = $q->row();

			$desde = $this->input->post("fecha1");
			$hasta = $this->input->post("fecha2");

      $data = array("empresa" => $dat->nombre,"imagen" => $logo, 'desde' => $this->input->post("fecha1") ,'hasta' => $this->input->post("fecha2"));
      $pdf->setear($data);
			$pdf->addPage();

			$l = array(
				'fe' => 30,
				'fa' => 25,
				're' => 30,
				'ab' => 30,
				'in' => 30,
				'eg' => 30,
				'to' => 30,
			);

			$total_g = array(
				'FacFin' => 0,
				'FacRet' => 0,
				'FacAbo' => 0,
				'Ing' => 0,
				'Egre' => 0,
			);

			$pdf->Cell($l['fe'],5,"Fecha",1,0,'C',0);
			$pdf->Cell($l['fa'],5,"Facturas",1,0,'C',0);
			$pdf->Cell($l['re'],5,"Retencion",1,0,'C',0);
			$pdf->Cell($l['ab'],5,"Abonos",1,0,'C',0);
			$pdf->Cell($l['in'],5,"Ingresos",1,0,'C',0);
			$pdf->Cell($l['eg'],5,"Egresos",1,0,'C',0);
			$pdf->Cell($l['to'],5,"Total",1,1,'C',0);

			$newdate = $desde;

			while (strtotime($newdate)<=strtotime($hasta)) {

				$this->db->select("sum(factura_detalle.sub_total) as total");
	      $this->db->from("factura_detalle");
	      $this->db->join("factura","factura.id_factura = factura_detalle.id_factura");
	      $this->db->where('factura.fecha', Y_m_d($newdate));
	      $this->db->where('factura.id_sucursal', $id_sucursal);
	      $this->db->where('factura.estado', 1);
	    	$query = $this->db->get();
				$FacFin = $query->row();

				$this->db->select("sum(factura.retencion) as total");
				$this->db->from("factura");
	      $this->db->where('factura.id_sucursal', $id_sucursal);
	      $this->db->where('factura.estado', 1);
				$this->db->where('factura.retencion > 0');
				$this->db->where('factura.fecha', Y_m_d($newdate));
				$query = $this->db->get();
				$FacRet = $query->row();

				$this->db->select("sum(abono.abono) as total");
				$this->db->from("abono");
				$this->db->join("cuenta","cuenta.id_cuenta=abono.id_cuenta");
				$this->db->where('abono.fecha',  Y_m_d($newdate));
	      $this->db->where('cuenta.id_sucursal', $id_sucursal);
				$query = $this->db->get();
				$FacAbo = $query->row();

				$this->db->select(" sum(IF(mov_caja.entrada=1,mov_caja.total,0)) as ingreso, sum(IF(mov_caja.salida=1,mov_caja.total,0)) as salida,");
				$this->db->from("mov_caja");
				$this->db->where('mov_caja.fecha', Y_m_d($newdate));
	      $this->db->where('mov_caja.id_sucursal', $id_sucursal);
				$query = $this->db->get();
				$TotIge = $query->row();

				$total1 = $FacFin->total - $FacRet->total + $FacAbo->total + $TotIge->ingreso - $TotIge->salida;
				$pdf->Cell($l['fe'],5,$newdate,1,0,'C',0);
				$pdf->Cell($l['fa'],5,number_format($FacFin->total,2),1,0,'R',0);
				$pdf->Cell($l['re'],5,number_format($FacRet->total,2),1,0,'R',0);
				$pdf->Cell($l['ab'],5,number_format($FacAbo->total,2),1,0,'R',0);
				$pdf->Cell($l['in'],5,number_format($TotIge->ingreso,2),1,0,'R',0);
				$pdf->Cell($l['eg'],5,number_format($TotIge->salida,2),1,0,'R',0);
				$pdf->Cell($l['to'],5,number_format($total1,2),1,1,'R',0);

				$newdate =  date("d-m-Y",strtotime($newdate."+ 1 day"));
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
