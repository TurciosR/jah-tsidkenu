<?php
    /** Error reporting */
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);

    if (PHP_SAPI == 'cli')
	   die('Error Inesperado');
    /** Include PHPExcel */
    require_once dirname(__FILE__) . '/php_excel/Classes/PHPExcel.php';
    include('_core.php');

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Open Solutions Systems")
    						->setLastModifiedBy("Open Solutions Systems")
    						->setTitle("Office 2007 XLSX")
    						->setSubject("Office 2007 XLSX")
    						->setDescription("Documento compatible con Office 2007 XLSX")
    						->setKeywords("office 2007 openxml php")
    						->setCategory("Reportes");
    //Titulos
    $title0="REPORTE FISCAL";


    //style border
    $BStyle = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
            'allborders' => array(
               'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
    //Center table
    $titulo = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
  		'font'  => array(
  			'bold'  => true,
  			'color' => array('rgb' => '00000'),
  			'size'  => 10,
  			'name'  => 'Arial'
        )
    );
	$negrita_centrado = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '000000'),
            'size'  => 10,
            'name'  => 'Arial'
        )
    );
    $centrado = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
		'font'  => array(
			'bold'  => false,
			'color' => array('rgb' => '000000'),
			'size'  => 10,
			'name'  => 'Arial'
        )
    );

    $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
    $objPHPExcel->getActiveSheet()->mergeCells('E2:F2');


    $objPHPExcel->getActiveSheet()->mergeCells('A2:A3');
    $objPHPExcel->getActiveSheet()->mergeCells('B2:B3');
    $objPHPExcel->getActiveSheet()->mergeCells('C2:C3');
    $objPHPExcel->getActiveSheet()->mergeCells('D2:D3');
    $objPHPExcel->getActiveSheet()->mergeCells('G2:G3');
    $objPHPExcel->getActiveSheet()->mergeCells('H2:H3');


    //altura de algunas filas
    for($j=2;$j<3;$j++)
    {
        $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(15);
    }
    //Ancho de algunas filas
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

    $nin = 2;
    //Esrilo de fuentes
    $objPHPExcel->getActiveSheet()->getStyle("A1:H1")->applyFromArray($titulo);
    $objPHPExcel->getActiveSheet()->getStyle("A2".":H3")->applyFromArray($negrita_centrado);
    $objPHPExcel->getActiveSheet()->getStyle("A2".":H3")->applyFromArray($BStyle);


    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'."1", "REPORTE EXISTENCIAS");

    //Encabezados de la tabla
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nin, "NOMBRE COMERCIAL");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$nin, "PRESENTACIÓN");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$nin, "LABORATORIO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$nin, "PRINCIPIO ACTIVO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$nin, "CONCENTRACIÓN");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($nin+1), "VALOR");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($nin+1), "UNIDAD");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$nin, "PRECIO PUBLICO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$nin, "EXISTENCIAS");

    $nin=4;

    $total_general = 0;
    $sql_stock = _query("SELECT pr.id_producto,pr.marca,pr.composicion,pr.descripcion, pr.barcode, c.nombre_cat as cat, SUM(su.cantidad) as cantidad, laboratorio.laboratorio
    FROM producto AS pr
    LEFT JOIN laboratorio on laboratorio.id_laboratorio=pr.id_laboratorio
    JOIN stock_ubicacion as su on su.id_producto=pr.id_producto
    JOIN categoria as c on c.id_categoria=pr.id_categoria
    WHERE su.cantidad>0 AND su.id_sucursal=$_SESSION[id_sucursal]  GROUP BY pr.id_producto ORDER BY pr.descripcion ");

    $contar = _num_rows($sql_stock);
    if($contar > 0)
    {
      while ($row = _fetch_array($sql_stock))
      {
        $id_producto = $row['id_producto'];
        $descripcion=$row["descripcion"];
        $composicion=$row["composicion"];
        $cat = $row['cat'];

        if ($row['laboratorio']=='') {
          // code...
          $marca =strtoupper($row['marca']);
        }
        else {
          // code...
          $marca =strtoupper($row['laboratorio']);
        }


        $barcode = $row['barcode'];
        $existencias = $row['cantidad'];
        $sql_pres = _query("SELECT pp.*, p.nombre as descripcion_pr FROM presentacion_producto as pp, presentacion as p WHERE pp.presentacion=p.id_presentacion AND pp.id_producto='$id_producto' ORDER BY pp.unidad DESC");
        $npres = _num_rows($sql_pres);
        $exis = 0;
        while ($rowb = _fetch_array($sql_pres))
        {
          $unidad = $rowb["unidad"];
          $costo = $rowb["costo"];
          $precio = $rowb["precio"];

          $cvalor= $rowb["cvalor"];
          $cunidad= $rowb["cunidad"];

          $xc=0;

          $sql_rank=_query("SELECT presentacion_producto_precio.precio FROM presentacion_producto_precio WHERE presentacion_producto_precio.id_presentacion=$rowb[id_presentacion] AND presentacion_producto_precio.id_sucursal=$_SESSION[id_sucursal] AND presentacion_producto_precio.precio!=0 ORDER BY presentacion_producto_precio.desde ASC LIMIT 1
            ");

            while ($rowr=_fetch_array($sql_rank)) {
              # code...
              if($xc==0)
              {

                $precio=$rowr['precio'];
              }
            }

            $descripcion_pr = $rowb["descripcion"];
            $presentacion = $rowb["descripcion_pr"];
            if($existencias >= $unidad)
            {
              $exis = intdiv($existencias, $unidad);
              $existencias = $existencias%$unidad;
            }
            else
            {
              $exis =  0;
            }


            $total_costo = round(($precio) * $exis, 4);
            $total_general += $total_costo;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nin, $descripcion);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$nin, ($presentacion." ".$descripcion_pr));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$nin, $marca);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$nin, $composicion);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$nin, $cvalor);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$nin, $cunidad);
            $objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$nin, $precio);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$nin, $exis);

            $nin += 1;
          }
        }

        $objPHPExcel->getActiveSheet()->mergeCells('A'.$nin.':G'.$nin);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nin, "TOTAL");
        $objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$nin, $total_general);
        /*$pdf->Cell(245,5,utf8_decode("TOTAL"),0,0,'L',0);
        $pdf->Cell(25,5,utf8_decode("$".number_format($total_general, 2)),0,1,'R',0);*/


        $objPHPExcel->getActiveSheet()->getStyle("A3".":H".$nin)->applyFromArray($BStyle);
      }



    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Reporte Valoracion');



    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $archivo_salida="reporte_valoracion_concentracion".date("dmY").".xls";
    // Redirect output to a client’s web browser (Excel7)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$archivo_salida.'"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 07:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
?>
