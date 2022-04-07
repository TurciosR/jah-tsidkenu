<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Factura extends CI_Controller {

	public function index(){
		validar_session($this);
		$data = array(
			'tipo' => 5,
			'nombre_archivo' => 'Administrar Facturas',
			'icono' => 'fa fa-user-circle',
			'urljs' => 'funciones_factura.js',
			'url_agregar' => 'Factura/agregar_factura',
			'txt_agregar' => 'Nuevo Factura',
			'tabla'=> array(
				'ID' => 1,
				'N° DOC' => 1,
				'FECHA' => 1,
				'CLIENTE'=>3,
				'TIPO DOC'=>3,
				'MONTO'=>1,
				'ESTADO'=>1,
				'ACCIONES'=>1,
			),
		);
		$this->load->helper('template_helper');
		template('factura/admin',$data);
	}

	public function get_data()
	{
		$this->load->model('Factura_model');

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
			0 => 'num_doc',
			1 => 'nombre',
			2 => 'total',
			3 => 'tipo',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->session->id_sucursal;
		$examenes = $this->Factura_model->get_factura($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);
		if ($examenes != 0) {
			$data = array();
			$num = 1;
			foreach ($examenes as $rows) {

				$menudrop = "<div class='btn-group'>
				<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
				<ul class='dropdown-menu dropdown-primary'>";

				$filename = base_url() . "Factura/ver_detalle_factura";
				$menudrop .= "<li><a href='" . $filename . "/" .($rows->id_factura). "' role='button' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye' ></i> Ver detalle</a></li>";
				if ($rows->estado==1) {
					$menudrop .= "<li><a class='anular' id='".$rows->id_factura."'><i class='fa fa-close' ></i> Anular</a></li>";
					$menudrop .= "<li><a class='copiaranular' id='".$rows->id_factura."'><i class='fa fa-copy' ></i> Copiar y Anular</a></li>";
				}

				$menudrop .= "</ul></div>";
				$estado="";
				if ($rows->estado==1) {
					$estado="<span class='label label-primary'>FINALIDADA</span>";
				}
				if ($rows->estado==2) {
					$estado="<span class='label label-danger'>ANULADA</span>";
				}

				$data[] = array(
					$num,
					$rows->num_doc,
					$rows->fecha,
					$rows->nombre,
					$rows->tipo,
					$rows->total,
					$estado,
					$menudrop,
				);

				$num++;
			}
			$total = $this->Factura_model->count_factura($id_sucursal);
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

	function  agregar_factura()
	{
		$fecha_actual=date("d-m-Y");
		$this->load->model('Factura_model');
		$id_sucursal=$this->session->id_sucursal;
		$row_confi=$this->Factura_model->get_sucursal($id_sucursal);
		$iva=$row_confi->iva;
		$retencion=$row_confi->retencion;

		$data = array('iva' => $iva,'retencion' => $retencion,'fecha_actual' => $fecha_actual );
		$this->load->helper('template_helper');
		template('factura/agregar_factura',$data);
	}
	function guardar_factura()
	{
		$this->load->model('Factura_model');
		$this->load->helpers('utilities_helper');
		date_default_timezone_set('America/El_Salvador');
		$fecha_actual=Y_m_d($this->input->post("fecha_actual"));
		$hora_actual=date("H:i:s");
		$id_sucursal=$this->session->id_sucursal;
		$id_usuario=$this->session->id_usuario;
		$id_cliente = $this->input->post("id_cliente");//
		$nit = $this->input->post("nit");//
		$nrc = $this->input->post("nrc");//
		$tipo = $this->input->post("tipo");//
		$num_doc = $this->input->post("num_doc");//
		$retencion_bol = $this->input->post("retencion_bol");
		$total_retencion = $this->input->post("total_retencion");
		$total_iva = 0;//$this->input->post("total_iva");
		$total = $this->input->post("total");
		$id_cuenta = $this->input->post("id_cuenta");//
		$saldo_anterior = $this->input->post("saldo_anterior");//
		$abono_hoy = $this->input->post("abono_hoy");//
		$saldo_actual = $this->input->post("saldo_actual");//
		$abono_anterior = $this->input->post("abono_anterior");//
		$descripcion = $this->input->post("descripcion");//
		$datos = json_decode($this->input->post("datos"), true);

		//////////////////////////////////////////ABONO/////////////////////////////////
		if ($tipo=="ABONO") {
			$abonado=$abono_anterior+$abono_hoy;
			$table_abono = "abono";
			$form_abono = array(
				'id_cuenta' => $id_cuenta,
				'abono' => $abono_hoy,
				'numero_doc' => $num_doc,
				'fecha' => $fecha_actual,
			);
			$insert_abo = $this->Factura_model->save($table_abono, $form_abono);
			$id_factura =$this->Factura_model-> _insert_id();
			if ($insert_abo) {
				$tabla= "cuenta";
				if ($saldo_actual==0){
					$form_data = array(
						'saldo' => $saldo_actual,
						'abono' => $abonado,
						'estado' => 1,
					);
				}else{
					$form_data = array(
						'saldo' => $saldo_actual,
						'abono' => $abonado,
					);
				}
				$where =" id_cuenta='$id_cuenta'";
				$update=$this->Factura_model->actualizar($tabla,$form_data,$where);
				if ($update) {
					$this->Factura_model->commit();
					$xdatos['typeinfo'] = 'Success';
					$xdatos['msg'] = 'Abono realizado con exito!';
					$xdatos['id_factura']=$id_factura;
					$xdatos['tipo_impresion']=$tipo;
					// $xdatos['fecha'] = $fecha_actual;
					// $xdatos['saldo'] = $saldo;
					// $xdatos['abono'] = $abono;
					// $xdatos['abonado'] = $abonado;

				} else {
					$this->Factura_model->rollback();
					$xdatos['typeinfo']='Error';
					$xdatos['msg']='Esta Cuenta ya existe!';
				}
			}else{
				$this->Factura_model->rollback();
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Error al guardar el abono!';
			}
		}
		//////////////////////////////////////COF Y CCF ///////////////////////////
		if ($tipo=="COF" || $tipo=="CCF") {
			$tabla= "factura";
			$form_data = array(
				'id_cliente' => $id_cliente,
				'total' => $total,
				'iva' => $total_iva,
				'num_doc' => $num_doc,
				'tipo' => $tipo,
				'retencion_bol' => $retencion_bol,
				'retencion' => $total_retencion,
				'id_usuario' => $id_usuario,
				'fecha' => $fecha_actual,
				'hora' => $hora_actual,
				'id_sucursal' => $id_sucursal,
				'estado' => 1,
			);
			$valor=	array(
				'id_cliente' => $id_cliente,
				'total' => $total,
				'iva' => $total_iva,
				'num_doc' => $num_doc,
				'tipo' => $tipo,
				'retencion_bol' => $retencion_bol,
				'retencion' => $total_retencion,
				'id_usuario' => $id_usuario,
				'fecha' => $fecha_actual,
				'id_sucursal' => $id_sucursal,
			);
			$total = $this->Factura_model->existe_factura($valor);
			if ($total==0) {
				$this->Factura_model->begin();
				$insert = $this->Factura_model->save($tabla, $form_data);
				$id_factura =$this->Factura_model-> _insert_id();
				if ($tipo=="CCF") {
					$tablacl = "cliente";
					$form_datacl = array(
						"nit" => $nit,
						"nrc" => $nrc,
					);
					$where="id='".$id_cliente."'";
					$actualizarcl = $this->Factura_model->actualizar($tablacl, $form_datacl,$where);
				}
				if($insert)
				{

					$error = 0;
					$table_fd = "factura_detalle";
					$num_datos=count($datos);
					$e=0;
					foreach ($datos as $data)
					{
						$cantidad = $data["cant"];
						$descripcion = $data["desc"];
						$precio = $data["prec"];
						$subtotal = $data["subt"];
						$form_fd = array(
							'id_factura' => $id_factura,
							'cantidad' => $cantidad,
							'valor' => $precio,
							'sub_total' => $subtotal,
							'descripcion' => $descripcion,

						);
						$ins = $this->Factura_model->save($table_fd, $form_fd);
						if($ins)
						{
							$e++;
						}
					}
					if($num_datos==$e)
					{
						$this->Factura_model->commit();
						$xdatos['typeinfo']='Success';
						$xdatos['msg']='Datos ingresadas correctamente!';
						$xdatos['id_factura']=$id_factura;
						$xdatos['tipo_impresion']=$tipo;
					}
					else
					{
						$this->Factura_model->rollback();
						$xdatos['typeinfo']='Error';
						$xdatos['msg']='Error al guardar los detalles!';
					}
				}
				else
				{
					$this->Factura_model->rollback();
					$xdatos['typeinfo']='Error';
					$xdatos['msg']='Error al guardar los los datos de la factura! ';
				}
			}else {
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Esta factura ya existe!';
			}
		}
		$xdatos['url'] = base_url("Factura");

		echo json_encode($xdatos);
	}
	function fetch($query)
	{
		$id_sucursal=$this->session->id_sucursal;
		$this->load->model('Factura_model');
		echo $this->Factura_model->traer_cliente($query,$id_sucursal);
	}




	function  ver_detalle_factura($id_factura) {
		$this->load->helper('template_helper');
		$this->load->model('Factura_model');

		$factura = $this->Factura_model->get_factura_modal($id_factura);
		$id_factura1=$factura->id_factura;
		$tipo=$factura->tipo;
		$nombre=$factura->nombre;
		$fecha=$factura->fecha;
		$tipo_desc=$factura->tipo_desc;
		$num_doc=$factura->num_doc;
		$subtotal=$factura->total;
		$iva=$factura->iva;
		$retencion_bol=$factura->retencion_bol;
		$retencion=$factura->retencion;
		$total_final=$subtotal+$iva-$retencion;
		if ($tipo=="CCF") {
			$subtotal=number_format(round(($subtotal/1.13), 2),2);
			$iva=number_format(round(($subtotal*0.13), 2),2);
			//$retencion=number_format(round(($subtotal*0.01), 2),2);
			$total_final = number_format(round($subtotal + $iva - $retencion , 2), 2);

		}



		$factura_detalle = $this->Factura_model->get_factura_detalle_modal($id_factura);
		$data = array(
			'nombre' => $nombre,
			'fecha' => $fecha,
			'tipo_desc' => $tipo_desc,
			'num_doc' => $num_doc,
			'subtotal' => $subtotal,
			'total_final' => $total_final,
			'iva' => $iva,
			'retencion_bol' => $retencion_bol,
			'retencion' => $retencion,
			'factura_detalle' => $factura_detalle,
			'id_factura' => $id_factura1,
			'tipo' => $tipo,
		);
		$this->load->view('factura/ver_detalle_factura',$data);
	}

	function imprimir_fact() {
		$this->load->helper('facturacion_imprimir_helper');
		$this->load->library('user_agent');
		$this->load->model('Factura_model');
		$id_sucursal=$this->session->id_sucursal;

		$tipo_impresion = $this->input->post("tipo_impresion");
		$id_factura = $this->input->post("id_factura");
		if ($tipo_impresion=='COF') {
			$tipo_entrada_salida="FACTURA CONSUMIDOR";
		}
		if ($tipo_impresion=='ABONO') {
			$tipo_entrada_salida="ABONO";
		}
		if ($tipo_impresion=='CCF') {
			$tipo_entrada_salida="CREDITO FISCAL";
		}
		//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
		$info = $this->agent->platform();
		if (strpos($info, 'Windows') == true) {
			$so_cliente='win';
		} else {
			$so_cliente='lin';
		}


		$headers="";
		$footers="";
		if ($tipo_impresion=='COF') {
			$info_facturas=print_fact($id_factura, $tipo_impresion);
		}

		if ($tipo_impresion=='CCF') {
			$info_facturas=print_ccf($id_factura, $tipo_impresion);
		}
		if ($tipo_impresion=='ABONO') {
			$info_facturas=print_abono($id_factura, $tipo_impresion);
		}
		//directorio de script impresion cliente


		$rows1 = $this->Factura_model->get_config_dir($id_sucursal);
		$dir_print=$rows1->dir_print_script;
		$shared_printer_win=$rows1->shared_printer_matrix;
		$shared_printer_pos = $rows1->shared_printer_pos;
		$nreg_encode['shared_printer_win'] =$shared_printer_win;
		$nreg_encode['shared_printer_pos'] =$shared_printer_pos;
		$nreg_encode['dir_print'] =$dir_print;
		$nreg_encode['facturar'] =$info_facturas;
		$nreg_encode['sist_ope'] =$so_cliente;
		$nreg_encode['headers'] =$headers;
		$nreg_encode['footers'] =$footers;
		$nreg_encode['tipo_impresion'] =$tipo_impresion;


		echo json_encode($nreg_encode);
	}
	function imprimir_examen()
	{
		$this->load->helper('facturacion_imprimir_helper');
		$this->load->library('user_agent');
		$this->load->model('Factura_model');

		$id_sucursal=$this->session->id_sucursal;

		$id_examen = $this->input->post("id_examen");
                $sucurs = $this->input->post("id_sucursal");
		//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
		$info = $this->agent->platform();
		if (strpos($info, 'Windows') == true) {
			$so_cliente='win';
		} else {
			$so_cliente='lin';
		}
		$headers="";
		$footers="";

		$info_facturas=print_examen($id_examen);

		$rows1 = $this->Factura_model->get_config_dir($sucurs);
		$dir_print=$rows1->dir_print_script;
		$shared_printer_win=$rows1->shared_printer_matrix;
		$shared_printer_pos = $rows1->shared_printer_pos;
		$nreg_encode['shared_printer_win'] =$shared_printer_win;
		$nreg_encode['shared_printer_pos'] =$shared_printer_pos;
		$nreg_encode['dir_print'] =$dir_print;
		$nreg_encode['facturar'] = utf8_encode($info_facturas);
		$nreg_encode['sist_ope'] =$so_cliente;
		$nreg_encode['headers'] =$headers;
		$nreg_encode['footers'] =$footers;
		$nreg_encode['tipo_impresion'] ="";

		echo json_encode($nreg_encode);
	}

	function imprimir_formato()
	{
		$this->load->helper('facturacion_imprimir_helper');
		$this->load->library('user_agent');
		$this->load->model('Factura_model');

		$id_sucursal=$this->session->id_sucursal;

		$id_examen = $this->input->post("id_examen");
                $sucurs = $this->input->post("id_sucursal");
		//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
		$info = $this->agent->platform();
		if (strpos($info, 'Windows') == true) {
			$so_cliente='win';
		} else {
			$so_cliente='lin';
		}
		$headers="";
		$footers="";

		$info_facturas=print_examen_formato($id_examen);

		$rows1 = $this->Factura_model->get_config_dir($sucurs);
		$dir_print=$rows1->dir_print_script;
		$shared_printer_win=$rows1->shared_printer_matrix;
		$shared_printer_pos = $rows1->shared_printer_pos;
		$nreg_encode['shared_printer_win'] =$shared_printer_win;
		$nreg_encode['shared_printer_pos'] =$shared_printer_pos;
		$nreg_encode['dir_print'] =$dir_print;
		$nreg_encode['facturar'] = utf8_encode($info_facturas);
		$nreg_encode['sist_ope'] =$so_cliente;
		$nreg_encode['headers'] =$headers;
		$nreg_encode['footers'] =$footers;
		$nreg_encode['tipo_impresion'] ="";

		echo json_encode($nreg_encode);
	}
	function verificar_abono(){
		$this->load->helper('template_helper');
		$this->load->model('Factura_model');
		$id_cliente = $this->input->post("id_cliente");
		$row=$this->Factura_model->get_cuenta($id_cliente);
		if($row!=0)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['datos']=$row;
			$xdatos['msg']='Movimiento eliminado correctamente!';
		}
		else
		{
			$xdatos['typeinfo']='Error';
		}

		$xdatos['url'] = base_url("Caja");

		echo json_encode($xdatos);
	}

	public function anular()
	{
		$id = $this->input->post("id");
		$this->load->model('Factura_model');
		$tabla = "factura";
		$form_data = array(
			"estado" => 2,
		);
		$where="MD5(id_factura)='".$id."'";
		$actualizar = $this->Factura_model->actualizar($tabla, $form_data,$where);
		if($actualizar)
		{
			$xdata["typeinfo"] = 'Success';
			$xdata["msg"] = 'Factura anulada con exito!!!';
		}
		else
		{
			$xdata["typeinfo"] = 'Error';
			$xdata["msg"] = 'Factura no pudo ser anulada!!!';
		}
		echo json_encode($xdata);
	}
	public function copiaranular()
	{
		$id_facturamd5 = $this->input->post("id");
		$num_doc = $this->input->post("num_doc");
		$this->load->model('Factura_model');
		$tabla = "factura";
		$form_data = array(
			"estado" => 2,
		);
		$where="MD5(id_factura)='".$id_facturamd5."'";

		$actualizar = $this->Factura_model->actualizar($tabla, $form_data,$where);
		if($actualizar)
		{
			$rowc = $this->Factura_model->get_facturac($id_facturamd5);
			$tablaf= "factura";
			$form_dataf = array(
				'id_cliente' => $rowc->id_cliente,
				'total' => $rowc->total,
				'iva' => $rowc->iva,
				'num_doc' => $num_doc,
				'tipo' => $rowc->tipo,
				'retencion_bol' => $rowc->retencion_bol,
				'retencion' => $rowc->retencion,
				'id_usuario' => $rowc->id_usuario,
				'fecha' => $rowc->fecha,
				'hora' => $rowc->hora,
				'id_sucursal' => $rowc->id_sucursal,
				'estado' => 1,
			);
			$this->Factura_model->begin();
			$insertf = $this->Factura_model->save($tablaf, $form_dataf);
			$id_factura =$this->Factura_model-> _insert_id();
			if($insertf)
			{
				$error = 0;
				$table_fd = "factura_detalle";
				$e=0;
				$datos = $this->Factura_model->get_factura_detallec($id_facturamd5);
				$num_datos=count($datos);
				foreach ($datos as $data)
				{
					$cantidad = $data->cantidad;
					$descripcion = $data->descripcion;
					$precio = $data->valor;
					$subtotal = $data->sub_total;
					$form_fd = array(
						'id_factura' => $id_factura,
						'cantidad' => $cantidad,
						'valor' => $precio,
						'sub_total' => $subtotal,
						'descripcion' => $descripcion,

					);
					$ins = $this->Factura_model->save($table_fd, $form_fd);
					if($ins)
					{
						$e++;
					}
				}
				if($num_datos==$e)
				{
					$this->Factura_model->commit();
					$xdata['typeinfo']='Success';
					$xdata['msg']='Datos ingresadas correctamente!';
					$xdata['id_factura']=$id_factura;
					$xdata['tipo_impresion']=$rowc->tipo;
				}
				else
				{
					$this->Factura_model->rollback();
					$xdata['typeinfo']='Error';
					$xdata['msg']='Error al guardar los detalles!';
				}
			}
			else
			{
				$this->Factura_model->rollback();
				$xdata['typeinfo']='Error';
				$xdata['msg']='Error al guardar los los datos de la factura! ';
			}

		}
		else
		{
			$xdata["typeinfo"] = 'Error';
			$xdata["msg"] = 'Factura no pudo ser anulada!!!';
		}
		$xdata['url'] = base_url("Factura");
		echo json_encode($xdata);
	}

}


/* End of file Usuarios.php */
