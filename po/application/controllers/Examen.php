<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examen extends CI_Controller {

	public function index(){
		validar_session($this);
		$data = array(
			'tipo' => 5,
			'nombre_archivo' => 'Administrar Examenes',
			'icono' => 'fa fa-user-circle',
			'urljs' => 'funciones_examen.js',
			'url_agregar' => 'Examen/agregar_examen',
			'txt_agregar' => 'Nuevo Examen',
			'tabla'=> array(
				'ID' => 1,
				'NOMBRE' => 4,
				'EDAD' => 1,
				'OPTOMETRISTA'=>3,
				'FECHA'=>2,
				'ACCIONES'=>1,
			),
		);
		$this->load->helper('template_helper');
		template('template/admin',$data);
	}

	public function get_data()
	{
		$this->load->model('Examen_model');

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
			0 => 'examenes.id',
			1 => 'examenes.fecha',
			2 => 'cliente.nombre',

		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->session->id_sucursal;
		$examenes = $this->Examen_model->get_examenes($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);
		if ($examenes != 0) {
			$data = array();
			$num = 1;
			foreach ($examenes as $rows) {

				// $menudrop = "<div class='btn-group'>
				// <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
				// <ul class='dropdown-menu dropdown-primary'>";
				//
				// $filename = base_url() . "Examen/editar_examen";
				// $menudrop .= "<li><a href='" . $filename . "/" .$rows->id_cliente. "' role='button'  data-refresh='true'><i class='fa fa-pencil' ></i> Expediente</a></li>";
				//
				// $menudrop .= "</ul></div>";
				$filename = base_url() . "Examen/editar_examen";
				$menudrop = "<a class='btn btn-primary' href='" . $filename . "/" .$rows->id_cliente. "' role='button'  data-refresh='true'><i class='fa fa-pencil' ></i> Expediente</a>";

				$fechaNac = new DateTime($rows->fecha_nacimiento);
				$fechaHoy = new DateTime(date("Y-m-d"));
				$diff = $fechaHoy->diff($fechaNac);
				$edadN = $diff->format("%y");

				$data[] = array(
					//$num,
					$rows->id,
					$rows->nombre,
					$rows->edad,
					$rows->optometrista,
					$rows->fecha,
					$menudrop,
				);

				$num++;
			}
			$total = $this->Examen_model->count_examenes($id_sucursal);
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

	function  agregar_examen()
	{
		$this->load->helper('template_helper');
		template('Examenes/agregar_examen');
	}

	function fetch($query)
	{
		$this->load->model('Examen_model');
		$id_sucursal=$this->session->id_sucursal;

		echo $this->Examen_model->traer_cliente($query,$id_sucursal);
	}

	function  cargar_examen($id_cliente=-1) {
		$this->load->helper('template_helper');
		$this->load->model('Examen_model');
		//$id_cliente = $this->input->get("id_cliente");
		//echo $id_cliente;
		$cliente = $this->Examen_model->get($id_cliente);
		$id_cliente1=$cliente->id;
		$nombre=$cliente->nombre;
		$edad = $cliente->edad;
		$sexo = $cliente->sexo;

		$data = array(
			'id_cliente' => $id_cliente1,
			'nombre' => $nombre,
			'edad' => $edad,
			'sexo' => $sexo,
		);
		template('Examenes/generar_examen',$data);
	}
	function  editar_examen($id=-1) {
		$this->load->helper('template_helper');
		$this->load->model('Examen_model');
		$data1 = $this->Examen_model->get_cliente($id);

		$this->db->select("*");
		$query = $this->db->get("sucursal");
		$suc = $query->result();
		$id_cliente=$data1->id;
		$nombre=$data1->nombre;
		$edad = $data1->edad;
		$sexo = $data1->sexo;
		$data2 = $this->Examen_model->get_datos($id);

		//procedemos a determinar la edad a partir de la fecha de nacimiento
		//$fecha = $this->input->post("fecha");
		$fechaNac = new DateTime($data1->fecha_nacimiento);
		$fechaHoy = new DateTime(date("Y-m-d"));
		$diff = $fechaHoy->diff($fechaNac);
		$edadN = $diff->format("%y");

		$datos = array(
			"id_cliente"=>$id_cliente,
			"nombre"=>$nombre,
			"edad"=>$edad,
			"sexo"=>$sexo,
			"datos_consulta"=>$data2,
			"sucursal" => $suc,
		);
		template('Examenes/editar_examen',$datos);
	}

	function generar_examen()
	{
		$this->load->model('Utils_model');
		//$this->load->helper('template_helper');
		$this->load->model('Examen_model');
		date_default_timezone_set('America/El_Salvador');
		$fecha_registro=date("Y-m-d");
		$nombre = trim($this->input->post("nombre"));
		$edad = $this->input->post("edad");
		$sexo = $this->input->post("sexo");
		$id_cliente = $this->input->post("id_cliente");
		$id_sucursal=$this->session->id_sucursal;

		$valor=	array(
			"nombre" => $nombre,
			"edad" => $edad,
			"sexo" => $sexo,
		);
		//$total = 1;//$this->Examen_model->existe_cliente($valor);
		//echo json_encode($valor);
		$total = $this->Examen_model->existe_cliente($valor);
		if($total)
		{
			$id_cliexi=$total->id;
		}
		else
		{
			$id_cliexi=0;
		}

		$tabla = "cliente";
		$form_data = array(
			"nombre" => $nombre,
			"edad" => $edad,
			"sexo" => $sexo,
			"fecha_registro" => $fecha_registro,
			"sucursal" => $id_sucursal,
		);

		if ($id_cliente=="") {
			$insert = $this->Examen_model->save($tabla, $form_data);
			if ($insert == 1)
			{
				$xdatos['typeinfo']='Success';
				$xdatos['id_cliente']= md5($this->Examen_model->_insert_id());
			}
		}else {
			//procedemos a actualizar la edad del cliente
		    if ($id_cliente!=""){
					//echo $id_cliente."#";
						$actualizar = $this->Utils_model->_update("cliente", array("edad"=>$edad), " id=".$id_cliente);

                $xdatos['typeinfo']='Success';
                $xdatos['id_cliente']= md5($id_cliente);
            }else{
							$actualizar = $this->Utils_model->_update("cliente", array("edad"=>$edad), " id=".$id_cliexi);
                $xdatos['typeinfo']='Success';
                $xdatos['id_cliente']= md5($id_cliexi);
               // $xdatos['mgs']= "El cliente ya existe!";
            }

		}
		$xdatos['url'] = base_url("Examen");
		echo json_encode($xdatos);

	}

	function guardar_examen()
    {
        $this->load->model('Examen_model');
        $this->load->model('Utils_model');
        $id_cliente = $this->input->post("id_cliente");
				$edad = $this->input->post("edad");
        $fecha_registro = date("Y-m-d");
        $esfd = $this->input->post("esfd");
        $esfi = $this->input->post("esfi");
        $cild = $this->input->post("cild");
        $cili = $this->input->post("cili");
        $ejed = $this->input->post("ejed");
        $ejei = $this->input->post("ejei");
        $adid = $this->input->post("adid");
        $adii = $this->input->post("adii");
        $di = $this->input->post("di");
        $ad = $this->input->post("ad");
        $color_lente = $this->input->post("color_lente");
        $bif = $this->input->post("bif");
        $aro = $this->input->post("aro");
        $tamanio = $this->input->post("tamanio");
        $color_aro = $this->input->post("color_aro");
        $observaciones = $this->input->post("observaciones");
        $id = $this->input->post("id_examen");
        $sucursal = $this->input->post("sucursal");
        $id_sucursal = $this->session->id_sucursal;
        $id_optometrista = $this->session->id_usuario;
        $table = "examenes";
        if ($id != 0) {
					if ($sucursal==""||$sucursal=="0") {
						// code...
						$sucursal = $id_sucursal;
					}
            $form_data = array(
                "id_cliente" => $id_cliente,
                "esfd" => $esfd,
                "cild" => $cild,
                "ejed" => $ejed,
                "adid" => $adid,
                "esfi" => $esfi,
                "cili" => $cili,
                "ejei" => $ejei,
                "adii" => $adii,
                "di" => $di,
                "ad" => $ad,
                "color_lente" => $color_lente,
                "bif" => $bif,
                "aro" => $aro,
                "tamanio" => $tamanio,
                "color_aro" => $color_aro,
                "observaciones" => $observaciones,
                "id_sucursal" => $sucursal
            );
            $id = MD5($id);
            $where = "md5(id)='$id'";
            $insertar = $this->Utils_model->_update($table, $form_data, $where);

						//actualizar edad del paciente
						$fechaHoy = new DateTime(date("Y-m-d"));
						$fecha_nacimiento = date("Y-m-d",strtotime(date("Y-m-d")."- ".$edad." year"));
						$actualizar = $this->Utils_model->_update("cliente", array("edad"=>$edad, "fecha_nacimiento"=>$fecha_nacimiento), " id=".$id_cliente);
        } else {
            $form_data = array(
                "id_cliente" => $id_cliente,
                "id_optometrista" => $id_optometrista,
                "esfd" => $esfd,
                "cild" => $cild,
                "ejed" => $ejed,
                "adid" => $adid,
                "esfi" => $esfi,
                "cili" => $cili,
                "ejei" => $ejei,
                "adii" => $adii,
                "di" => $di,
                "ad" => $ad,
                "color_lente" => $color_lente,
                "bif" => $bif,
                "aro" => $aro,
                "fecha" => $fecha_registro,
                "tamanio" => $tamanio,
                "color_aro" => $color_aro,
                "observaciones" => $observaciones,
                "id_sucursal" => $sucursal
            );
            $insertar = $this->Utils_model->_insert($table, $form_data);
						$id_ex = $this->Utils_model->insert_id();
						//actualizar edad del paciente
						$fechaHoy = new DateTime(date("Y-m-d"));
						$fecha_nacimiento = date("Y-m-d",strtotime(date("Y-m-d")."- ".$edad." year"));
						$actualizar = $this->Utils_model->_update("cliente", array("edad"=>$edad, "fecha_nacimiento"=>$fecha_nacimiento), " id=".$id_cliente);
        }
        if ($insertar) {
            $xdatos['typeinfo'] = 'Success';
            //$xdatos['id']=$id_ex;
            $xdatos['msg'] = "Datos guardados con exito!!!";
        } else {
            $xdatos['typeinfo'] = 'Error';
            $xdatos['msg'] = "Datos no pudieron ser guardados!!!";
        }

        $xdatos['url'] = base_url("Examen");
        $xdatos['pdf'] = base_url("Examen") . "/imprimir_examen/" . md5($id_ex);
        $xdatos['md5'] = md5($id_ex);
        $xdatos['s'] = $sucursal;
        $xdatos['o'] = $id_optometrista;
		echo json_encode($xdatos);
	}

	public function imprimir_examen($id)
    {
        $this->load->helper('template_helper');
        $this->load->model('Utils_model');
        $this->load->model('Examen_model');
        $data = $this->Examen_model->get_datos_exa($id);
        $id_cliente = $data->id_cliente;
        $nombre = $data->nombre;
        $edad = $data->edad;
        $sexo = $data->sexo;
        $esfd = $data->esfd;
        $cild = $data->cild;
        $ejed = $data->ejed;
        $adid = $data->adid;
        $esfi = $data->esfi;
        $cili = $data->cili;
        $ejei = $data->ejei;
        $adii = $data->adii;
        $di = $data->di;
        $ad = $data->ad;
        $color_lente = $data->color_lente;
        $bif = $data->bif;
        $aro = $data->aro;
        $tamanio = $data->tamanio;
        $color_aro = $data->color_aro;
        $id_ref = $data->id;
        $observaciones = $data->observaciones;
        $sucursal = $data->nombre_sucursal;
        $telefono = $data->telefono;
        $direccion = $data->direccion;
        $optometrista = $data->optometrista;

        $fee = $data->fecha;

        if ($fee=="" || $fee =="0000-00-00") {
          // code...
          $fee=Date("d/m/y");
        }
        else {
          $fee = d_m_Y($fee);
        }


        $this->load->add_package_path(APPPATH . 'third_party/fpdf');
        $this->load->library('pdf1');
        $this->fpdf = new Pdf1();
        //$this->fpdf->size(array(100,50));
        $this->fpdf->SetTopMargin(-18);
        $this->fpdf->SetLeftMargin(15);
        //Numeracion de paginas
        //$this->fpdf->AliasNbPages();
        //Salto automatico de pagina margen de 20 mm
        $this->fpdf->SetAutoPageBreak(true, 2);
        //Agrega la pagina a trabajar
        $this->fpdf->AddPage();
        //Seteo de fuente Times New Roman 12
        $this->fpdf->SetFont('Helvetica', '', 12);
        //$path = base_url() . "assets/img/logo1.png";
        //$this->fpdf->Image($path, 10, 10, 40, 40);
        $this->fpdf->Cell(180, 7, utf8_decode($sucursal), 0, 1, "C");
        $this->fpdf->SetFont('Helvetica', '', 8);
        $this->fpdf->Cell(180, 1, utf8_decode("TODO EN SALUD VISUAL"), 0, 1, "C");
        $this->fpdf->Ln(1);
        $this->fpdf->SetFont('Helvetica', '', 10);
        //Primera Fila
        $this->fpdf->Cell(155, 6, utf8_decode($direccion), 0, 0, "L");
        $this->fpdf->SetFont('Helvetica', 'B', 7.5);
        $this->fpdf->Cell(12, 6, utf8_decode("TEL: "), 0, 0, "L");
        $this->fpdf->SetFont('Helvetica', '', 10);;
        $this->fpdf->Cell(13, 6, utf8_decode($telefono), 0, 1, "R");

        $this->fpdf->SetFont('Helvetica', 'B', 9);
        $x = $this->fpdf->GetX();
        $this->fpdf->Cell(25, 8, utf8_decode("REFERENCIA: "), 0, 0, "L");
        $this->fpdf->SetFont('Helvetica', '', 10);
        $this->fpdf->SetX($x);
        $this->fpdf->Cell(45, 8, utf8_decode(strtoupper(mb_strtolower($id_ref))), 1, 0, "R");
        $this->fpdf->SetFont('Helvetica', 'B', 9);
        $x = $this->fpdf->GetX();
        $this->fpdf->Cell(25, 8, utf8_decode("ID CLIENTE: "), 0, 0, "L");
        $this->fpdf->SetX($x);
        $this->fpdf->SetFont('Helvetica', '', 10);
        $this->fpdf->Cell(45, 8, utf8_decode(strtoupper(mb_strtolower($id_cliente))), 1, 0, "R");
        $this->fpdf->SetFont('Helvetica', 'B', 9);
        $x = $this->fpdf->GetX();
        $this->fpdf->Cell(25, 8, utf8_decode("SEXO: "), 0, 0, "L");
        $this->fpdf->SetFont('Helvetica', '', 10);
        $this->fpdf->SetX($x);
        $this->fpdf->Cell(45, 8, utf8_decode(strtoupper(mb_strtolower($sexo))), 1, 0, "R");
        $this->fpdf->SetFont('Helvetica', 'B', 9);
        $x = $this->fpdf->GetX();
        $this->fpdf->Cell(25, 8, utf8_decode("EDAD: "), 0, 0, "L");
        $this->fpdf->SetX($x);
        $this->fpdf->SetFont('Helvetica', '', 10);
        $this->fpdf->Cell(45, 8, utf8_decode(mb_strtoupper(mb_strtolower($edad . " AÑOS "))), 1, 0, "R");
        //SEGUNDA LINEA
        $this->fpdf->Ln(8);
        $this->fpdf->SetFont('Helvetica', 'B', 9);
        $x = $this->fpdf->GetX();
        $this->fpdf->Cell(25, 8, utf8_decode("NOMBRE: "), 0, 0, "L");
        $this->fpdf->SetFont('Helvetica', '', 10);
        $this->fpdf->SetX($x);
        $this->fpdf->Cell(135, 8, utf8_decode(mb_strtoupper(mb_strtolower($nombre))), 1, 0, "R");
        $x = $this->fpdf->GetX();
        $this->fpdf->SetFont('Helvetica', 'B', 9);
        $this->fpdf->Cell(25, 8, utf8_decode("FECHA: "), 0, 0, "L");
        $this->fpdf->SetX($x);
        $this->fpdf->SetFont('Helvetica', '', 10);
        $this->fpdf->Cell(45, 8, utf8_decode(strtoupper(mb_strtolower($fee))), 1, 0, "R");
        $this->fpdf->Ln(8);
        //$this->fpdf->SetTextColor(0, 0, 0);

        $this->fpdf->Cell(32, 8, utf8_decode(" "), 1, 0, "L");
        $this->fpdf->SetFont('Helvetica', 'B', 10);
        $this->fpdf->Cell(37, 8, utf8_decode("ESF "), 1, 0, "C");
        $this->fpdf->Cell(37, 8, utf8_decode("CIL "), 1, 0, "C");
        $this->fpdf->Cell(37, 8, utf8_decode("EJE "), 1, 0, "C");
		$this->fpdf->Cell(37, 8, utf8_decode("ADICIÓN "), 1, 0, "C");
		$this->fpdf->Ln(8);
		$this->fpdf->SetFont('Helvetica', 'B', 10);
		$this->fpdf->Cell(32, 8, utf8_decode("O.D "), 1, 0, "C");
		$this->fpdf->SetFont('Helvetica', '', 15);
		$this->fpdf->Cell(37, 8, utf8_decode(strtoupper (mb_strtolower($esfd))), 1, 0, "C");
		$this->fpdf->Cell(37, 8, utf8_decode(strtoupper (mb_strtolower($cild))), 1, 0, "C");
		$this->fpdf->Cell(37, 8, utf8_decode(strtoupper (mb_strtolower($ejed))), 1, 0, "C");
		$this->fpdf->Cell(37, 8, utf8_decode(strtoupper (mb_strtolower($adid))), 1, 0, "C");
		$this->fpdf->Ln(8);
		//$this->fpdf->SetTextColor(0, 0, 0);
		$this->fpdf->SetFont('Helvetica', 'B', 10);
		$x = $this->fpdf->GetX();
		$this->fpdf->Cell(32, 8, utf8_decode("O.I "), 1, 0, "C");
		$this->fpdf->SetFont('Helvetica', '', 15);
		$this->fpdf->Cell(37, 8, utf8_decode(strtoupper (mb_strtolower($esfi))), 1, 0, "C");
		$this->fpdf->Cell(37, 8, utf8_decode(strtoupper (mb_strtolower($cili))), 1, 0, "C");
		$this->fpdf->Cell(37, 8, utf8_decode(strtoupper (mb_strtolower($ejei))), 1, 0, "C");
		$this->fpdf->Cell(37, 8, utf8_decode(strtoupper (mb_strtolower($adii))), 1, 0, "C");

		//SEPTIMA LINEA
		$this->fpdf->Ln(8);
		//$this->fpdf->SetTextColor(0, 0, 0);
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$x = $this->fpdf->GetX();
		$this->fpdf->Cell(25, 8, utf8_decode("D.I: "), 0, 0, "L");
		$this->fpdf->SetFont('Helvetica', '', 15);
		$this->fpdf->SetX($x);
		$this->fpdf->Cell(32, 8, utf8_decode(strtoupper (mb_strtolower(intval($di)))), 0, 0, "R");
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$x = $this->fpdf->GetX();
		$this->fpdf->Cell(25, 8, utf8_decode("A.D: "), 0, 0, "L");
		$this->fpdf->SetX($x);
		$this->fpdf->SetFont('Helvetica', '', 8);
		$this->fpdf->Cell(28, 8, utf8_decode(strtoupper (mb_strtolower(""))), 0, 0, "R");
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$x = $this->fpdf->GetX();
		$ay = $this->fpdf->GetY();
		$this->fpdf->Cell(25, 8, utf8_decode("MATERIAL: "), "", 0, "L");
		$this->fpdf->SetFont('Helvetica', '', 8);
		$this->fpdf->MultiCell(35, 8, utf8_decode(strtoupper (mb_strtolower($color_lente))), "R", "L",0);
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$yb= $this->fpdf->GetY();
		$this->fpdf->SetY($ay);
		$this->fpdf->SetX($x+60);
		$this->fpdf->Cell(25, 8, utf8_decode("TIPO LENTE: "), 0, 0, "L");
		$this->fpdf->SetX($x+60);
		$this->fpdf->SetFont('Helvetica', '', 8);
		$this->fpdf->Cell(60, 8, utf8_decode(strtoupper (mb_strtolower($bif))), 0, 0, "R");
		//OCTAVO LINEA
		$this->fpdf->Ln(8);
		//$this->fpdf->SetTextColor(0, 0, 0);

		$this->fpdf->line(15,$ay,15,$yb);
		$this->fpdf->line(47,$ay,47,$yb);
		$this->fpdf->line(75,$ay,75,$yb);
		$this->fpdf->line(195,$ay,195,$yb);
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$x = $this->fpdf->GetX();
		$this->fpdf->SetY($yb);
		$this->fpdf->Cell(25, 8, utf8_decode("ARO: "), 0, 0, "L");
		$this->fpdf->SetFont('Helvetica', '', 8);
		$this->fpdf->SetX($x);
		$this->fpdf->Cell(90, 8, utf8_decode(strtoupper (mb_strtolower($aro))), 1, 0, "R");
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$x = $this->fpdf->GetX();
		$this->fpdf->Cell(25, 8, utf8_decode("TAMAÑO: "), 0, 0, "L");
		$this->fpdf->SetFont('Helvetica', '', 8);
		$this->fpdf->SetX($x);
		$this->fpdf->Cell(45, 8, utf8_decode(strtoupper (mb_strtolower($tamanio))), 1, 0, "R");
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$x = $this->fpdf->GetX();
		$this->fpdf->Cell(25, 8, utf8_decode("COLOR ARO: "), 0, 0, "L");
		$this->fpdf->SetX($x);
		$this->fpdf->SetFont('Helvetica', '', 8);
		$this->fpdf->Cell(45, 8, utf8_decode(strtoupper (mb_strtolower($color_aro))), 1, 0, "R");
		//NOVENA LINEA
		$this->fpdf->Ln(8);
		//$this->fpdf->SetTextColor(0, 0, 0);
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$x = $this->fpdf->GetX();
		$this->fpdf->Cell(25, 8, utf8_decode("OBSERVACIONES: "), 0, 0, "L");
		$this->fpdf->SetFont('Helvetica', '', 8);
		$this->fpdf->SetX($x);
		$this->fpdf->MultiCell(180, 7 , utf8_decode(strtoupper (mb_strtolower("\n".$observaciones))), 1,"J",0);
		$this->fpdf->SetFont('Helvetica', 'B', 9);
		$this->fpdf->Cell(25, 8, utf8_decode("OPTOMETRISTA :  "), 0, 0, "L");
		$x = $this->fpdf->GetX()+3;
		$this->fpdf->SetX($x);
		$this->fpdf->SetFont('Helvetica', '', 10);
		$this->fpdf->Cell(180, 8, utf8_decode(mb_strtoupper (mb_strtolower($optometrista))), 0, 0, "L");

		ob_clean();
		$this->fpdf->Output("examen.pdf", "I");
	}

}



/* End of file Usuarios.php */
