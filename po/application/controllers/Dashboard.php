<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
		validar_session($this);
		$this->load->helper('template_helper');
		$this->load->model("Dashboard_model");
		/*$id_colaborador = $this->session->id_colaborador;
		$tipo = $this->session->tipo;
		$row = $this->Dashboard_model->get_saldo($id_colaborador);
		$saldo = $row->saldo;

		if($tipo==5){

			$permisos = $this->Dashboard_model->get_permisos_admin();
			$vacaciones = $this->Dashboard_model->get_vacaciones_admin();
			$solP = $this->Dashboard_model->get_solicitudes_vacaciones_admin();
			$solV = $this->Dashboard_model->get_solicitudes_permisos_admin();

			$data = array(
				"saldo"=>$saldo,
				"tipo"=>$tipo,
				"urljs"=>"funciones_dashboard.js",
				"permisos"=>$permisos,
				"vacaciones"=>$vacaciones,
				"solP"=>$solP->num,
				"solV"=>$solV->num
			);
		}
		elseif ($tipo==1){
			$permisos = $this->Dashboard_model->get_permisos($id_colaborador);
			$vacaciones = $this->Dashboard_model->get_vacaciones($id_colaborador);
			$saldo = $this->Dashboard_model->get_saldo_dias($id_colaborador);
			$solP = $this->Dashboard_model->get_solicitudes_permisos($id_colaborador);
			$solV = $this->Dashboard_model->get_solicitudes_vacaciones($id_colaborador);

			$data = array(
				"saldo"=>$saldo,
				"tipo"=>$tipo,
				"urljs"=>"funciones_dashboard.js",
				"permisos"=>$permisos,
				"vacaciones"=>$vacaciones,
				"saldo"=>$saldo->num,
				"solP"=>$solP->num,
				"solV"=>$solV->num
			);
		}
		else{
			$saldo = $this->Dashboard_model->get_saldo_dias($id_colaborador);
			$solP = $this->Dashboard_model->get_historial_permisos($id_colaborador);
			$solV = $this->Dashboard_model->get_historial_vacaciones($id_colaborador);
			$saldo = $this->Dashboard_model->get_saldo_dias($id_colaborador);

			$data = array(
				"saldo"=>$saldo,
				"tipo"=>$tipo,
				"urljs"=>"funciones_dashboard.js",
				"saldo"=>$saldo->num,
				"HistorialP"=>$solP->num,
				"HistorialV"=>$solV->num,
				"saldo"=>$saldo->num,
				"solP"=>$solP->num,
				"solV"=>$solV->num
			);
		}*/

		$data = array(
			"solP"=>1,
			"solV"=>1,
		);
		template('dashboard',$data);
	}
	function grafica_permiso_admin(){
		$this->load->model("Dashboard_model");
		$this->load->helper("Utilities_helper");
		$inicio = restar_meses(date("Y-m-d"),6);
		for($i=0; $i<6; $i++)
		{
			$a = explode("-",$inicio)[0];
			$m = explode("-",$inicio)[1];
			$ult = cal_days_in_month(CAL_GREGORIAN, $m, $a);
			$start = "$a-$m-01";
			$end = "$a-$m-$ult";
			$row = $this->Dashboard_model->grafica_permiso($start,$end);
			$total = $row->total;
			$data[] = array(
				"total" => $total,
				"mes" => nombre_mes($m),
			);
			$inicio = sumar_meses($start,1);
		}
		echo json_encode($data);
	}

	function grafica_vacacion_admin(){
		$this->load->model("Dashboard_model");
		$this->load->helper("Utilities_helper");
		$inicio = restar_meses(date("Y-m-d"),6);
		for($i=0; $i<6; $i++)
		{
			$a = explode("-",$inicio)[0];
			$m = explode("-",$inicio)[1];
			$ult = cal_days_in_month(CAL_GREGORIAN, $m, $a);
			$start = "$a-$m-01";
			$end = "$a-$m-$ult";
			$row = $this->Dashboard_model->grafica_vacacion($start,$end);
			$total = $row->total;
			$data[] = array(
				"total" => $total,
				"mes" => nombre_mes($m),
			);
			$inicio = sumar_meses($start,1);
		}
		echo json_encode($data);
	}

}
