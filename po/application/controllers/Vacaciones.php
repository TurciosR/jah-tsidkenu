<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vacaciones extends CI_Controller
{

	public function index()
	{
		validar_session($this);
		$data = array(
			'tipo' => '0',
			'nombre_archivo' => 'Mis Solicitudes de Vacacion',
			'icono' => 'fa fa-calendar',
			'urljs' => 'funciones_vacaciones.js',
			'url_agregar' => 'Vacaciones/agregar',
			'txt_agregar' => 'Agregar Solicitud de Vacacion',
			'tabla' => array(
				'NO' => '1',
				'FECHA DE SOLICITUD' => '2',
				'INICIO' => '1',
				'FIN' => '1',
				'CANTIDAD DE DIAS' => '2',
				'ESTADO' => '4',
				'ACCIONES' => '1',
			),
		);
		$this->load->helper('template_helper');
		template('template/admin',$data);
	}

	public function get_data()
	{
		$this->load->model('Utils_model');
		$this->load->model('Vacaciones_model');
		$this->load->helper('utilities_helper');
		$id_colaborador = $this->session->id_colaborador;
		$tipo = $this->session->tipo;
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
			0 => 'v.correlativo',
			1 => 'v.fecha_solicitud',
			2 => 'v.fecha_inicio',
			3 => 'v.fecha_fin',
			5 => 'v.estado',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}

		$vacaciones = $this->Vacaciones_model->get_vacaciones($id_colaborador, $order, $search, $valid_columns, $length, $start, $dir, $tipo);
		if ($vacaciones != 0) {
			$data = array();
			foreach ($vacaciones as $rows) {
				$fecha_solicitud = nice_date($rows->fecha_solicitud, "d-m-Y");
				$fecha_inicio = nice_date($rows->fecha_inicio, "d-m-Y");
				$fecha_fin = nice_date($rows->fecha_fin, "d-m-Y");

				$dias = diferenciaDias($fecha_inicio, $fecha_fin)+1;

				$id_vacacion = $rows->id_vacacion;
				$filename = base_url() . "Vacaciones/seguimiento";
				$acciones = "<a title='Seguimiento' style='color:green; font-weight:bold; font-size:20px;' href='" . $filename . "/" . md5($id_vacacion) . "'><i class='fa fa-search'></i></a>  ";
				$filename = base_url() . "Vacaciones/formato_vacacion";
				$acciones .= "<a title='Formulario PDF' style='color:#2647f8; font-weight:bold; font-size:20px;' target='_blank' href='" . $filename . "/" . md5($id_vacacion) . "'><i class='fa fa-print'></i></a>  ";
				$estado = $rows->estado;
				if ($estado == "ESPERANDO APROBACIÓN") {
					$acciones .= "<a title='Cancelar' style='color:red; font-weight:bold; font-size:20px;' class='canceled' id=" . $id_vacacion . "><i class='fa fa-times'></i></a>  ";
				}
				$correlativo = "V".str_pad($rows->correlativo, 6, "0", STR_PAD_LEFT);

				$data[] = array(
					$correlativo,
					$fecha_solicitud,
					$fecha_inicio,
					$fecha_fin,
					$dias,
					$rows->estado,
					$acciones,
				);
			}
			$total = $this->Vacaciones_model->get_count_vacaciones($id_colaborador);
			$output = array(
				"draw" => $draw,
				"recordsTotal" => $total->num,
				"recordsFiltered" => $total->num,
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

	public function agregar()
	{
		validar_session($this);
		$this->load->model('Vacaciones_model');
		$this->load->helper('template_helper');
		$id_colaborador = $this->session->id_colaborador;
		$fecha = date("Y-m-d");
		$fecha1 = date("d-m-Y", strtotime($fecha . "+ 10 days"));
		$fecha2 = date("d-m-Y", strtotime($fecha . "+ 11 days"));

		$row = $this->Vacaciones_model->get_saldo_dias($id_colaborador);
		$saldo = $row->saldo;
		$fila = $this->Vacaciones_model->get_colaboradores($id_colaborador);

		$data = array(
			"saldo"=>$saldo,
			"colaboradores"=>$fila,
			"fecha1"=>$fecha1,
			"fecha2"=>$fecha2,
			'titulo' => "Agregar Solicitud de Vacación",
			'urljs' => 'funciones_vacaciones.js',
		);

		/*$data = array(
			'titulo' => "Agregar Solicitud de Vacación",
			'urljs' => 'funciones_vacaciones.js',
			'formulario' => array(
				'name'  => 'formulario',
				'id'    => 'formulario',
			),
			'campos' => array(
				'1' => array(
					'lenght'=>'6',
					'nombre'=>'Fecha de Inicio',
					'tipo' => 'text',
					'prop' => array(
						'type' => 'text',
						'name' => 'fecha_inicio',
						'id' => 'fecha_inicio',
						'class' => 'form-control datepicker1',
						'value'=>$fecha1,
						'readonly'=>"",
					),
				),
				'2' => array(
					'lenght'=>'6',
					'nombre'=>'Fecha de Finalización',
					'tipo' => 'text',
					'prop' => array(
						'type' => 'text',
						'name' => 'fecha_fin',
						'id' => 'fecha_fin',
						'class' => 'form-control datepicker1',
						'value'=>$fecha2,
						'readonly'=>"",
					),
				),
				'3' => array(
					'lenght'=>'6',
					'nombre'=>'Total de Dias',
					'tipo' => 'text',
					'prop' => array(
						'type' => 'text',
						'name' => 'total_dias',
						'id' => 'total_dias',
						'class' => 'form-control',
						'disabled' => '',
					),
				),
				'4' => array(
					'lenght'=>'6',
					'nombre'=>'Saldo de Dias',
					'tipo' => 'text',
					'prop' => array(
						'name' => 'saldo_dias',
						'id' => 'saldo_dias',
						'class' => 'form-control',
						'value'=>$saldo,
						'disabled'=>''
					),
				),
				'5' => array(
					'lenght'=>'12',
					'nombre'=>'Reemplazo',
					'tipo' => 'select',
					'prop' => array(
						'name' => 'reemplazo',
						'id' => 'reemplazo',
						'class' => 'form-control select',
						'valores'=>array(
						),
					),
				),
			),
			'button'=>array(
				'type'=>'button',
				'id'=>"btn_add",
				'name'=>"btn_add",
				'class'=>"btn btn-primary m-t-n-xs pull-right",
				'texto'=>'<i class="fa fa-save"></i> Guardar'
			),
			'proccess'=>array(
				'type'=>'hidden',
				'id'=>"proccess",
				'name'=>"proccess",
				'value'=>'insert'
			),
		);

		$assocData = array();
		if($fila){
			foreach ($fila as $row)
			{
				$assocData += [ $row->id_colaborador => $row->colaborador];
			}
			$data["campos"]["5"]["prop"]["valores"] = $assocData;
		}*/
		template('agregar_vacacion',$data);

	}

	public function formato_vacacion($id)
	{
		validar_session($this);
		$this->load->model('Utils_model');
		$this->load->model('Vacaciones_model');
		$this->load->model('Colaborador_model');

		$datos = $this->Vacaciones_model->formulario_pdf($id);

		$colaborador = $datos->colaborador;
		$id_colaborador = $datos->id_colaborador;
		$cargo = $datos->cargo;
		$unidad = $datos->unidad;
		$fecha_ingreso = $datos->fecha_ingreso;
		$ant = $datos->anios;
		$saldo = $datos->saldo;
		$id_vacacion = $datos->id_vacacion;

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
		$this->fpdf->SetFont('Helvetica', '', 12);
		//Ruta del logo de la organizacion
		//$path = "img/logo1.png";

		// $fecha_ingreso =  d_m_Y($fecha_ingreso);

		$path = base_url() . "assets/img/logo1.png";
		$this->fpdf->Image($path, 10, 10, 40, 40);
		$this->fpdf->Cell(180, 7, utf8_decode("ENTE OPERADOR REGIONAL"), 0, 1, "C");
		$this->fpdf->Cell(180, 7, utf8_decode("Formulario para Solicitud de Vacaciones"), 0, 1, "C");
		$this->fpdf->Ln(20);
		$this->fpdf->SetFont('Helvetica', '', 10);
		//Primera Fila
		$this->fpdf->Cell(115, 6, utf8_decode("NOMBRE COMPLETO DEL COLABORADOR"), 0, 1, "L");
		$this->fpdf->Cell(115, 6, utf8_decode(ucwords(mb_strtolower($colaborador))), 1, 0, "L");
		$this->fpdf->Cell(10, 6, "", 0, 0, "L");
		$this->fpdf->Cell(30, 6, utf8_decode("Fecha Ingreso"), 0, 0, "L");
		$this->fpdf->Cell(25, 6, d_m_Y($fecha_ingreso), 1, 1, "R");
		$this->fpdf->Ln(5);
		$this->fpdf->Cell(50, 6, utf8_decode("PUESTO"), 0, 0, "L");
		$this->fpdf->Cell(10, 6, "", 0, 0, "L");
		$this->fpdf->Cell(55, 6, utf8_decode("COORDINACIÓN / GERENCIA"), 0, 0, "L");
		$this->fpdf->Cell(10, 6, "", 0, 0, "L");
		$this->fpdf->Cell(30, 6, utf8_decode("Antiguedad"), 0, 0, "L");
		$this->fpdf->Cell(25, 6, utf8_decode($ant), 1, 1, "R");
		
		$y = $this->fpdf->GetY();
		$this->fpdf->MultiCell(50, 5, utf8_decode(ucfirst(mb_strtolower($cargo))), 0, "J", 0);
		$this->fpdf->SetY($y);
		$this->fpdf->Cell(50, 12, "", 1, 0, "C");
		$this->fpdf->Cell(10, 6, "", 0, 0, "L");
		$y = $this->fpdf->GetY();
		$this->fpdf->MultiCell(55, 5, utf8_decode(ucfirst(mb_strtolower($unidad))), 0, "J", 0);
		$this->fpdf->SetY($y);
		$this->fpdf->Cell(50, 12, "", 1, 0, "C");
		$this->fpdf->Cell(10, 6, "", 0, 0, "L");
		$this->fpdf->Cell(55, 12, "", 1, 0, "C");
		$this->fpdf->Cell(10, 6, "", 0, 0, "L");
		$this->fpdf->Ln(5);
		$this->fpdf->Cell(125, 6, "", 0, 0, "L");
		$this->fpdf->Cell(30, 6, utf8_decode("Saldo"), 0, 0, "L");
		$this->fpdf->Cell(25, 6, utf8_decode($saldo), 1, 1, "R");

		$this->fpdf->Ln(15);
		$this->fpdf->SetFillColor(50, 50, 50);
		$this->fpdf->SetTextColor(255, 255, 255);
		$this->fpdf->SetFont('Helvetica', '', 9);
		$this->fpdf->Cell(20, 5, utf8_decode("Fecha Inicio"), 1, 0, "C", 1);
		$this->fpdf->Cell(20, 5, utf8_decode("Fecha Final"), 1, 0, "C", 1);
		$this->fpdf->Cell(20, 5, utf8_decode("Total Dias"), 1, 0, "C", 1);
		$this->fpdf->Cell(20, 5, utf8_decode("Saldo Dias"), 1, 0, "C", 1);
		$this->fpdf->Cell(60, 5, utf8_decode("Comentarios"), 1, 0, "C", 1);
		$this->fpdf->Cell(40, 5, utf8_decode("Reemplazo"), 1, 1, "C", 1);
		$this->fpdf->Ln(1);
		$this->fpdf->SetFillColor(255, 255, 255);
		$this->fpdf->SetTextColor(0, 0, 0);
		$y = $this->fpdf->GetY();

		$sql1 = $this->Vacaciones_model->get_data_formulario($id_vacacion);
			foreach ($sql1 as $row) {
				$fecha_inicio = $row->fecha_inicio;
				$fecha_fin = $row->fecha_fin;
				$total_dias = $row->total_dias;
				$saldo = $row->saldo;
				$observaciones = $row->observaciones;
				$colaborador = $row->colaborador;
				$this->fpdf->Cell(20, 5, d_m_Y($fecha_inicio), 1, 0, "C", 1);
				$this->fpdf->Cell(20, 5, d_m_Y($fecha_fin), 1, 0, "C", 1);
				$this->fpdf->Cell(20, 5, utf8_decode($total_dias), 1, 0, "C", 1);
				$this->fpdf->Cell(20, 5, utf8_decode($saldo), 1, 0, "C", 1);
				$this->fpdf->Cell(60, 5, utf8_decode($observaciones), 1, 0, "C", 1);
				$this->fpdf->Cell(40, 5, substr(utf8_decode($colaborador),0,24), 1, 1, "L", 1);
			}

		$this->fpdf->Ln(25);
		$this->fpdf->SetFillColor(50, 50, 50);
		$this->fpdf->SetTextColor(255, 255, 255);
		$this->fpdf->Cell(90, 5, utf8_decode("APROBADO POR"), 1, 0, "C", 1);
		$this->fpdf->Cell(10, 5, "", 0, 0, "C", 0);
		$this->fpdf->Cell(40, 5, utf8_decode("CARGO"), 1, 0, "C", 1);
		$this->fpdf->Cell(10, 5, "", 0, 0, "C", 0);
		$this->fpdf->Cell(30, 5, utf8_decode("FECHA"), 1, 0, "C", 1);
		$this->fpdf->SetFillColor(255, 255, 255);
		$this->fpdf->SetTextColor(0, 0, 0);
		$this->fpdf->Ln(10);

		$datos_aprobado = $this->Vacaciones_model->get_aprobaciones($id);
		//echo $datos_aprobado;
		if($datos_aprobado){
			foreach ($datos_aprobado as $aprobaciones) {
				// code...
				$fecha_ap = $aprobaciones->fecha;
				$hora_ap = $aprobaciones->hora;
				$descripcion = $aprobaciones->descripcion;
				if (strpos($descripcion, 'INMEDIATO') !== false) {
					$jefe0_data = $this->Colaborador_model->get_colaborador($datos->jefe_inmediato);
					$jefe_nombre = $jefe0_data->nombre." ".$jefe0_data->apellido;
					$this->fpdf->Cell(90, 5, utf8_decode($jefe_nombre), "B", 0, "C", 0);
					$this->fpdf->Cell(10, 5, "", 0, 0, "C", 0);
					$this->fpdf->Cell(40, 5, utf8_decode("Jefe Inmediato"), "B", 0, "C", 0);
					$this->fpdf->Cell(10, 5, "", 0, 0, "C", 0);
					$this->fpdf->Cell(30, 5, d_m_Y($fecha_ap), "B", 1, "C", 0);
					$this->fpdf->Cell(150, 5, "", 0, 0, "C", 0);
					$this->fpdf->Cell(30, 5, hora_A_P($hora_ap), 0, 1, "C", 0);
				}
				if (strpos($descripcion, 'HUMANO') !== false) {
					$jefe1_data = $this->Colaborador_model->get_colaborador($datos->rrhh);
					$jefe_nombre = $jefe1_data->nombre." ".$jefe1_data->apellido;
					$this->fpdf->Cell(90, 5, utf8_decode($jefe_nombre), "B", 0, "C", 0);
					$this->fpdf->Cell(10, 5, "", 0, 0, "C", 0);
					$this->fpdf->Cell(40, 5, utf8_decode("Talento Humano"), "B", 0, "C", 0);
					$this->fpdf->Cell(10, 5, "", 0, 0, "C", 0);
					$this->fpdf->Cell(30, 5, d_m_Y($fecha_ap), "B", 1, "C", 0);
					$this->fpdf->Cell(150, 5, "", 0, 0, "C", 0);
					$this->fpdf->Cell(30, 5, hora_A_P($hora_ap), 0, 1, "C", 0);
				}
			}
		}
		else {
			$this->fpdf->Cell(180, 5, utf8_decode("EN ESPERA DE APROBACIÓN"), "B", 0, "C", 0);
		}

		ob_clean();
		$this->fpdf->Output("permiso.pdf", "I");
	}

	public function save_vacation()
	{
		$this->load->model('Vacaciones_model');
		$this->load->model('Utils_model');
		$this->load->model('Config_model');
		$this->load->model('Colaborador_model');
		$this->load->helper('Utilities_helper');
		$this->load->helper('mail_helper');
		$id_colaborador = $this->session->id_colaborador;
		$fecha_solicitud = date("Y-m-d");
		$hora_solicitud = date("H:m:s");
		$id_reemplazo = $_POST["id_reemplazo"];
		$fecha_inicio = Y_m_d($_POST["fecha_inicio"]);
		$fecha_fin = Y_m_d($_POST["fecha_fin"]);
		$row = $this->Vacaciones_model->get_saldo_dias($id_colaborador);
		$saldo = $row->saldo;
		$tot = $_POST["total"];
		$fecha1 =strtotime($fecha_inicio);
		$fecha2 =strtotime($fecha_fin);
		if($fecha1>$fecha2){
			$fecha_es_mayor=true;
		}else{
			$fecha_es_mayor=false;
		}
		if($saldo>=$tot){
			if($fecha_es_mayor==false){
				$correlativo = $this->Vacaciones_model->get_ultimo_correlativo();
				$correlativo = $correlativo->correlativo + 1;
				$table1 = "vacaciones";
				$form_data1 = array(
					"id_colaborador" => $id_colaborador,
					"id_reemplazo" => $id_reemplazo,
					"fecha_inicio" => $fecha_inicio,
					"fecha_fin" => $fecha_fin,
					"total_dias" => $tot,
					"fecha_solicitud" => $fecha_solicitud,
					"hora_solicitud" => $hora_solicitud,
					"estado" => "ESPERANDO APROBACIÓN",
					"correlativo"=>$correlativo
				);
				$this->Utils_model->begin();
				$id_vacacion = $this->Vacaciones_model->insert_vacaciones($table1, $form_data1);

				while ($tot > 0){
					$row_id_vac_col = $this->Vacaciones_model->get_id_vacaciones_colaboradores($id_colaborador);
					$id_vac_col = $row_id_vac_col->id_vacaciones_colaboradores;
					$saldo_tabla = $row_id_vac_col->saldo;
					if($saldo_tabla>=$tot){
						$nuevo_saldo = $saldo_tabla - $tot;
						$tot=0;
					}
					else{
						$nuevo_saldo  = 0;
						$tot = $tot - $saldo_tabla;
					}
					$form = array("saldo"=>$nuevo_saldo);
					$this->Vacaciones_model->actualizar_saldo($id_vac_col,$form);
				}

				$tabla_seguimiento = "vacaciones_seguimiento";
				$fecha= date("Y-m-d");
				$hora = date("H:i:s");
				$f1 = array(
					'id_vacacion' => $id_vacacion,
					'descripcion' => 'SOLICITUD CREADA',
					'observaciones' => '',
					'hora' => $hora,
					'fecha' => $fecha,
					'tipo' => 'success',
				);
				$f2 = array(
					'id_vacacion' => $id_vacacion,
					'descripcion' => 'ESPERANDO APROBACIÓN DE JEFE INMEDIATO',
					'observaciones' => '',
					'tipo' => 'info',
				);
				$f4 = array(
					'id_vacacion' => $id_vacacion,
					'descripcion' => 'ESPERANDO APROBACIÓN DE TALENTO HUMANO',
					'observaciones' => '',
					'tipo' => 'info',
				);
				$insertar_seguimiento = $this->Utils_model->_insert($tabla_seguimiento,$f1,"",0);
				$this->Utils_model->_insert($tabla_seguimiento,$f2,"",0);
				$this->Utils_model->_insert($tabla_seguimiento,$f4,"",0);

				//Send email
				$eor_data = $this->Config_model->get_data();
				$colaborador_data = $this->Colaborador_model->get_colaborador($id_colaborador);
				$from = $eor_data->correo_empresa;
				$to = $colaborador_data->correo;
				$colaborador_nombre = $colaborador_data->nombre." ".$colaborador_data->apellido;
				$colaborador_jefe0 = $colaborador_data->jefe_inmediato;
				$subject = "Seguimiento de Solicitud";
				$headers = "From: ".$from."\r\n";
				//$headers .= "CC: ".$from."\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$titulo = "¡Solicitud creada con exito!";
				$tex = "Su solicitud ha sido creada exitosamente, el proceso de aprobación se ha iniciado, puede consultar el seguimiento de su solicitud en la plataforma en la seccion Vacaciones -> Mis solicitudes";
				$fecha_format = ucfirst(strtolower(nombre_dia(date("Y-m-d")))).', '.hora_A_P(date("H:i:s"));
				$message =  msg($colaborador_nombre, $subject, $tex, $titulo, $fecha_format);
				mail($to, $subject, $message, $headers);
				$jefe0_data = $this->Colaborador_model->get_colaborador($colaborador_jefe0);

				//JEFE 1 MAIL
				if ($jefe0_data) {
					$jefe0_nombre = $jefe0_data->nombre." ".$jefe0_data->apellido;
					$to = $jefe0_data->correo;
					$subject = "¡Nueva Solicitud!";
					$headers = "From: ".$from."\r\n";
					//$headers .= "CC: ".$from."\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					$titulo = "Seguimiento de Solicitud";
					$tex = "El colaborador ".$colaborador_nombre." ha ingresado una solicitud de vacaciones, para mas detalles ingrese a la plaraforma en la seccion Vacaciones -> Solicitudes de Colaboradores.";
					$message =  msg($jefe0_nombre, $subject, $tex, $titulo, $fecha_format);
					mail($to, $subject, $message, $headers);
				}
				//End send email data

				if ($insertar_seguimiento) {
					$this->Utils_model->commit();
					$xdatos["type"] = "success";
					$xdatos["msg"] = "Solicitud ingresada";
				} else {
					$this->Utils_model->rollback();
					$xdatos["type"] = "error";
					$xdatos["msg"] = "Error al ingresar la solicitud";
				}
			}
			else{
				$xdatos['type'] = 'error';
				$xdatos['msg'] = 'Fecha de Inicio es mayor que la fecha de fin!  ERR: 000FH1';
			}
		}
		else{
			$xdatos['type'] = 'error';
			$xdatos['msg'] = 'Los dias a solicitar no pueden ser mayor al saldo disponible!  ERR: 000SAL';
		}

		$xdatos["base"] = base_url();
		echo json_encode($xdatos);
	}

	function cancelar_solicitud() {
		$id = $this->input->post("id");
		$this->load->model('Utils_model');
		$tabla = "vacaciones";
		$form_data = array(
			'estado' => 'CANCELADA',
		);
		$where = " id_vacacion ='".$id."'";
		$update = $this->Utils_model->_update($tabla,$form_data,$where);
		if($update) {
			$tablab = "vacaciones_seguimiento";
			$form_datab = array(
				'id_vacacion' => $id,
				'descripcion' => 'SOLICITUD CANCELADA POR EL COLABORADOR',
				'observaciones' => '',
				'hora' => date("H:i:s"),
				'fecha' => date("Y-m-d"),
				'tipo' => 'danger',
			);
			$insertar = $this->Utils_model->_insert($tablab,$form_datab);
			if($insertar) {
				$xdatos["typeinfo"] = "Success";
				$xdatos["msg"] = "Solicitud cancelada con exito";
			}
			else {
				$xdatos["typeinfo"] = "Error";
				$xdatos["msg"] = "Solicitud no pudo ser cancelada";
			}
		}
		else {
			$xdatos["typeinfo"] = "Error";
			$xdatos["msg"] = "Solicitud no pudo ser cancelada";
		}
		$xdatos["base"] = base_url();
		echo json_encode($xdatos);
	}

	function aprobar_solicitud()
	{
		$id = $this->input->post("id");
		$id_colaborador = $this->session->id_colaborador;
		$tipo = $this->session->tipo;
		$this->load->model('Vacaciones_model','',TRUE);
		$this->load->model('Utils_model');
		$this->load->model('Colaborador_model');
		$this->load->model('Config_model');
		$this->load->helper('utilities_helper');
		$this->load->helper('mail_helper');
		$tipo_jefe = $this->Vacaciones_model->get_tipo_jefe($id, $id_colaborador);

		$table = "vacaciones_seguimiento";
		if($tipo=="5"){
			$rows = $this->Vacaciones_model->vacacion_seguimiento($id,"HUMANO");
			$id_seguimiento = $rows->id_seguimiento_vacacion;
			$form = array(
				"fecha"=>date("Y-m-d"),
				"hora"=>date("H:i:s"),
				"descripcion"=>"APROBADA POR TALENTO HUMANO",
				"tipo"=>"success",
			);
		}else{
			if($tipo_jefe=="INMEDIATO"){
				$rows = $this->Vacaciones_model->vacacion_seguimiento($id,"INMEDIATO");
				$id_seguimiento = $rows->id_seguimiento_vacacion;
				$form = array(
					"fecha"=>date("Y-m-d"),
					"hora"=>date("H:i:s"),
					"descripcion"=>"SOLICITUD APROBADA POR EL JEFE INMEDIATO",
					"tipo"=>"success",
				);
			}
			if($tipo_jefe=="SUPERIOR"){
				$rows = $this->Vacaciones_model->vacacion_seguimiento($id,"INMEDIATO");
				$id_seguimiento = $rows->id_seguimiento_vacacion;
				$form = array(
					"fecha"=>date("Y-m-d"),
					"hora"=>date("H:i:s"),
					"descripcion"=>"SOLICITUD APROBADA POR EL JEFE INMEDIATO",
					"tipo"=>"success",
				);
			}
		}
		$this->Utils_model->begin();
		$where = " id_seguimiento_vacacion='".$id_seguimiento."'";
		$update = $this->Utils_model->_update($table,$form,$where);
		if($update){
			$tabla_vacacion = "vacaciones";
			if($tipo=="5"){
				$form_v = array(
					"rrhh"=>$id_colaborador
				);
			}else{
				if($tipo_jefe=="INMEDIATO"){
					$form_v = array(
						"jefe_inmediato"=>1
					);
				}
				else if ($tipo_jefe=="SUPERIOR"){
					$form_v = array(
						"jefe_inmediato"=>1
					);
				}
			}
			$where1 = " id_vacacion='".$id."'";
			$update1 = $this->Utils_model->_update($tabla_vacacion,$form_v,$where1);
			if($update1){

				$row = $this->Vacaciones_model->vacacion_detalle($id);
				$jefei = $row->jefe_inmediato;
				$jefes = $row->jefe_superior;
				$rrhh = $row->rrhh;

				if($jefei==1 && $jefes==1){
					$form_v2 = array(
						"estado"=>"APROBADA"
					);
					$this->Utils_model->_update($tabla_vacacion,$form_v2,$where1);
					$rows = $this->Vacaciones_model->vacacion_seguimiento($id,"HUMANO");
					$id_seguimiento = $rows->id_seguimiento_vacacion;
					$form = array(
						"fecha"=>date("Y-m-d"),
						"hora"=>date("H:i:s"),
						"descripcion"=>"SOLICITUD APROBADA",
						"tipo"=>"success",
					);
					$where = " id_seguimiento_vacacion= '".$id_seguimiento."'";
					$this->Utils_model->_update($table,$form,$where);
				}
				else if($rrhh!=0){
					$form_v2 = array(
						"estado"=>"APROBADA"
					);
					$this->Utils_model->_update($tabla_vacacion,$form_v2,$where1);
					$rows = $this->Vacaciones_model->vacacion_seguimiento($id,"HUMANO");
					$id_seguimiento = $rows->id_seguimiento_vacacion;
					$form = array(
						"fecha"=>date("Y-m-d"),
						"hora"=>date("H:i:s"),
						"descripcion"=>"SOLICITUD APROBADA",
						"tipo"=>"success",
					);
					$where = " id_seguimiento_vacacion= '".$id_seguimiento."'";
					$this->Utils_model->_update($table,$form,$where);
				}


				//Inicia envio de email
				$permiso_data = $this->Vacaciones_model->vacacion_detalle($id);
				$eor_data = $this->Config_model->get_data();
				$id_colaborador_permiso = $permiso_data->id_colaborador;
				$colaborador_data = $this->Colaborador_model->get_colaborador($id_colaborador_permiso);
				$from = $eor_data->correo_empresa;
				$to = $colaborador_data->correo;
				$colaborador_nombre = $colaborador_data->nombre." ".$colaborador_data->apellido;
				$subject = "Seguimiento de Solicitud";
				$headers = "From: ".$from."\r\n";
				//$headers .= "CC: ".$from."\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$titulo = "¡Solicitud Aprobada!";
				$tex = "Su solicitud ha sido aprobada por su jefe ".mb_strtolower($tipo_jefe).", puede consultar el seguimiento de su solicitud en la plataforma en la seccion Permisos -> Mis solicitudes";
				$fecha_format = ucfirst(strtolower(nombre_dia(date("Y-m-d")))).', '.hora_A_P(date("H:i:s"));
				$message =  msg($colaborador_nombre, $subject, $tex, $titulo, $fecha_format);
				$send = mail($to, $subject, $message, $headers);
				//Termina envio de email
				if($send){
					$this->Utils_model->commit();
					$xdatos["typeinfo"] = "Success";
					$xdatos["msg"] = "Solicitud aprobada con exito";
				}else{
					$this->Utils_model->rollback();
					$xdatos["typeinfo"] = "Error";
					$xdatos["msg"] = "Solicitud no pudo ser aprobada";
				}
			}
			else{
				$this->Utils_model->rollback();
				$xdatos["typeinfo"] = "Error";
				$xdatos["msg"] = "Solicitud no pudo ser aprobada";
			}

		}
		else{
			$this->Utils_model->rollback();
			$xdatos["typeinfo"] = "Error";
			$xdatos["msg"] = "Solicitud no pudo ser aprobada";
		}
		$xdatos["base"] = base_url();
		echo json_encode($xdatos);
	}

	function denegar_solicitud() {
		$id = $this->input->post("id");
		$sms = $this->input->post("sms");
		$id_colaborador = $this->session->id_colaborador;
		$this->load->model('Vacaciones_model');
		$this->load->model('Utils_model');
		$this->load->model('Colaborador_model');
		$this->load->model('Config_model');
		$this->load->helper('utilities_helper');
		$this->load->helper('mail_helper');

		//$tipo_jefe = $this->Vacaciones_model->get_tipo_jefe($id, $id_colaborador);
		$tipo_jefe="INMEDIATO";
		$tabla = "vacaciones";
		$form_data = array(
			'estado' => 'DENEGADA',
		);
		$where = "WHERE id_vacacion='".$id."'";
		$update = $this->Utils_model->_update($tabla,$form_data,$where);
		if($update) {
			$permiso_data = $this->Vacaciones_model->vacacion_detalle($id);
			$eor_data = $this->Config_model->get_data();
			$id_colaborador_permiso = $permiso_data->id_colaborador;
			$colaborador_data = $this->Colaborador_model->get_colaborador($id_colaborador_permiso);
			$from = $eor_data->correo_empresa;
			$to = $colaborador_data->correo;
			$colaborador_nombre = $colaborador_data->nombre." ".$colaborador_data->apellido;
			$subject = "Seguimiento de Solicitud";
			$headers = "From: ".$from."\r\n";
			//$headers .= "CC: ".$from."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$titulo = "¡Solicitud denegada!";
			$tex = "Su solicitud ha sido denegada por su jefe ".mb_strtolower($tipo_jefe).", puede consultar el seguimiento de su solicitud en la plataforma en la seccion Permisos -> Mis solicitudes";
			$fecha_format = ucfirst(strtolower(nombre_dia(date("Y-m-d")))).', '.hora_A_P(date("H:i:s"));
			$message =  msg($colaborador_nombre, $subject, $tex, $titulo, $fecha_format);
			mail($to, $subject, $message, $headers);

			$tablab = "vacaciones_seguimiento";
			$form_datab = array(
				'id_vacacion' => $id,
				'descripcion' => 'SOLICITUD DENEGADA POR EL JEFE '.$tipo_jefe,
				'observaciones' => $sms,
				'hora' => date("H:i:s"),
				'fecha' => date("Y-m-d"),
				'tipo' => 'danger',
			);
			$insertar = $this->Utils_model->_insert($tablab,$form_datab);
			if($insertar) {
				$xdatos["typeinfo"] = "Success";
				$xdatos["msg"] = "Solicitud denegada con exito";
			}
			else {
				$xdatos["typeinfo"] = "Error";
				$xdatos["msg"] = "Solicitud no pudo ser denegada";
			}
		}
		else {
			$xdatos["typeinfo"] = "Error";
			$xdatos["msg"] = "Solicitud no pudo ser denegada";
		}
		$xdatos["base"] = base_url();
		echo json_encode($xdatos);
	}

	function seguimiento($id){
		validar_session($this);
		$this->load->helper('template_helper');
		$this->load->model('Vacaciones_model');
		$row = $this->Vacaciones_model->get_seguimiento($id);
		$data = array(
			'titulo' => 'Seguimiento',
			'urljs' => 'funciones_vacaciones.js',
			'data' => $row,
		);

		template('seguimiento',$data);
	}

}
