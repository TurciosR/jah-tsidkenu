<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traslados_pendientes extends CI_Controller {
	/*
	Global table name
	*/
	private $table = "stock";
	private $pk = "id_producto";

	function __construct()
	{
		parent::__construct();
		$this->load->model('UtilsModel',"utils");
		$this->load->model("Traslados_pendientesModel","traslados");
	}

	public function index()
	{
		$id_usuario=$this->session->id_usuario;
		$id_sucursal=$this->session->id_sucursal;
		$usuario_tipo =	$this->traslados->get_one_row("usuario", array('id_usuario' => $id_usuario,));
		if($usuario_tipo!=NULL){
			if($usuario_tipo->admin==1 || $usuario_tipo->super_admin==1){
					$sucursales=$this->traslados->get_detail_rows("sucursales",array('1' => 1, ));
			}else {
					$sucursales=$this->traslados->get_detail_rows("sucursales", array('id_sucursal' => $id_sucursal,));
			}
	 }else {
			$sucursales=$this->traslados->get_detail_rows("sucursales",array('1' => 1, ));
	 }
		$data = array(
			"titulo"=> "Traslados",
			"icono"=> "mdi mdi-cart",
			"buttons" => array(
			),
			"selects" => array(
				0 => array(
					"name" => "sucursales",
					"data" => $sucursales,
					"id" => "id_sucursal",
					"text" => array(
						"nombre",
						"direccion",
					),
					"separator" => " ",
					"selected" => $id_sucursal,
				),
			),
			"table"=>array(
				"ID"=>5,
				"Fecha"=>10,
				"Origen"=>10,
				"Destino"=>10,
				"Total"=>5,
        "Estado"=>10,
				"Detalle"=>25,
				"Acciones"=>10,
			),
		);
		$extras = array(
			'css' => array(
			),
			'js' => array(
				"js/scripts/traslados_pendientes.js"
			),
		);
		layout("template/admin",$data,$extras);
	}

	function get_data(){
		$draw = intval($this->input->post("draw"));
		$start = intval($this->input->post("start"));
		$length = intval($this->input->post("length"));
		$id_sucursal = $this->input->post("id_sucursal");

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
			0 => 'v.id_traslado',
			1 => 'v.fecha',
			2 => 'CONCAT(s1.nombre," ",s1.direccion)',
			3 => 'CONCAT(s2.nombre," ",s2.direccion)',
		);
		if (!isset($valid_columns[$col])) {
			$order = null;
		} else {
			$order = $valid_columns[$col];
		}

		$row = $this->traslados->get_collection($order, $search, $valid_columns, $length, $start, $dir, $id_sucursal);
		//print_r($row);
		if ($row != 0) {
			$data = array();
			foreach ($row as $rows) {
				//procedemos a obtener el detalle del traslado
				$detalleT = $this->traslados->get_detalle_traslado($rows->id_traslado);

				$menudrop = "<div class='btn-group'>
				<button data-toggle='dropdown' class='btn btn-success dropdown-toggle' aria-expanded='false'><i class='mdi mdi-menu' aria-haspopup='false'></i> Menu</button>
				<ul class='dropdown-menu dropdown-menu-right' x-placement='bottom-start'>";
				$menudrop .= "<li><a  data-toggle='modal' data-target='#viewModal' data-refresh='true'  role='button' class='detail' data-id=".$rows->id_traslado."><i class='mdi mdi-eye-check' ></i> Detalles</a></li>";
        if ($rows->estado==0) {
          // code...
          $menudrop .= "<li><a role='button' class='aceptar_traslado' id_traslado=".$rows->id_traslado." sucursal_destino=".$rows->id_sucursal_destino." sucursal_despacho=".$rows->id_sucursal_despacho."><i class='mdi mdi-check' ></i> Aceptar Traslado</a></li>";
          $menudrop .= "<li><a role='button' class='anular_traslado' id_traslado=".$rows->id_traslado."><i class='mdi mdi-delete-forever' ></i> Anular Traslado</a></li>";
        }
        else{

        }
        $menudrop .= "</ul></div>";

				//procedemos a validar si se mostraran los precios
				if($this->session->admin==1 || $this->session->super_admin==1){
					$validarCostos = "";
				}
				else {
					// code...
					$validarCostos = "hidden";
				}

				$data[] = array(
					$rows->id_traslado,
					$rows->fecha,
					$rows->suc1,
					$rows->suc2,
					"<div $validarCostos>".$rows->total."</div>",
          $rows->estados_p,
					$detalleT->detalle_t,
					$menudrop,
				);
			}
			$total = $this->traslados->total_rows();
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
  function aceptar_traslado(){
    $this->utils->begin();
    $id = $this->input->post("id");
    $despacho = $this->input->post("sucursal_despacho");
    $destino = $this->input->post("sucursal_destino");
    //echo $id;
    $rows = $this->traslados->get_detail_ci($id);
    $validacion =1;
    //var_dump($rows);
    foreach ($rows as $arrDetalle) {
      // code...
      //echo $arrDetalle->nombre."#";
      $id_producto = $arrDetalle->id_producto;
      $color = $arrDetalle->id_color;
      $cantidad = $arrDetalle->cantidad;
      $stock_data = $this->traslados->get_stock($id_producto,$color,$despacho);
      $newstock = ($stock_data->cantidad)-$cantidad;
      $respuestaDesp = $this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);

      //Carga en el destino
      $stock_data = $this->traslados->get_stock($id_producto,$color,$destino);
      $newstock = ($stock_data->cantidad)+$cantidad;
      $respuestaDest = $this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);

      if ($respuestaDesp==1&&$respuestaDest==1) {
        // code...
      }
      else {
        // code...
        $validacion=0;
        break;
      }
    }
    if ($validacion==1) {
      // code...
      $this->utils->update("traslado",array('estado' => '1', ),"id_traslado=".$id);

      $this->utils->commit();
      $xdatos["type"]="success";
      $xdatos['title']='Informaci??n';
      $xdatos["msg"]="Registo ingresado correctamente!";
    }
    else {
      // code...
      $this->utils->rollback();
      $xdatos["type"]="error";
      $xdatos['title']='Alerta';
      $xdatos["msg"]="Error al ingresar el registro";
    }
    echo json_encode($xdatos);
  }
  function anular_traslado(){
		if($this->input->method(TRUE) == "POST"){
			$id = $this->input->post("id");
			$this->utils->begin();
			$this->utils->update("traslado",array('estado' => '2', ),"id_traslado=$id");
			$this->utils->commit();
			$response["type"] = "success";
			$response["title"] = "Informaci??n";
			$response["msg"] = "Traslado anulado con ??xito!";
			echo json_encode($response);
		}
	}

	function change_state($id=-1){
		if($this->input->method(TRUE) == "GET"){
			$id = $this->uri->segment(3);
			$row = $this->ventas->get_one_row("ventas",array('id_venta' => $id, ));
			$rows = $this->ventas->get_detail_rows("estado",array('1' => 1, ));
			if($rows && $id!=""){
				$data = array(
					"row"=>$row,
					"rows"=>$rows,
				);
				$this->load->view("ventas/change_state.php",$data);
			}else{
				redirect('errorpage');
			}
		}
	}

	function agregar(){
		if($this->input->method(TRUE) == "GET"){
			$id_usuario=$this->session->id_usuario;
			$id_sucursal=$this->session->id_sucursal;
			$usuario_tipo =	$this->ventas->get_one_row("usuario", array('id_usuario' => $id_usuario,));
			if($usuario_tipo!=NULL){
	 		  if($usuario_tipo->admin==1 || $usuario_tipo->super_admin==1){
	 					$sucursales=$this->ventas->get_detail_rows("sucursales",array('1' => 1, ));
	 			}else {
	 					$sucursales=$this->ventas->get_detail_rows("sucursales", array('id_sucursal' => $id_sucursal,));
	 			}
	 	 }else {
	 	 	 	$sucursales=$this->ventas->get_detail_rows("sucursales",array('1' => 1, ));
	 	 }

			$data = array(
				"sucursal_envio"=>$sucursales,
				"sucursal"=>$this->ventas->get_detail_rows("sucursales",array('1' => 1, )),
				"id_sucursal" => $this->session->id_sucursal,
			);

			$extras = array(
				'css' => array(
				),
				'js' => array(
					"js/scripts/traslados.js"
				),
			);

			layout("traslado/guardar",$data,$extras);
		}
		else if($this->input->method(TRUE) == "POST"){
			$this->load->model("ProductosModel","productos");
			$this->utils->begin();
			$concepto = strtoupper($this->input->post("concepto"));
			$fecha = Y_m_d($this->input->post("fecha"));
			$instrucciones = $this->input->post("instrucciones");
			$total = $this->input->post("total");
			$data_ingreso = json_decode($this->input->post("data_ingreso"),true);
			$id_sucursal = $this->input->post("sucursal");
			$id_sucursal_destino = $this->input->post("sucursal_destino");
			$id_usuario = $this->session->id_usuario;
			$hora = date("H:i:s");

			$correlativo = $this->ventas->get_max_correlative('ven',$id_sucursal);

			$data = array(
				'fecha' => $fecha,
				'hora' => $hora,
				'concepto' => $concepto,
				'indicaciones ' => $instrucciones,
				'id_sucursal_despacho' => $id_sucursal,
				'id_sucursal_destino' => $id_sucursal_destino,
				'correlativo' => $correlativo,
				'total' => $total,
				'id_sucursal' => $id_sucursal,
				'id_usuario' => $id_usuario,
				'requiere_imei ' => 0,
				'imei_ingresado' => 0,
				'guia' => "",
			);

			$imei_required = false;

			$id_venta = $this->ventas->inAndCon('traslado',$data);
			if($id_venta!=NULL){

				foreach ($data_ingreso as $fila)
				{
					$id_producto = $fila['id_producto'];
					$costo = $fila['costo'];
					$cantidad = $fila['cantidad'];
					$precio_sugerido = $fila['precio_sugerido'];
					$subtotal = $fila['subtotal'];
					$color = $fila['color'];
					$estado = $fila['est'];

					//Descarga Del origen
					$form_data = array(
						'id_traslado' => $id_venta,
						'id_producto' => $id_producto,
						'id_color' => $color,
						'costo' => $costo,
						'precio' => $precio_sugerido,
						'cantidad' => $cantidad,
						'subtotal' => $subtotal,
						'condicion' => $estado,
						'garantia' =>  $this->ventas->getGarantia($id_producto,$estado),
						'carga' => 0,
					);
					$id_detalle = $this->ventas->inAndCon('traslado_detalle',$form_data);
					$stock_data = $this->ventas->get_stock($id_producto,$color,$id_sucursal);
					$newstock = ($stock_data->cantidad)-$cantidad;
					$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);

					//Carga en el destino
					$stock_data = $this->ventas->get_stock($id_producto,$color,$id_sucursal_destino);
					$newstock = ($stock_data->cantidad)+$cantidad;
					$this->utils->update("stock",array('cantidad' => $newstock, ),"id_stock=".$stock_data->id_stock);


					if ($this->ventas->has_imei_required($id_producto)) {
						// code...
						$imei_required=true;
					}
				}

				if ($imei_required) {
					// code...
					$this->utils->update("ventas",array('requiere_imei' => 1, ),"id_venta=$id_venta");
				}
				$this->utils->commit();
				$xdatos["type"]="success";
				$xdatos['title']='Informaci??n';
				$xdatos["msg"]="Registo ingresado correctamente!";
			}
			else {
				$this->utils->rollback();
				$xdatos["type"]="error";
				$xdatos['title']='Alerta';
				$xdatos["msg"]="Error al ingresar el registro";
			}


			echo json_encode($xdatos);
		}
	}
  function detalle($id=-1){
    if($this->input->method(TRUE) == "GET"){
      $id = $this->uri->segment(3);
      $rows = $this->traslados->get_detail_ci($id);
      //procedemos a validar si se mostraran los precios
      if($this->session->admin==1 || $this->session->super_admin==1){
        $validarCostos = "";
      }
      else {
        // code...
        $validarCostos = "hidden";
      }
      if($rows && $id!=""){
        $data = array(
          "rows"=>$rows,
          "process" => "traslado",
          "ocultar" => $validarCostos
        );
        $this->load->view("inventario/ver_detalle.php",$data);
      }else{
        redirect('errorpage');
      }
    }
  }
}

/* End of file Ventas.php */
