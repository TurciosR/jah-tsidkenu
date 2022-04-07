<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud extends CI_Controller {

	public function index(){
		validar_session($this);
		$data = array(
			'tipo' => 5,
			'nombre_archivo' => 'Solicitudes Recibidas',
			'icono' => 'fa fa-user-circle',
			'urljs' => 'funciones_solicitud.js',
			//'url_agregar' => '',
			//'txt_agregar' => '',
			'tabla'=> array(
				'ID' => 1,
				'ARO' => 1,
				'USUARIO' => 2,
				'SUCURSAL'=>3,
				'F. SOLICITUD'=>1,
				'F. RESOLUCION'=>2,
				'ESTADO'=>1,
				'ACCION'=>1,
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
			5 => 'DATE_FORMAT(fecha, "%d-%m-%Y")',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}
		$id_sucursal=$this->session->id_sucursal;
		$solicitudes = $this->Solicitud_model->get_solisitud($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);
		if ($solicitudes != 0) {
			$data = array();
			$num = 1;
			foreach ($solicitudes as $rows) {

				$menudrop = "<div class='btn-group'>
                <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
                <ul class='dropdown-menu dropdown-primary'>";

				//$filename = base_url() . "Solicitud/ver_detalle_factura";
				//$menudrop .= "<li><a href='" . $filename . "/" .($rows->id_solicitud). "' role='button' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye' ></i> Detalle</a></li>";
				if($rows->estado==0){
					$menudrop .= "<li><a  data='".$rows->id_solicitud."' class='confirmar'><i class='fa fa-check'></i> Confirmar</a></li>";
					$menudrop .= "<li><a  data='".$rows->id_solicitud."' class='cancelar'><i class='fa fa-close'></i> Cancelar</a></li>";
				}
				$menudrop .= "</ul></div>";
				if ($rows->estado==0){
				    $estado='<span class="label label-warning">PENDIENTE</span>';
                }elseif ($rows->estado==1){
                    $estado='<span class="label label-primary">ENVIADO</span>';
                }elseif ($rows->estado==2){
                    $estado='<span class="label label-danger">CANCELADA</span>';
                }

				$data[] = array(
					$rows->id,
					$rows->codigo,
					$rows->nombre,
					$rows->sucur,
					$rows->fecha,
					$rows->fecha_re,
                    $estado,
					$menudrop,
				);

				$num++;
			}
			$total = $this->Solicitud_model->count_solicitud($id_sucursal);
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

	function  agregar_factura()
	{
		$this->load->model('Factura_model');
		$id_sucursal=$this->session->id_sucursal;
		$row_confi=$this->Factura_model->get_sucursal($id_sucursal);
		$iva=$row_confi->iva;
		$retencion=$row_confi->retencion;
		$data = array('iva' => $iva,'retencion' => $retencion );
		$this->load->helper('template_helper');
		template('factura/agregar_factura',$data);
	}
	function guardar_factura()
	{
		$this->load->model('Factura_model');
		date_default_timezone_set('America/El_Salvador');
		$fecha_actual=date("Y-m-d");
		$hora_actual=date("H:i:s");
		$id_sucursal=$this->session->id_sucursal;
		$id_usuario=$this->session->id_usuario;
		$id_cliente = $this->input->post("id_cliente");//
		$tipo = $this->input->post("tipo");//
		$num_doc = $this->input->post("num_doc");//
		$retencion_bol = $this->input->post("retencion_bol");
		$total_retencion = $this->input->post("total_retencion");
		$total_iva = $this->input->post("total_iva");
		$total = $this->input->post("total");
		$saldo_anterior = $this->input->post("saldo_anterior");//
		$abono_hoy = $this->input->post("abono_hoy");//
		$saldo_actual = $this->input->post("saldo_actual");//
		$descripcion = $this->input->post("descripcion");//
		$datos = json_decode($this->input->post("datos"), true);

		//////////////////////////////////////////ABONO/////////////////////////////////
		if ($tipo=="ABONO") {
			$tabla= "factura";
			$form_data = array(
				'id_cliente' => $id_cliente,
				'tipo' => $tipo,
				'num_doc' => $num_doc,
				'saldo_anterior' => $saldo_anterior,
				'abono_hoy' => $abono_hoy,
				'saldo_actual' => $saldo_actual,
				'descripcion' => $descripcion,
				'id_usuario' => $id_usuario,
				'fecha' => $fecha_actual,
				'hora' => $hora_actual,
				'id_sucursal' => $id_sucursal,

			);
			$valor=	array(
				'id_cliente' => $id_cliente,
				'tipo' => $tipo,
				'num_doc' => $num_doc,
				'saldo_anterior' => $saldo_anterior,
				'abono_hoy' => $abono_hoy,
				'saldo_actual' => $saldo_actual,
				'descripcion' => $descripcion,
				'id_usuario' => $id_usuario,
				'fecha' => $fecha_actual,
				'id_sucursal' => $id_sucursal,
			);
			$total = $this->Factura_model->existe_factura($valor);
			if ($total==0) {
				$insert = $this->Factura_model->save($tabla, $form_data);
				if($insert)
				{
					$xdatos['typeinfo']='Success';
					$xdatos['msg']='Datos ingresadas correctamente!';
				}
				else
				{
					$xdatos['typeinfo']='Error';
					$xdatos['msg']='Error al guardar los los datos! ';
				}
			}else {
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Esta factura ya existe!';
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
				if($insert)
				{
					$id_factura =$this->Factura_model-> _insert_id();
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
		$saldo_anterior=$factura->saldo_anterior;
		$abono_hoy=$factura->abono_hoy;
		$saldo_actual=$factura->saldo_actual;
		$descripcion=$factura->descripcion;
		$subtotal=$factura->total;
		$iva=$factura->iva;
		$retencion_bol=$factura->retencion_bol;
		$retencion=$factura->retencion;

		$total_final=$subtotal+$retencion+$iva;


		$factura_detalle = $this->Factura_model->get_factura_detalle_modal($id_factura);
		$data = array(
			'nombre' => $nombre,
			'fecha' => $fecha,
			'tipo_desc' => $tipo_desc,
			'num_doc' => $num_doc,
			'saldo_anterior' => $saldo_anterior,
			'abono_hoy' => $abono_hoy,
			'saldo_actual' => $saldo_actual,
			'descripcion' => $descripcion,
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




	function confirmar_solicitud(){
		$this->load->model('Solicitud_model');
		$this->load->model('Utils_model');
		$id = $this->input->post("id");

		$insert = false;

		$dat = $this->Solicitud_model->get_one_solicitud($id);
		if ($dat) {
			// code...

			$sucQueSolicita  = $dat->id_sucursal;
			$sucAlaQueSolicitan = $dat->sucursal_solicita;
			$codigo = trim($dat->codigo);
			//obtenemos las existencia del aro

			$aroe  = $this->Solicitud_model->get_existe_aro($codigo,$sucAlaQueSolicitan);
			if ($aroe) {
				// existe el registro

				$existencias = $aroe->existencia;
				$id_aroLocal = $aroe->id;
				$casa = $aroe->casa;
				$marca = $aroe->marca;

				if (($existencias-1)>-1) {
					// si se puede descargar una unidad

					//si existe en el destino
					$existeDestino = $this->Solicitud_model->existe_aro($codigo,$sucQueSolicita);

					//insertarmos en el destino
					$table = "aro";
					if ($existeDestino) {
							//existencia en el destino
							$arod  = $this->Solicitud_model->get_existe_aro($codigo,$sucQueSolicita);

							$ex =($arod->existencia)+1;
							$idd = md5($arod->id);
							$form_data = array(
							'codigo' => $codigo,
							'marca' => $marca,
							'casa' => $casa,
							'existencia' => $ex,
						);
							$where = "md5(id)='$idd'";
							$insertar = $this->Utils_model->_update($table, $form_data, $where);

					} else {
						$form_data = array(
							'codigo' => $codigo,
							'marca' => $marca,
							'casa' => $casa,
							'existencia' => 1,
							'sucursal' => $sucQueSolicita,
						);
							$insertar = $this->Utils_model->_insert($table, $form_data);
					}

					//descargar en la sucursal a la que le solicitan
					$ex =$existencias-1;
					$idd = md5($id_aroLocal);
					$form_data = array(
						'codigo' => $codigo,
						'marca' => $marca,
						'casa' => $casa,
						'existencia' => $ex,
					);
					$where = "md5(id)='$idd'";
					$insertar = $this->Utils_model->_update($table, $form_data, $where);

					$tabla="solicitudes";
					$form_data = array(
						"estado" => 1,
					);
					$where="MD5(id_solicitud)='".$id."'";
					$insert = $this->Solicitud_model->actualizar($tabla, $form_data,$where);


				}
			}
		}

		if($insert)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Solicitud confirmada correctamente!';
		}
		else
		{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Solicitud no pudo ser confirmada!';
		}

		$xdatos['base'] = base_url("Solicitud");

		echo json_encode($xdatos);
	}

	function cancelar_solicitud(){
		$this->load->model('Solicitud_model');
		$id = $this->input->post("id");
		$tabla="solicitudes";
		$form_data = array(
			"estado" => 2,
		);
		$where="MD5(id_solicitud)='".$id."'";
		$insert = $this->Solicitud_model->actualizar($tabla, $form_data,$where);
		if($insert)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Solicitud cancelada correctamente!';
		}
		else
		{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Solicitud no pudo ser cancelada!';
		}

		$xdatos['base'] = base_url("Solicitud");

		echo json_encode($xdatos);
	}

}


/* End of file Usuarios.php */
