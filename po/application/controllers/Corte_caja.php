<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Corte_caja extends CI_Controller {

	public function index(){
		validar_session($this);
		$data = array(
			'tipo' => '',
			'nombre_archivo' => 'Administrar Corte Caja',
			'icono' => 'fa fa-user-circle',
			'urljs' => 'funciones_corte_caja.js',
			'url_agregar' => 'Corte_caja/agregar_corte',
			'txt_agregar' => 'Corte Caja',
			'modal_agregar' => 1,
			'tabla'=> array(
				'#' => 1,
				'FECHA' => 2,
				'TOTAL' => 2,
				'EFECTIVO EN CAJA' => 2,
				'OBSERVACIONES'=>4,
				'ACCIONES'=>1,
			),
		);
		$this->load->helper('template_helper');
		template('template/admin',$data);
	}

	public function get_data()
	{
		$this->load->model('Corte_caja_model');

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
			0 => 'total_efectivo',
			1 => 'efectivo_caja',
			2 => 'observaciones',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->session->id_sucursal;
		$caja = $this->Corte_caja_model->get_caja($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);

		if ($caja != 0) {
			$data = array();
			$num = 1;
			foreach ($caja as $rows) {

				$menudrop = "<div class='btn-group'>
				<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
				<ul class='dropdown-menu dropdown-primary'>";
				$filename = base_url() . "Corte_caja/ver_detalle";
				$menudrop .= "<li><a data-toggle='modal' href='" . $filename . "/" .$rows->id_corte. "' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-pencil\"></i> Ver detalle</a></li>";
				$menudrop .= "</ul></div>";

				$data[] = array(
					$num,
					$rows->fecha,
					$rows->total_efectivo,
					$rows->efectivo_caja,
					$rows->observaciones,
					$menudrop,
				);

				$num++;
			}
			$total = $this->Corte_caja_model->total_caja($id_sucursal);
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

	function  agregar_corte() {
		date_default_timezone_set("America/El_Salvador");
		$ahora= date("Y-m-d");
		$this->load->helper('template_helper');
		$this->load->model('Corte_caja_model');
		$row3 = $this->Corte_caja_model->get_total_factura($ahora);
		$factura=$row3->total;
		$row4 = $this->Corte_caja_model->get_total_abono($ahora);
		$abonos=$row4->total;
		$row1 = $this->Corte_caja_model->get_total_otros_ingresos($ahora);
		$otros_ingresos=$row1->total;
		$row2 = $this->Corte_caja_model->get_total_egresos($ahora);
		$egresos=$row2->total;
		$total_efectivo=$factura+$abonos+$otros_ingresos-$egresos;

		$datos = array(
			'ingresos' =>  number_format($factura,2),
			'abonos' =>  number_format($abonos,2),
			'otros_ingresos' =>  number_format($otros_ingresos,2),
			'egresos' =>  number_format($egresos,2),
			'total_efectivo' =>  number_format($total_efectivo,2),
		);
		$this->load->view('corte_caja/agregar_corte_caja',$datos);

	}
	function insertar()
	{
		$this->load->model('Corte_caja_model');
		date_default_timezone_set("America/El_Salvador");

		$id_usuario=$this->session->id_usuario;
		$id_sucursal=$this->session->id_sucursal;
		$ingresos = $this->input->post("ingresos");
		$abonos = $this->input->post("abonos");
		$otros_ingresos = $this->input->post("otros_ingresos");
		$egresos = $this->input->post("egresos");
		$total_efectivo = $this->input->post("total_efectivo");
		$efectivo_caja = $this->input->post("efectivo_caja");
		$observaciones = $this->input->post("observaciones");
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");

		$table = 'corte_caja';
	    $form_data = array (
	    	'fecha' => $fecha,
	    	'hora' => $hora,
	    	'ingresos' => $ingresos,
	    	'abono' => $abonos,
	    	'otros_ingresos' => $otros_ingresos,
				'egresos' => $egresos,
	    	'total_efectivo' => $total_efectivo,
	    	'efectivo_caja' => $efectivo_caja,
	    	'observaciones' => $observaciones,
	    	'id_usuario' => $id_usuario,
	    	'id_sucursal' => $id_sucursal,
	    );
			$insert = $this->Corte_caja_model->save($table, $form_data);
	    if($insert)
	    {
	      $xdatos['typeinfo']='Success';
	      $xdatos['msg']='Corte generado con exito ';
	      $xdatos['process']='edited';
	    }
	    else
	    {
	      $xdatos['typeinfo']='error';
	      $xdatos['msg']='Corte no pudo ser generado';
	    }

		$xdatos['url'] = base_url("Corte_caja");
		echo json_encode($xdatos);

	}

	function  ver_detalle($id) {
		date_default_timezone_set("America/El_Salvador");
		$ahora= date("Y-m-d");
		$this->load->helper('template_helper');
		$this->load->model('Corte_caja_model');
		$row = $this->Corte_caja_model->get_corte_caja($id);
		$datos = array(
			'fecha'=>d_m_Y($row->fecha),
			'hora'=>$row->hora,
			'ingresos'=>number_format($row->ingresos,2),
			'abonos'=>number_format($row->abono,2),
			'otros_ingresos'=>number_format($row->otros_ingresos,2),
			'egresos'=>number_format($row->egresos,2),
			'total_efectivo'=>number_format($row->total_efectivo,2),
			'efectivo_caja'=>number_format($row->efectivo_caja,2),
			'observaciones'=>$row->observaciones,
		);
		$this->load->view('corte_caja/ver_detalle',$datos);

	}


}

/* End of file Gerencias.php */
