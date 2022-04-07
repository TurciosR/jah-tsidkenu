<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salidas extends CI_Controller {

	public function index(){
		validar_session($this);
		$data = array(
			'tipo' => 5,
			'nombre_archivo' => 'Salidas de Aro',
			'icono' => 'fa fa-user-circle',
			'urljs' => 'funciones_salida.js',
			//'url_agregar' => '',
			//'txt_agregar' => '',
			'tabla'=> array(
				'ID' => 1,
				'CODIGO ARO' => 2,
				'MOTIVO' => 4,
				'USUARIO'=>3,
				'CANTIDAD'=>1,
				'FECHA'=>1
			),
		);
		$this->load->helper('template_helper');
		template('template/admin',$data);
	}

	public function get_data()
	{
		$this->load->model('Salidas_model');
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
			0 => 'id_movimiento',
			1 => 'motivo',
			2 => 'usuario',
			3 => 'fecha',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->session->id_sucursal;
		$solicitudes = $this->Salidas_model->get_salida($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);
		if ($solicitudes != 0) {
			$data = array();
			$num = 1;
			foreach ($solicitudes as $rows) {
				$data[] = array(
					$num,
					$rows->codigo,
					$rows->motivo,
					$rows->nombre,
					$rows->cantidad,
					$rows->fecha,
				);

				$num++;
			}
			$total = $this->Salidas_model->count_salida($id_sucursal);
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
				"draw" => 0,
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => $data
			);
		}
		echo json_encode($output);
		exit();
	}

}


/* End of file Usuarios.php */
