<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Controller {

	public function index(){
		validar_session($this);
		$data = array(
			'tipo' => 5,
			'nombre_archivo' => 'Administrar Clientes',
			'icono' => 'fa fa-user-circle',
			'urljs' => 'funciones_cliente.js',
			'url_agregar' => 'Cliente/agregar_cliente',
			'txt_agregar' => 'Agregar Cliente',
			'tabla'=> array(
				'#' => 1,
				'NOMBRE DE CLIENTE' => 3,
				'EDAD' => 1,
				'DUI'=>2,
				'FECHA DE INGRESO'=>2,
				'ACCIONES'=>1,
			),
		);
		$this->load->helper('template_helper');
		template('template/admin',$data);
	}

	public function get_data()
	{
		$this->load->model('Cliente_model');

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
			0 => 'nombre',
			1 => 'edad',
			2 => 'dui',
			3 => 'fecha_registro',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->session->id_sucursal;
		$cliente = $this->Cliente_model->get_cliente($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);

		if ($cliente != 0) {
			$data = array();
			$num = 1;
			foreach ($cliente as $rows) {

				$menudrop = "<div class='btn-group'>
				<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
				<ul class='dropdown-menu dropdown-primary'>";

				$filename = base_url() . "Cliente/editar_cliente_vista";
				$menudrop .= "<li><a href='" . $filename . "/" .$rows->id. "' role='button'  data-refresh='true'><i class='fa fa-pencil' ></i> Editar</a></li>";
				$filename = base_url() . "Examen/cargar_examen";
				$menudrop .= "<li><a href='" . $filename . "/" .$rows->id. "' role='button'  data-refresh='true'><i class='fa fa-clone' ></i> Examen</a></li>";
				$filename = base_url() . "Cliente/editar_examen";
				$menudrop .= "<li><a href='" . $filename . "/" .$rows->id. "' role='button'  data-refresh='true'><i class='fa fa-book' ></i> Expediente</a></li>";

				$menudrop .= "</ul></div>";
				//procedemos a determinar la edad a partir de la fecha de nacimiento
				$fecha = $this->input->post("fecha");
				$fechaNac = new DateTime($rows->fecha_nacimiento);
				$fechaHoy = new DateTime(date("Y-m-d"));
				$diff = $fechaHoy->diff($fechaNac);
				$edadN = $diff->format("%y");
				//echo $edadN."##";
				$data[] = array(
					$num,
					$rows->nombre,
					$rows->edad,
					$rows->dui,
					$rows->fecha_registro,
					$menudrop,
				);

				$num++;
			}
			$total = $this->Cliente_model->total_cliente($id_sucursal);
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
				"draw" => $draw,
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => $data
			);
		}
		echo json_encode($output);
		exit();
	}

	function  agregar_cliente() {
		$this->load->helper('template_helper');
		$this->load->model('Cliente_model');
		$departamento = $this->Cliente_model->get_departamento();
		$data = array(
			'departamento' => $departamento,
		);
		template('cliente/agregar_cliente',$data);

	}
	function insertar_cliente()
	{

		$this->load->model('Cliente_model');
		date_default_timezone_set('America/El_Salvador');
	  $fecha_registro=date("Y-m-d");
		$nombre = $this->input->post("nombre");
		$edad = $this->input->post("vistaEdad");
		$fecha_nacimiento = $this->input->post("edad");
		$sexo = $this->input->post("sexo");
		$dui = $this->input->post("dui");
		$nit = $this->input->post("nit");
		$nrc = $this->input->post("nrc");
		$direccion = $this->input->post("direccion");
		$departamento = $this->input->post("departamento");
		$municipio = $this->input->post("municipio");
		$telefono = $this->input->post("telefono");
		$id_sucursal=$this->session->id_sucursal;

		$valor=	array(
			"nombre" => $nombre,
			"edad" => $edad,
			"sexo" => $sexo,

		);
		$total = $this->Cliente_model->existe_cliente($valor);

		$tabla = "cliente";
		$form_data = array(
			"nombre" => $nombre,
			"edad" => $edad,
			"fecha_nacimiento" => Y_m_d($fecha_nacimiento),
			"sexo" => $sexo,
			"dui" => $dui,
			"nit" => $nit,
			"nrc" => $nrc,
			"direccion" => $direccion,
			"departamento" => $departamento,
			"municipio" => $municipio,
			"telefonos" => $telefono,
			"fecha_registro" => $fecha_registro,
			"sucursal" => $id_sucursal,

		);

		if ($total==0) {
			$insert = $this->Cliente_model->save($tabla, $form_data);
			if ($insert == 1)
			{

				$xdatos['typeinfo']='Success';
				$xdatos['msg']='Cliente ingresado correctamente!';
				$xdatos['process']='insert';
			}
			else
			{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Cliente no pudo ser ingresada!';
			}


		}else {
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Este cliente ya fue ingresado!';
		}


		$xdatos['url'] = base_url("Cliente");

		echo json_encode($xdatos);

	}


	function municipio()
	{
		$this->load->helper('template_helper');
		$this->load->model('Cliente_model');
		$id_departamento = $this->input->post("id_departamento");
		$option = "";
		$municipio = $this->Cliente_model->get_municipio($id_departamento);
		$option .= "<option value=''>Seleccione</option>";
		foreach ($municipio as $row) {
			$option .= "<option value='".$row->id_municipio."'>".$row->nombre_municipio."</option>";
		}

		echo $option;
	}
    function guardar_examen()
    {
        $this->load->model('Cliente_model');
        $this->load->model('Utils_model');
        $id_cliente = $this->input->post("id_cliente");
        $fecha_registro = Y_m_d($this->input->post("fecha"));
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
        if ($aro=="Seleccione"){
            $aro="";
        }
        $id_aro = $this->input->post("id_aro");
        $tamanio = $this->input->post("tamanio");
        $color_aro = $this->input->post("color_aro");
        $observaciones = $this->input->post("observaciones");
        $id = $this->input->post("id_examen");
        $sucursal = $this->input->post("sucursal");
        $nombre_cli = $this->input->post("nombre_cli");
        $id_sucursal = $this->session->id_sucursal;
        $id_optometrista = $this->session->id_usuario;
        $table = "examenes";
        if ($id != 0) {
            $form_data = array(
                "id_cliente" => $id_cliente,
                "id_sucursal" => $sucursal,
                "esfd" => $esfd,
                "cild" => $cild,
                "ejed" => $ejed,
                "adid" => $adid,
                "esfi" => $esfi,
                "cili" => $cili,
                "ejei" => $ejei,
                "adii" => $adii,
                "di" => $di,
                "fecha" => $fecha_registro,
                "ad" => $ad,
                "color_lente" => $color_lente,
                "bif" => $bif,
                "aro" => $aro,
                "id_aro" => $id_aro,
                "tamanio" => $tamanio,
                "color_aro" => $color_aro,
                "observaciones" => $observaciones
            );
            $id = MD5($id);
            $where = "md5(id)='$id'";
           /* $aros_existencia = $this->Cliente_model->get_existe_aro($id_aro);
            $existencia=$aros_existencia->existencia;
            if ($existencia>0){*/
                $insertar = $this->Utils_model->_update($table, $form_data, $where);
           // }
        }
        if ($insertar) {
            if ($aro!=""){
            $aros_existencia = $this->Cliente_model->get_existe_aro($id_aro);
            $existencia=$aros_existencia->existencia;
            $codigo=$aros_existencia->codigo;
            $existencia=$existencia-1;
            $tabla_aro="aro";
            $where_aro=" id='$id_aro'";
            $form_data_aro = array(
                "existencia" => $existencia,
            );
            if ($existencia>=0){
                $update_aro = $this->Utils_model->_update($tabla_aro, $form_data_aro, $where_aro);
                if ($update_aro){
                    $fecha_registro=date("Y-m-d");
                    $hora_registro=date("H:i:s");
                    $id_sucursal=$this->session->id_sucursal;
                    $id_usuario=$this->session->id_usuario;
                    $table_mov = "movimientos";
                    $form_data_mov = array(
                        'codigo' => $codigo,
                        'motivo' => "POR VENTA A".$nombre_cli,
                        'cantidad' => 1,
                        'tipo' => "SALIDA",
                        'id_sucursal' => $id_sucursal,
                        'id_usuario' => $id_usuario,
                        'fecha' => $fecha_registro,
                        'hora' => $hora_registro,
                    );
                    if ($nuevaex>=0){
                        $insertar_mov = $this->Utils_model->_insert($table_mov, $form_data_mov);

                    }
            }
            }
        }
            $xdatos['typeinfo'] = 'Success';
            //$xdatos['id']=$id_ex;
            $xdatos['msg'] = "Datos guardados con exito!!!";
        } else {
            $xdatos['typeinfo'] = 'Error';
            $xdatos['msg'] = "Datos no pudieron ser guardados verificar si hay existencias!!!";
        }

        $xdatos['url'] = base_url("Cliente");
        //$xdatos['pdf'] = base_url("Examen") . "/imprimir_examen/" . md5($id_ex);
        //$xdatos['md5'] = md5($id_ex);
        $xdatos['s'] = $sucursal;
        $xdatos['o'] = $id_optometrista;
        echo json_encode($xdatos);
    }
	function editar_cliente_vista($id=-1){
		$this->load->helper('template_helper');
		$this->load->model('Cliente_model');

		$rows1 = $this->Cliente_model->get($id);
		$id_cliente=$rows1->id;
		$nombre=$rows1->nombre;
		$edad = $rows1->edad;
		$sexo = $rows1->sexo;
		$dui = $rows1->dui;
		$nit = $rows1->nit;
		$nrc = $rows1->nrc;
		$direccion = $rows1->direccion;
		$departamento = $rows1->departamento;
		$municipio = $rows1->municipio;
		$telefono = $rows1->telefonos;

		$array_departamento = $this->Cliente_model->get_departamento();
		$array_municipio = $this->Cliente_model->get_municipio($departamento);

		$data = array(
			'array_departamento' => $array_departamento,
			'array_municipio' => $array_municipio,
			'id_cliente' => $id_cliente,
			'nombre' => $nombre,
			'edad' => $edad,
			'fecha_nacimiento' => $rows1->fecha_nacimiento,
			'sexo' => $sexo,
			'dui' => $dui,
			'nit' => $nit,
			'nrc' => $nrc,
			'direccion' => $direccion,
			'departamento' => $departamento,
			'municipio' => $municipio,
			'telefono' => $telefono,

		);
		template('cliente/editar_cliente',$data);

	}
	function editar_cliente()
	{
		$this->load->model('Cliente_model');
		date_default_timezone_set('America/El_Salvador');
		$fecha_actualiza=date("Y-m-d")." ".date("H:i:s");
		$id_cliente = $this->input->post("id_cliente");
		$nombre = $this->input->post("nombre");
		$edad = $this->input->post("vistaEdad");
		$fecha_nacimiento = $this->input->post("edad");
		$sexo = $this->input->post("sexo");
		$dui = $this->input->post("dui");
		$nit = $this->input->post("nit");
		$nrc = $this->input->post("nrc");
		$direccion = $this->input->post("direccion");
		$departamento = $this->input->post("departamento");
		$municipio = $this->input->post("municipio");
		$telefono = $this->input->post("telefono");
		//echo $edad."#";
		$valor=	array(
			"nombre" => $nombre,
			"edad" => $edad,
			"sexo" => $sexo,
			"id!=" => $id_cliente,
		);
		$total = $this->Cliente_model->existe_cliente($valor);

		$tabla = "cliente";
		$form_data = array(
			"nombre" => $nombre,
			"edad" => $edad,
			"fecha_nacimiento" => Y_m_d($fecha_nacimiento),
			"sexo" => $sexo,
			"dui" => $dui,
			"nit" => $nit,
			"nrc" => $nrc,
			"direccion" => $direccion,
			"departamento" => $departamento,
			"municipio" => $municipio,
			"telefonos" => $telefono,
			"fecha_actualiza" => $fecha_actualiza,
		);

		if ($total==0) {
			$insert = $this->Cliente_model->update($id_cliente, $form_data);
			if ($insert == 1)
			{

				$xdatos['typeinfo']='Success';
				$xdatos['msg']='Cliente editado correctamente!';
				$xdatos['process']='insert';
			}
			else
			{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Cliente no pudo ser editado!';
			}


		}else {
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Este cliente ya fue editado!';
		}


		$xdatos['url'] = base_url("Cliente");

		echo json_encode($xdatos);

	}

    function  editar_examen($id=-1) {
        $this->load->helper('template_helper');
        $this->load->model('Cliente_model');
        $data1 = $this->Cliente_model->get_cliente_dat($id);
        $id_cliente=$data1->id;
        $nombre=$data1->nombre;
        $edad = $data1->edad;
        $sexo = $data1->sexo;
        $id_sur=$this->session->id_sucursal;
        $data2 = $this->Cliente_model->get_datos($id);
        $array_aro = $this->Cliente_model->get_aros($id_sur);
        $datos = array(
            "id_cliente"=>$id_cliente,
            "nombre"=>$nombre,
            "edad"=>$edad,
            "sexo"=>$sexo,
            "datos_consulta"=>$data2,
            "array_aros"=>$array_aro,
        );
        template('cliente/editar_examen',$datos);
    }
		function calcular_edad(){
			$fecha = $this->input->post("fecha");
			$fechaNac = new DateTime(Y_m_d($fecha));
			$fechaHoy = new DateTime(date("Y-m-d"));
			$diff = $fechaHoy->diff($fechaNac);
			echo $diff->format("%y");
			//echo $fecha;
		}
		function restar_edad(){
			$fecha = $this->input->post("fecha");
			$fechaHoy = date("d-m-Y");
			$fecha_nacimiento = date("d-m-Y",strtotime($fechaHoy."- ".$fecha." years"));
			//echo $fechaHoy." - ".$fecha." = ";
			echo $fecha_nacimiento;
			//echo $fecha;
		}
		function actualizarFechas(){
			$this->load->model('Utils_model',"utils");
			//echo "sdjndshjsdj";
			$arr = $this->db->get("cliente");
			$fechaHoy = new DateTime(date("Y-01-01"));
			$centinela = 1;
			//echo date("Y-01-01");
			//echo $fechaHoy;
			//echo "dsjfsjdsfhjfsd";
			//var_dump($arr->result());
			foreach ($arr->result() as $arrCliente) {
				// code...
				//echo "dsfdfds";
				//echo $arrCliente->edad."#";
				$edad = $arrCliente->edad;
				$id = $arrCliente->id;
				//echo $id;
				$fecha_nacimiento = date("Y-01-01",strtotime($fechaHoy."- ".$edad." year"));
				//echo $fecha_nacimiento;
				$datos = array("fecha_nacimiento"=>$fecha_nacimiento);
				$actualizar = $this->utils->_update("cliente", $datos, " id=".$id);
				//echo $fecha_nacimiento."#";
				if ($actualizar) {
					// code...
					//echo "1";
				}
				else {
					//echo "0";
					$centinela = 0;
					break;
				}
			}
			if ($centinela==1) {
				// exito...
				$xdatos['typeinfo']='Success';
				$xdatos['msg']='Cambios realizados correctamente!';
			}
			else {
				// code...
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Error al realizar cambios!';
			}
			echo json_encode($xdatos);
		}
}


/* End of file Gerencias.php */
