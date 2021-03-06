<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reportes extends CI_Controller
{

	/*
	Enviroment variables
	*/
	private $table = "colores";
	private $pk = "id_color";

	function __construct()
	{
		parent::__construct();
		$this->load->Model("ColoresModel", "colores");
		$this->load->helper("upload_file");
		$this->load->model('UtilsModel', "utils");
		$this->load->model("ReportesModel", "reportes");
		$this->load->model("InventarioModel", "inventario");
		$this->load->model("ProductosModel", "productos");
		$this->load->model("TrasladoModel", "traslado");
	}

	function agregar()
	{

		if ($this->input->method(TRUE) == "GET") {
			$generarReportes = $this->reportes->get_reportes();
			$id_sucursal = 1;
			$stock = $this->productos->get_stock_r($id_sucursal);
			//var_dump($generarReportes);
			$data = array(
				"productos" => $stock,
				"reportes" => $generarReportes,
				"sucursal" => $this->inventario->get_detail_rows("sucursales", array('1' => 1,)),
				"id_sucursal" => $this->session->id_sucursal,
			);
			$extras = array(
				'css' => array(),
				'js' => array(
					"js/scripts/reportes.js",
				),
			);
			layout("reports/generar_reportes", $data, $extras);
		}
	}
	function reporte_existencias()
	{

		if ($this->input->method(TRUE) == "GET") {
			$generarReportes = $this->reportes->get_reportes();
			$id_sucursal = 1;
			$stock = $this->productos->get_stock_r($id_sucursal);
			$categorias = $this->productos->get_categorias();
			//var_dump($categorias);
			//var_dump($generarReportes);
			$data = array(
				"productos" => $stock,
				"categorias" => $categorias,
				"reportes" => $generarReportes,
				"sucursal" => $this->inventario->get_detail_rows("sucursales", array('1' => 1,)),
				"id_sucursal" => $this->session->id_sucursal,
			);
			$extras = array(
				'css' => array(),
				'js' => array(
					"js/scripts/reportes.js",
				),
			);
			layout("reports/reporte_existencias", $data, $extras);
		}
	}
	function get_stock_sucursal()
	{
		$id_sucursal = $this->input->post("id");
		$stock = $this->productos->get_stock_r($id_sucursal);
		$option = "<option value=''>Seleccione...</option>";
		foreach ($stock as $arrP) {
			// code...
			$option .= "<option value='" . $arrP->id_producto . "' color='" . $arrP->id_color . "'>$arrP->codigo_barra $arrP->marca $arrP->nombre $arrP->modelo $arrP->color</option>";
		}
		echo $option;
	}
	function generar()
	{
		if ($this->input->method(TRUE) == "GET") {

			$id = $this->uri->segment(3);
			//echo $id;
			$tipoReporte = $this->uri->segment(4);
			$fechaI = $this->uri->segment(5);
			$fechaF = $this->uri->segment(6);
			$sucursal = $this->uri->segment(7); //sucursal
			//procedemos a obtener los datos de la sucursal
			$arrSucursal = $this->reportes->get_row_sucursal($sucursal);
			$this->load->library('Report');
			//procedemos a obtener el tipo de reporte
			$obtenerTipo = $this->reportes->get_tipo_reporte($id);
			//var_dump($obtenerTipo);
			$pdf = $this->report->getInstance('P', 'mm', 'Letter');
			$logo = "assets/img/logo.png";
			$pdf->SetMargins(6, 10);
			$pdf->SetLeftMargin(5);
			$pdf->AliasNbPages();
			$pdf->SetAutoPageBreak(true, 15);
			$pdf->AliasNbPages();
			$data = array("empresa" => "Jah", "imagen" => $logo, 'fecha' => "14-10-1998", 'titulo' => $obtenerTipo->nombre);
			$pdf->setear($data);
			$pdf->addPage();
			$pdf->SetFont('Arial', 'B', 10);

			$l = array(
				's' => 10,
				'con' => 180,
			);
			$array_data = array(
				array('', $l['s'], "C"),
				array($arrSucursal->nombre . " " . $arrSucursal->direccion, $l['con'], "C"),
			);
			$pdf->LineWrite($array_data);
			$pdf->LN(5);

			if ($tipoReporte == 0) {
				// general...
				if ($obtenerTipo->parametro == "report_utilidades") {
					$l = array(
						's' => 10,
						'con' => 130,
						'tot' => 60
					);
					$array_data = array(
						array('', $l['s'], "C"),
						array('Concepto', $l['con'], "C"),
						array('Total', $l['tot'], "C"),
					);
					$pdf->LineWriteB($array_data);

					$data = $this->reportes->get_totales(Y_m_d($fechaI), Y_m_d($fechaF), $sucursal);
					//var_dump($data);
					$ventasTotales = $data->total - $data->descuento;
					$ventasNetas = $ventasTotales - $data->costo;
					$pdf->SetFont('Arial', '', 10);
					$array_data = array(
						array('', $l['s'], "C"),
						array("Ventas Totales", $l['con'], "L"),
						array("$" . number_format(($ventasTotales), 2, '.', ''), $l['tot'], "R")
					);
					$pdf->LineWriteB($array_data);

					$array_data = array(
						array('', $l['s'], "C"),
						array("(-)Costo de Ventas", $l['con'], "L"),
						array("$" . number_format($data->costo, 2, '.', ''), $l['tot'], "R")
					);
					$pdf->LineWriteB($array_data);

					$pdf->SetFont('Arial', 'B', 10);
					$array_data = array(
						array('', $l['s'], "C"),
						array("(=)Ventas Netas", $l['con'], "L"),
						array("$" . number_format($ventasNetas, 2, '.', ''), $l['tot'], "R")
					);
					$pdf->LineWriteB($array_data);
				} //fin de reporte utilidades
				if ($obtenerTipo->parametro == "report_taller") {
					$totalAcum = 0;
					$costoAcum = 0;
					$cantidadAcum = 0;

					$estados = ["2","4"];
					$descripcionestados = ["Finalizados", "En taller"];

					$l = array(
						's' => 10,
						'nom' => 40,
						'can' => 80,
						'cos' => 40,
						'tot' => 25
					);

					foreach ($estados as $key => $estado) {

					$array_data = array(
						array('', 10, "C"),
						array(" Trabajos " . $descripcionestados[$key], 180, "C"),
					);
					$pdf->LineWrite($array_data);
					$pdf->LN(1);

					$array_data = array(
						array('', $l['s'], "C"),
						array('Cliente', $l['nom'], "C"),
						array('Concepto', $l['can'], "C"),
						array('Encargado(s)', $l['cos'], "C"),
						array('Total', $l['tot'], "C"),
					);

						
					$pdf->LineWriteB($array_data);

					$data = $this->reportes
					->get_trabajos_taller_rango(Y_m_d($fechaI), Y_m_d($fechaF), $sucursal, $estado);
					//var_dump($data);
					$pdf->SetFont('Arial', '', 10);
					if ($data == 0) {
						// code...
						$array_data = array(
							array('', $l['s'], "C"),
							array('sin resultados...', $l['nom'], "L"),
							array('', $l['can'], "R"),
							array('', $l['cos'], "R"),
							array('', $l['tot'], "R"),
						);
						$pdf->LineWriteB($array_data);

						$pdf->SetFont('Arial', 'B', 10);
						$pdf->LN(5);
					} else {
						// code...
						foreach ($data as $arrData) {
							// code...
							$totalAcum += $arrData->total;
							// $costoAcum += $arrData->total;
							$cantidadAcum += 1;
							$array_data = array(
								array('', $l['s'], "C"),
								array($arrData->cnombre, $l['nom'], "L"),
								array($arrData->concepto, $l['can'], "L"),
								array($arrData->encargado, $l['cos'], "L"),
								array("$" . number_format($arrData->total, 2, '.', ''), $l['tot'], "R"),
							);
							$pdf->LineWriteB($array_data);
						}

						$pdf->SetFont('Arial', 'B', 10);
						$array_data = array(
							array('', $l['s'], "C"),
							array('', $l['nom'], "L"),
							array('', $l['can'], "R"),
							array("Total", $l['cos'], "R"),
							array("$" . number_format(($totalAcum), 2, '.', ''), $l['tot'], "R")
						);
						$pdf->LineWriteB($array_data);

						$pdf->LN(5);
						}
					}
				}
				
			} elseif ($tipoReporte == 1) {
				// especifico...
				if ($obtenerTipo->parametro == "report_utilidades") {
					$totalAcum = 0;
					$costoAcum = 0;
					$cantidadAcum = 0;
					$l = array(
						's' => 10,
						'nom' => 110,
						'can' => 15,
						'cos' => 30,
						'tot' => 30
					);
					$array_data = array(
						array('', $l['s'], "C"),
						array('Nombre', $l['nom'], "C"),
						array('Cant.', $l['can'], "C"),
						array('Costo', $l['cos'], "C"),
						array('Subtotal', $l['tot'], "C"),
					);
					$pdf->LineWriteB($array_data);

					$data = $this->reportes->get_ventas_rango(Y_m_d($fechaI), Y_m_d($fechaF), $sucursal);
					//var_dump($data);
					$pdf->SetFont('Arial', '', 10);
					if ($data == 0) {
						// code...
						$array_data = array(
							array('', $l['s'], "C"),
							array('sin resultados...', $l['nom'], "L"),
							array('', $l['can'], "R"),
							array('', $l['cos'], "R"),
							array('', $l['tot'], "R"),
						);
						$pdf->LineWriteB($array_data);
					} else {
						// code...
						foreach ($data as $arrData) {
							// code...
							$totalAcum += $arrData['subtotal'];
							$costoAcum += $arrData['costo'];
							$cantidadAcum += $arrData['cantidad'];
							$array_data = array(
								array('', $l['s'], "C"),
								array($arrData['nombre'], $l['nom'], "L"),
								array($arrData['cantidad'], $l['can'], "R"),
								array("$" . number_format($arrData['costo'], 2, '.', ''), $l['cos'], "R"),
								array("$" . number_format($arrData['subtotal'], 2, '.', ''), $l['tot'], "R"),
							);
							$pdf->LineWriteB($array_data);
						}

						$pdf->SetFont('Arial', 'B', 10);
						$array_data = array(
							array('', $l['s'], "C"),
							array("Total", $l['nom'], "L"),
							array($cantidadAcum, $l['can'], "R"),
							array("$" . number_format(($costoAcum), 2, '.', ''), $l['cos'], "R"),
							array("$" . number_format(($totalAcum), 2, '.', ''), $l['tot'], "R")
						);
						$pdf->LineWriteB($array_data);
					}
				} //fin de reporte utilidades
			}
		} //emergencias reportadas
		$pdf->Output();
		//echo $id."#";
	}
	function generarExist()
	{
		if ($this->input->method(TRUE) == "GET") {

			$id = $this->uri->segment(3);
			$sucursal = $this->uri->segment(4); //sucursal
			$categoria = $this->uri->segment(5); //categoria
			$mostrarCostos = $this->uri->segment(6); //mostrar costos
			//procedemos a obtener los datos de la sucursal
			$arrSucursal = $this->reportes->get_row_sucursal($sucursal);
			$this->load->library('Report');
			$pdf = $this->report->getInstance('P', 'mm', 'Letter');
			$logo = "assets/img/logo.png";
			$pdf->SetMargins(6, 10);
			$pdf->SetLeftMargin(5);
			$pdf->AliasNbPages();
			$pdf->SetAutoPageBreak(true, 15);
			$pdf->AliasNbPages();
			$data = array("empresa" => "Jah", "imagen" => $logo, 'fecha' => "14-10-1998", 'titulo' => "Reporte de Existencias");
			$pdf->setear($data);
			$pdf->addPage();
			$pdf->SetFont('Arial', 'B', 10);

			$l = array(
				's' => 10,
				'con' => 180,
			);
			$array_data = array(
				array('', $l['s'], "C"),
				array($arrSucursal->nombre . " " . $arrSucursal->direccion, $l['con'], "C"),
			);
			$pdf->LineWrite($array_data);
			$pdf->LN(5);

			$totalAcum = 0;
			$costoAcum = 0;
			$cantidadAcum = 0;
			$totalCosto = 0;
			$l = array(
				's' => 10,
				'cod' => 40,
				'nom' => 95,
				'can' => 15,
				'ind' => 15,
				'cos' => 20
			);

			$array_data = array(
				array('', $l['s'], "C"),
				array('Codigo', $l['cod'], "C"),
				array('Nombre', $l['nom'], "C"),
				array('Cant.', $l['can'], "C"),
				array('Costo Ind.', $l['ind'], "C"),
				array('Subtotal.', $l['cos'], "C"),
			);
			$pdf->LineWriteB($array_data);

			//procedemos a verificar si el reporte es por categoria o un consolidado
			$data = $this->reportes->get_existencias($sucursal, $categoria);

			//var_dump($data);
			$pdf->SetFont('Arial', '', 10);
			if ($data == 0) {
				// code...
				$array_data = array(
					array('', $l['s'], "C"),
					array('', $l['cod'], "R"),
					array('sin resultados...', $l['nom'], "L"),
					array('', $l['can'], "R"),
					array('', $l['cos'], "R"),
				);
				$pdf->LineWriteB($array_data);
			} else {
				// code...
				foreach ($data as $arrData) {
					// code...
					$totalCosto += ($arrData->costo_c_iva * $arrData->cantidad);
					$cantidadAcum += $arrData->cantidad;

					if ($mostrarCostos == 1) {
						$array_data = array(
							array('', $l['s'], "C"),
							array($arrData->codigo_barra, $l['cod'], "L"),
							array($arrData->cadena, $l['nom'], "L"),
							array($arrData->cantidad, $l['can'], "R"),
							array(number_format($arrData->costo_c_iva, 2, '.', ''), $l['ind'], "R"),
							array(number_format(($arrData->costo_c_iva * $arrData->cantidad), 2, '.', ''), $l['cos'], "R"),
						);
					} else {
						$array_data = array(
							array('', $l['s'], "C"),
							array($arrData->codigo_barra, $l['cod'], "L"),
							array($arrData->cadena, $l['nom'], "L"),
							array($arrData->cantidad, $l['can'], "R"),
							array(number_format(0, 2, '.', ''), $l['ind'], "R"),
							array(number_format((0), 2, '.', ''), $l['cos'], "R"),
						);
					}

					$pdf->LineWriteB($array_data);
				}
			}

			$pdf->SetFont('Arial', 'B', 10);

			if ($mostrarCostos == 1) {
				$array_data = array(
					array('', $l['s'], "C"),
					array('TOTAL', $l['cod'], "R"),
					array('', $l['nom'], "L"),
					array('', $l['can'], "L"),
					array('', $l['ind'], "L"),
					array(number_format($totalCosto, 2), $l['cos'], "R"),
				);
			} else {
				$array_data = array(
					array('', $l['s'], "C"),
					array('TOTAL', $l['cod'], "R"),
					array('', $l['nom'], "L"),
					array('', $l['can'], "L"),
					array('', $l['ind'], "L"),
					array(number_format(0, 2), $l['cos'], "R"),
				);
			}
			$pdf->LineWriteB($array_data);
		} //emergencias reportadas
		$pdf->Output();
		//echo $id."#";
	}
	function generar_kardex()
	{
		if ($this->input->method(TRUE) == "GET") {
			$fechaI = $this->uri->segment(3);
			$fechaF = $this->uri->segment(4);
			$sucursal = $this->uri->segment(5); //sucursal
			$id = $this->uri->segment(6); //id producto
			$color = $this->uri->segment(7); //id color
			//procedemos a obtener los datos del producto
			$datosP = $this->productos->get_stock_data($id, $color);
			$encabezadoP = $datosP->marca . " " . $datosP->nombre . " " . $datosP->modelo . " " . $datosP->color;
			//procedemos a obtener los datos de la sucursal
			if ($sucursal == 0) {
				$sucursalNombre = "SUCURSAL";
				$sucursalDireccion = "";
			} else {
				$arrSucursal = $this->reportes->get_row_sucursal($sucursal);
				$sucursalNombre = $arrSucursal->nombre;
				$sucursalDireccion = $arrSucursal->direccion;
			}
			$this->load->library('Report');
			//procedemos a obtener el tipo de reporte
			//var_dump($obtenerTipo);
			$pdf = $this->report->getInstance('L', 'mm', 'Letter');
			$logo = "assets/img/logo.png";
			$pdf->SetMargins(6, 10);
			$pdf->SetLeftMargin(5);
			$pdf->AliasNbPages();
			$pdf->SetAutoPageBreak(true, 15);
			$pdf->AliasNbPages();
			$data = array("empresa" => "Jah", "imagen" => $logo, 'fecha' => "14-10-1998", 'titulo' => $encabezadoP . " - Kardex");
			$pdf->setear($data);
			$pdf->addPage();
			$pdf->SetFont('Arial', 'B', 10);

			$l = array(
				's' => 10,
				'con' => 250,
			);
			$array_data = array(
				array('', $l['s'], "C"),
				array($sucursalNombre . " " . $sucursalDireccion, $l['con'], "C"),
			);
			$pdf->LineWrite($array_data);
			$pdf->LN(5);

			//procedemos a generar el kardex del producto
			$kardex = $this->reportes->get_kardex($id, $color, $sucursal, $fechaI, $fechaF);
			//var_dump($kardex);
			$pdf->SetFont('Arial', 'B', 7);
			if ($kardex == 0) {
				// code...
				$l = array(
					's' => 10,
					'fec' => 30,
					'pro' => 55,
					'tip' => 30,
					'con' => 45,
					'ent' => 45,
					'sal' => 45,
					'exi' => 45
				);
				$array_data = array(
					array('', $l['s'], "C"),
					array('', $l['fec'], "R"),
					array('sin resultados...', $l['pro'], "L"),
					array('', $l['ent'], "R"),
					array('', $l['sal'], "R"),
					array('', $l['exi'], "R"),
				);
			} else {
				// code...
				$l = array(
					's' => 10,
					'fec' => 30,
					'pro' => 12,
					'tip' => 30,
					'con' => 43,
					'ent' => 45,
					'sal' => 45,
					'exi' => 45
				);
				$array_data = array(
					array('', $l['s'], "C"),
					array('FECHA', $l['fec'], "C"),
					array('DOC', $l['pro'], "C"),
					array('TIPO', $l['tip'], "C"),
					array('SUCURSAL', $l['con'], "C"),
					array('ENTRADAS', $l['ent'], "C"),
					array('SALIDAS', $l['sal'], "C"),
					array('EXISTENCIAS', $l['exi'], "C"),
				);
				$pdf->LineWriteB($array_data);
				$pdf->SetFont('Arial', '', 7);

				//$existencias
				//var_dump($kardex);
				$l = array(
					's' => 10,
					'fec' => 30,
					'pro' => 12,
					'tip' => 30,
					'con' => 43,
					'can' => 15,
					'cos' => 15,
					'tot' => 15,
					'rel' => 60
				);
				$pdf->SetFont('Arial', 'B', 6);
				$array_data = array(
					array('', $l['s'], "C"),
					array("", $l['fec'], "L"),
					array("", $l['pro'], "C"),
					array("", $l['tip'], "C"),
					array("", $l['con'], "C"),
					array("UNIDAD", $l['can'], "C"),
					array("COSTO", $l['cos'], "C"),
					array("SUBTOTAL", $l['tot'], "C"),
					array("UNIDAD", $l['can'], "C"),
					array("COSTO", $l['cos'], "C"),
					array("SUBTOTAL", $l['tot'], "C"),
					array("UNIDAD", $l['can'], "C"),
					array("COSTO", $l['cos'], "C"),
					array("SUBTOTAL", $l['tot'], "C"),
					//array("",$l['rel'],"R"),
				);
				$pdf->LineWriteB($array_data);
				$existencias = 0;
				$costoK = 0;
				$subtotalK = 0;
				$pdf->SetFont('Arial', '', 7);
				foreach ($kardex as $arrKardex) {
					// code...
					$l = array(
						's' => 10,
						'fec' => 30,
						'pro' => 12,
						'tip' => 30,
						'con' => 43,
						'can' => 15,
						'cos' => 15,
						'tot' => 15,
						'rel' => 45
					);

					if ($arrKardex['movimiento'] == "carga") {
						// code...
						$existencias += $arrKardex['cantidad'];
						$subtotalK += $arrKardex['cantidad'] * $arrKardex['costo'];
						$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];

						//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
						if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
							// code...
							$array_data = array(
								array('', $l['s'], "C"),
								array($arrKardex['fecha'], $l['fec'], "L"),
								array($arrKardex['correlativo'], $l['pro'], "C"),
								array(strtoupper($arrKardex['movimiento']), $l['tip'], "C"),
								array(strtoupper(''), $l['con'], "C"),
								array($arrKardex['cantidad'], $l['can'], "R"),
								array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
								array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
								array("", $l['rel'], "R"),
								array($existencias, $l['can'], "R"),
								array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
								array("$" . number_format($subtotalK, 2), $l['tot'], "R"),
								//array("",$l['rel'],"R"),
							);
							$pdf->LineWriteB($array_data);
						} else {
							// code...
						}
					} else if ($arrKardex['movimiento'] == "compra") {
						// code...
						$existencias += $arrKardex['cantidad'];
						$subtotalK += $arrKardex['cantidad'] * $arrKardex['costo'];
						$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];

						//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
						if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
							// code...
							$array_data = array(
								array('', $l['s'], "C"),
								array($arrKardex['fecha'], $l['fec'], "L"),
								array($arrKardex['correlativo'], $l['pro'], "C"),
								array(strtoupper($arrKardex['movimiento']), $l['tip'], "C"),
								array(strtoupper(''), $l['con'], "C"),
								array($arrKardex['cantidad'], $l['can'], "R"),
								array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
								array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
								array("", $l['rel'], "R"),
								array($existencias, $l['can'], "R"),
								array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
								array("$" . number_format($subtotalK, 2), $l['tot'], "R"),
								//array("",$l['rel'],"R"),
							);
							$pdf->LineWriteB($array_data);
						} else {
							// code...
						}
					} else if ($arrKardex['movimiento'] == "descarga") {
						// code...
						$existencias -= $arrKardex['cantidad'];
						$subtotalK -= $arrKardex['cantidad'] * $arrKardex['costo'];
						$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];
						//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
						if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
							// code...
							$array_data = array(
								array('', $l['s'], "C"),
								array($arrKardex['fecha'], $l['fec'], "L"),
								array($arrKardex['correlativo'], $l['pro'], "C"),
								array(strtoupper($arrKardex['movimiento']), $l['tip'], "C"),
								array(strtoupper(''), $l['con'], "C"),
								array("", $l['rel'], "R"),
								array($arrKardex['cantidad'], $l['can'], "R"),
								array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
								array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
								array($existencias, $l['can'], "R"),
								array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
								array("$" . number_format($subtotalK, 2), $l['tot'], "R"),
								//array("",$l['rel'],"R"),
							);
							$pdf->LineWriteB($array_data);
						} else {
							// code...
						}
					} else if ($arrKardex['movimiento'] == "ajuste") {
						// code...
						if ($arrKardex['tipo'] == "resta") {
							// code...
							$existencias = $arrKardex['cantidad'];
							$subtotalK = $arrKardex['cantidad'] * $arrKardex['costo'];
							$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];
							//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
							if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
								// code...
								$array_data = array(
									array('', $l['s'], "C"),
									array($arrKardex['fecha'], $l['fec'], "L"),
									array($arrKardex['correlativo'], $l['pro'], "C"),
									array(strtoupper($arrKardex['movimiento']), $l['tip'], "C"),
									array(strtoupper(''), $l['con'], "C"),
									array("", $l['rel'], "R"),
									array($arrKardex['cantidad'], $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
									array($arrKardex['cantidad'], $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
									//array("",$l['rel'],"R"),
								);
								$pdf->LineWriteB($array_data);
							} else {
								// code...
							}
						} else {
							// code...
							$existencias = $arrKardex['cantidad'];
							$subtotalK = $arrKardex['cantidad'] * $arrKardex['costo'];
							$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];
							//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
							if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
								// code...
								$array_data = array(
									array('', $l['s'], "C"),
									array($arrKardex['fecha'], $l['fec'], "L"),
									array($arrKardex['correlativo'], $l['pro'], "C"),
									array(strtoupper($arrKardex['movimiento']), $l['tip'], "C"),
									array(strtoupper(''), $l['con'], "C"),
									array($arrKardex['cantidad'], $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
									array("", $l['rel'], "R"),
									array($arrKardex['cantidad'], $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
									//array("",$l['rel'],"R"),
								);
								$pdf->LineWriteB($array_data);
							} else {
								// code...
							}
						}
					} else if ($arrKardex['movimiento'] == "traslado") {
						// code...
						if ($arrKardex['tipo'] == "resta") {
							// code...
							$existencias -= $arrKardex['cantidad'];
							$subtotalK -= $arrKardex['cantidad'] * $arrKardex['costo'];
							$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];
							//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
							if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
								// code...
								$array_data = array(
									array('', $l['s'], "C"),
									array($arrKardex['fecha'], $l['fec'], "L"),
									array($arrKardex['correlativo'], $l['pro'], "C"),
									array(strtoupper($arrKardex['movimiento'] . "(SALIDA)"), $l['tip'], "C"),
									array(strtoupper($arrKardex['sucursal']), $l['con'], "C"),
									array("", $l['rel'], "R"),
									array($arrKardex['cantidad'], $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
									array($existencias, $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalK, 2), $l['tot'], "R"),
									//array("",$l['rel'],"R"),
								);
								$pdf->LineWriteB($array_data);
							} else {
								// code...
							}
						} else {
							// code...
							$existencias += $arrKardex['cantidad'];
							$subtotalK += $arrKardex['cantidad'] * $arrKardex['costo'];
							$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];
							//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
							if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
								// code...
								$array_data = array(
									array('', $l['s'], "C"),
									array($arrKardex['fecha'], $l['fec'], "L"),
									array($arrKardex['correlativo'], $l['pro'], "C"),
									array(strtoupper($arrKardex['movimiento'] . "(ENTRADA)"), $l['tip'], "C"),
									array(strtoupper($arrKardex['sucursal_d']), $l['con'], "C"),
									array($arrKardex['cantidad'], $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
									array("", $l['rel'], "R"),
									array($existencias, $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalK, 2), $l['tot'], "R"),
									//array("",$l['rel'],"R"),
								);
								$pdf->LineWriteB($array_data);
							} else {
								// code...
							}
						}
					} else if ($arrKardex['movimiento'] == "ventas") {
						// code...
						if ($arrKardex['tipo'] == "resta") {
							// code...
							$existencias -= $arrKardex['cantidad'];
							$subtotalK -= $arrKardex['cantidad'] * $arrKardex['costo'];
							$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];
							//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
							if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
								// code...
								$array_data = array(
									array('', $l['s'], "C"),
									array($arrKardex['fecha'], $l['fec'], "L"),
									array($arrKardex['correlativo'], $l['pro'], "C"),
									array(strtoupper($arrKardex['movimiento']), $l['tip'], "C"),
									array(strtoupper(''), $l['con'], "C"),
									array("", $l['rel'], "R"),
									array($arrKardex['cantidad'], $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
									array($existencias, $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalK, 2), $l['tot'], "R"),
									//array("",$l['rel'],"R"),
								);
								$pdf->LineWriteB($array_data);
							} else {
								// code...
							}
						} else {
							// code...
							$existencias += $arrKardex['cantidad'];
							$subtotalK += $arrKardex['cantidad'] * $arrKardex['costo'];
							$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];
							//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
							if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
								// code...
								$array_data = array(
									array('', $l['s'], "C"),
									array($arrKardex['fecha'], $l['fec'], "L"),
									array($arrKardex['correlativo'], $l['pro'], "C"),
									array(strtoupper($arrKardex['movimiento']), $l['tip'], "C"),
									array(strtoupper(''), $l['con'], "C"),
									array($arrKardex['cantidad'], $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
									array("", $l['rel'], "R"),
									array($existencias, $l['can'], "R"),
									array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
									array("$" . number_format($subtotalK, 2), $l['tot'], "R"),
									//array("",$l['rel'],"R"),
								);
								$pdf->LineWriteB($array_data);
							} else {
								// code...
							}
						}
					} else if ($arrKardex['movimiento'] == "devoluciones") {
						// code...
						$existencias += $arrKardex['cantidad'];
						$subtotalK += $arrKardex['cantidad'] * $arrKardex['costo'];
						$subtotalInd = $arrKardex['cantidad'] * $arrKardex['costo'];
						//echo $$arrKardex['fechaEval'].">=".$fechaI."&&".$arrKardex['fechaEval']."<=".$fechaF;
						if ($arrKardex['fechaEval'] >= Y_m_d($fechaI) && $arrKardex['fechaEval'] <= Y_m_d($fechaF)) {
							// code...
							$array_data = array(
								array('', $l['s'], "C"),
								array($arrKardex['fecha'], $l['fec'], "L"),
								array($arrKardex['correlativo'], $l['pro'], "C"),
								array(strtoupper($arrKardex['movimiento']), $l['tip'], "C"),
								array(strtoupper(''), $l['con'], "C"),
								array($arrKardex['cantidad'], $l['can'], "R"),
								array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
								array("$" . number_format($subtotalInd, 2), $l['tot'], "R"),
								array("", $l['rel'], "R"),
								array($existencias, $l['can'], "R"),
								array("$" . number_format($arrKardex['costo'], 2), $l['cos'], "R"),
								array("$" . number_format($subtotalK, 2), $l['tot'], "R"),
								//array("",$l['rel'],"R"),
							);
							$pdf->LineWriteB($array_data);
						} else {
							// code...
						}
					}
				}
			}
		}
		$pdf->Output();
		//echo $id."#";
	}

	public function reporte_movimientos_caja()
	{
		if ($this->input->method(TRUE) == "GET") {
			$generarReportes = $this->reportes->get_reportes();
			$id_sucursal = 1;
			//$stock = $this->productos->get_stock_r($id_sucursal);
			//var_dump($generarReportes);
			$data = array(
				//"productos"=>$stock,
				"reportes" => $generarReportes,
				"sucursal" => $this->inventario->get_detail_rows("sucursales", array('1' => 1,)),
				"id_sucursal" => $this->session->id_sucursal,
			);
			$extras = array(
				'css' => array(),
				'js' => array(
					"js/scripts/reportes.js",
				),
			);
			layout("reports/generar_reportes_movimientos", $data, $extras);
		}
	}

	public function generarMovimiento()
	{
		$this->load->library('Report');
		$sucursal = $this->uri->segment(3);
		$tipo = $this->uri->segment(4);
		$fechaI = $this->uri->segment(5);
		$fechaIni = date("Y-m-d", strtotime($fechaI));
		$fechaF = $this->uri->segment(6);
		$fechaFin = date("Y-m-d", strtotime($fechaF));

		$arrSucursal = $this->reportes->get_row_sucursal($sucursal);
		$sucursalNombre = $arrSucursal->nombre;
		$sucursalDireccion = $arrSucursal->direccion;
		//procedemos a obtener el tipo de reporte
		//var_dump($obtenerTipo);
		$pdf = $this->report->getInstance('P', 'mm', 'Letter');
		$logo = "assets/img/logo.png";
		$pdf->SetMargins(6, 10);
		$pdf->SetLeftMargin(5);
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(true, 15);
		$pdf->AliasNbPages();
		$data = array("empresa" => "Jah", "imagen" => $logo, 'fecha' => "14-10-1998", 'titulo' => "");
		$pdf->setear($data);
		$pdf->addPage();
		$pdf->SetFont('Arial', 'B', 10);

		$l = array(
			's' => 10,
			'con' => 195,
		);
		$array_data = array(
			array('', $l['s'], "C"),
			array("GESTION DE MOVIMIENTOS DE ENTRADA Y SALIDA", $l['con'], "C"),
		);
		$pdf->LineWrite($array_data);
		$array_data = array(
			array('', $l['s'], "C"),
			array($sucursalNombre . " - " . $sucursalDireccion, $l['con'], "C"),
		);
		$pdf->LineWrite($array_data);
		$pdf->LN(5);

		$row = $this->reportes->get_movimientos($tipo, $sucursal, $fechaIni, $fechaFin);

		$l = array(
			's' => 10,
			'con' => 140,
			'tip' => 30,
			'mon' => 20,
		);
		$pdf->SetFont('Arial', 'B', 10);
		$array_data = array(
			array('', $l['s'], "C"),
			array("CONCEPTO", $l['con'], "L"),
			array("TIPO", $l['tip'], "L"),
			array("MONTO", $l['mon'], "L"),
		);
		$pdf->LineWriteB($array_data);
		$pdf->SetFont('Arial', '', 10);
		if (is_null($row)) {
			//si no se encuentra ningun movimiento
			$array_data = array(
				array('', $l['s'], "C"),
				array("SIN RESULTADOS...", $l['con'], "L"),
				array("", $l['tip'], "L"),
				array("", $l['mon'], "L"),
			);
			$pdf->LineWriteB($array_data);
		} else {
			foreach ($row as $arrMovimientos) {
				# procedemos a agregar los movimientos de entrada y de salida...
				$array_data = array(
					array('', $l['s'], "C"),
					array($arrMovimientos->concepto, $l['con'], "L"),
					array(($arrMovimientos->entrada == 1) ? 'ENTRADA' : 'SALIDA', $l['tip'], "L"),
					array(number_format($arrMovimientos->valor, 2), $l['mon'], "R"),
				);
				$pdf->LineWriteB($array_data);
			}
		}
		$pdf->Output();
	} // FIN Generar Movimiento

	function traslados()
	{

		if ($this->input->method(TRUE) == "GET") {
			$id_sucursal = 1;
			//var_dump($categorias);
			//var_dump($generarReportes);
			$data = array(
				"sucursal" => $this->inventario->get_detail_rows("sucursales", array('1' => 1,)),
				"id_sucursal" => $this->session->id_sucursal,
			);
			$extras = array(
				'css' => array(),
				'js' => array(
					"js/scripts/reportes.js",
				),
			);
			layout("reports/generar_reporte_traslados", $data, $extras);
		}
	} //FIN traslados()

	function reporte_traslado_rango()
	{
		$fechaInicio = $this->uri->segment(3); //sucursal de origen
		$fechaFin = $this->uri->segment(4); //sucursal de destino
		$sucursalDespacho = $this->uri->segment(5); //fecha de inicio
		$sucursalDestino = $this->uri->segment(6); //fecha de inicio

		$fechaInicio = date("Y-m-d", strtotime($fechaInicio));
		$fechaFin = date("Y-m-d", strtotime($fechaFin));

		//procedemos a obtener los datos de la sucursal
		$arrSucursalOrigen = $this->reportes->get_row_sucursal($sucursalDespacho);
		$arrSucursalDestino = $this->reportes->get_row_sucursal($sucursalDestino);

		$this->load->library('Report');
		$pdf = $this->report->getInstance('P', 'mm', 'Letter');
		$logo = "assets/img/logo.png";
		$pdf->SetMargins(1, 10);
		$pdf->SetLeftMargin(5);
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(true, 15);
		$pdf->AliasNbPages();
		$data = array(
			"empresa" => "Jah",
			"imagen" => $logo,
			"fecha" => date("d-m-Y"),
			"titulo" => "Reporte de Traslados",
			"nombre_empresa" => "Jah - TSIDKENNU",
			"telefonos" => "",
			"razon_social" => "",
		);
		$pdf->setear($data);
		$pdf->addPage();
		$pdf->SetFont('Arial', 'B', 11);

		$l = array(
			's' => 10,
			'tit' => 185,
		);
		$array_data = array(
			array('', $l['s'], "C"),
			array("REPORTE DE TRASLADOS ENTRE SUCURSALES", $l['tit'], "C"),
		);
		$pdf->LineWrite($array_data);
		$pdf->LN(5);

		$pdf->SetFont('Arial', 'B', 10);
		$l = array(
			's' => 10,
			'izq' => 90,
			'der' => 95,
		);
		/**Encabezado */
		$array_data = array(
			array('', $l['s'], "C"),
			array("ORIGEN: " . $arrSucursalOrigen->nombre, $l['izq'], "C"),
			array("DESDE: " . $fechaInicio, $l['der'], "C"),
		);
		$pdf->LineWrite($array_data);
		$array_data = array(
			array('', $l['s'], "C"),
			array("DESTINO: " . $arrSucursalDestino->nombre, $l['izq'], "C"),
			array("HASTA: " . $fechaFin, $l['der'], "C"),
		);
		$pdf->LineWrite($array_data);
		$pdf->LN(5);

		//**Fin del encabezado, inicia la tabla */
		$ancho_columna = array(
			'id_traslado' => 20,
			'fecha' => 22,
			'usuario' => 30,
			'descripcion' => 87,
			'cantidad' => 18,
			'estado' => 25,
		);
		$array_data = array(
			array('', 1, "C"), //Columna de relleno para que se pinte el border
			array('# Traslado', $ancho_columna['id_traslado'], "C"),
			array("Fecha", $ancho_columna['fecha'], "L"),
			array("Producto", $ancho_columna['descripcion'], "L"),
			array("Usuario", $ancho_columna['usuario'], "L"),
			array("Cantidad", $ancho_columna['cantidad'], "L"),
			array("Estado", $ancho_columna['estado'], "L"),
		);
		$pdf->LineWriteB($array_data);

		$data = $this->traslado->get_traslados_entre_fechas($fechaInicio, $fechaFin, $sucursalDespacho, $sucursalDestino);

		$pdf->SetFont('Arial', '', 10);
		if (!$data == 0) { //Si no se retorna nada de la consulta.

			foreach ($data as $arrData) {
				$estado = "PENDIENTE";
				if ($arrData->estado == 1) {
					$estado = "FINALIZADO";
				}
				$total_cantidad = 0;

				$array_data = array(
					array('', 1, "C"), //Columna de relleno para que se pinte el border
					array($arrData->id_traslado, $ancho_columna['id_traslado'], "C"),
					array($arrData->fecha, $ancho_columna['fecha'], "L"),
					array($arrData->concepto, $ancho_columna['descripcion'], "L"),
					array($arrData->usuario, $ancho_columna['usuario'], "L"),
					array("", $ancho_columna['cantidad'], "R"),
					array($estado, $ancho_columna['estado'], "L"),
				);
				//ARRRAY de RGB para setear el color de la celda nueva a insertar
				$RGB = [214, 224, 245];
				$pdf->LineWriteFilled($array_data, $RGB);
				//$pdf->LineWriteB($array_data);

				//Luego de agregar la linea del traslado, agregar la lista de productos trasladados
				$detalle_traslado = $this->traslado->get_all_detalle_traslado($arrData->id_traslado);
				if (!$detalle_traslado == 0) {

					$prod_descripcion = '';
					$prod_cantidades = ''; //Aumentar los precios aqui para que aprezcan listados igual que los productos
					foreach ($detalle_traslado as $row_detalle) {
						//Luego de agregar la linea del traslado, agregar la lista de productos trasladados
						$detalle_traslado = $this->traslado->get_detalle_traslado($arrData->id_traslado);
						$total_cantidad += $row_detalle->cantidad;
						/*Concatenar los campos de Nombre, modelo y color del prodcto*/
						$prod_descripcion .= "*( " . $row_detalle->cantidad . " ) " . $row_detalle->nombre;
						$prod_descripcion .= " " . $row_detalle->modelo;
						$prod_descripcion .= " " . $row_detalle->color . ".";
						$prod_descripcion .= "\n";

						$prod_cantidades .= ($prod_cantidades == "") ? "" : "\n";
						//$prod_cantidades .= $row_detalle->cantidad;
					}
					$array_detalle = array(
						array('', 1, "C"), //Columna de relleno para que se pinte el border
						array("", $ancho_columna['id_traslado'], "C"),
						array("", $ancho_columna['fecha'], "L"),
						array($prod_descripcion, $ancho_columna['descripcion'], "L"),
						array("", $ancho_columna['usuario'], "L"),
						array($total_cantidad, $ancho_columna['cantidad'], "C"),
						array("", $ancho_columna['estado'], "L"),
					);
					$pdf->LineWriteB($array_detalle);
				}
			}
		} else {
			$array_data = array(
				//SUMARTORIA DE TODOS LOS ANCHOS DE LA TABLA PARA QUE QUEDE UNA SOLA
				array("No se encontraron Datos que mostrar", 203, "C")
			);
			$pdf->LineWriteB($array_data);
		} //FIN si se retorna o no de la primera consulta

		$pdf->Output();
	} //FIN function reporte traslado de rangos

	function generar_trabajos_taller()
	{
		if ($this->input->method(TRUE) == "GET") {

			$id = $this->uri->segment(3);
			//echo $id;
			$tipoReporte = $this->uri->segment(4);
			$fechaI = $this->uri->segment(5);
			$fechaF = $this->uri->segment(6);
			$sucursal = $this->uri->segment(7); //sucursal
			//procedemos a obtener los datos de la sucursal
			$arrSucursal = $this->reportes->get_row_sucursal($sucursal);
			$this->load->library('Report');
			//procedemos a obtener el tipo de reporte
			$obtenerTipo = $this->reportes->get_tipo_reporte($id);
			//var_dump($obtenerTipo);
			$pdf = $this->report->getInstance('P', 'mm', 'Letter');
			$logo = "assets/img/logo.png";
			$pdf->SetMargins(6, 10);
			$pdf->SetLeftMargin(5);
			$pdf->AliasNbPages();
			$pdf->SetAutoPageBreak(true, 15);
			$pdf->AliasNbPages();
			$data = array("empresa" => "Jah", "imagen" => $logo, 'fecha' => "14-10-1998", 'titulo' => $obtenerTipo->nombre);
			$pdf->setear($data);
			$pdf->addPage();
			$pdf->SetFont('Arial', 'B', 10);

			$l = array(
				's' => 10,
				'con' => 180,
			);
			$array_data = array(
				array('', $l['s'], "C"),
				array($arrSucursal->nombre . " " . $arrSucursal->direccion, $l['con'], "C"),
			);
			$pdf->LineWrite($array_data);
			$pdf->LN(5);
		} //emergencias reportadas
		$pdf->Output();
		//echo $id."#";
	}
}//FIN Reporte Class
/* End of file Productos.php */
