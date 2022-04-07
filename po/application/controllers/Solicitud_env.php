<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_env extends CI_Controller {

	public function index(){
		validar_session($this);
		$data = array(
			'tipo' => 5,
			'nombre_archivo' => 'Solicitudes Realizadas',
			'icono' => 'fa fa-user-circle',
			'urljs' => 'funciones_solicitud_env.js',
			//'url_agregar' => '',
			//'txt_agregar' => '',
			'tabla'=> array(
				'ID' => 1,
				'ARO' => 1,
				'USUARIO' => 3,
				'SUCURSAL'=>3,
				'F. SOLICITUD'=>1,
				'F. RESOLUCION'=>2,
				'ESTADO'=>1,
			),
		);
		$this->load->helper('template_helper');
		template('template/admin',$data);
	}

	public function get_data()
	{
		$this->load->model('Solicitud_model');
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
			0 => 'id_solicitud',
			1 => 'codigo',
			2 => 'sur.nombre',
			3 => 'u.nombre',
			4 => 'fecha_re',
			10 => 'DATE_FORMAT(s.fecha, "%d-%m-%Y")',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->session->id_sucursal;
		$solicitudes = $this->Solicitud_model->get_solisitud_env($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);
		if ($solicitudes != 0) {
			$data = array();
			$num = 1;
			foreach ($solicitudes as $rows) {
				if ($rows->estado==0){
				    $estado='<span class="label label-warning">PENDIENTE</span>';
                }elseif ($rows->estado==1){
                    $estado='<span class="label label-primary">ENVIADO</span>';
                }elseif ($rows->estado==2){
                    $estado='<span class="label label-danger">CANCELADA</span>';
                }

				$data[] = array(
					$num,
					$rows->codigo,
					$rows->nombre,
					$rows->sucur,
					$rows->fecha,
					$rows->fecha_re,
                    $estado,
				);

				$num++;
			}
			$total = $this->Solicitud_model->count_solicitud_env($id_sucursal);
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
