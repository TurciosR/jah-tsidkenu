<?php
require('fpdf/fpdf.php');
require '_core.php';

class PDF extends FPDF
{

  function Header()
  {
    //Logo
    //$this->Image('logo_pb.png',10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',9);
    //Movernos a la derecha
    $this->Cell(270,10,'REPORTE DE EXISTENCIAS',0,0,'C');
    $this->Ln(10);

    $x=$this->GetX();
    $y=$this->GetY();
    $this->Cell(45,10,'NOMBRE COMERCIAL',1,0,'C');
    $this->Cell(35,10,utf8_decode('PRESENTACIÓN'),1,0,'C');
    $this->Cell(35,10,'LABORATORIO',1,0,'C');
    $this->Cell(75,10,'PRINCIPIO ACTIVO',1,0,'C');

    $x=$this->GetX();
    $y=$this->GetY();
    $this->Cell(40,5,utf8_decode('CONCENTRACIÓN'),1,1,'C');
    $this->SetX($x);
    $this->Cell(20,5,utf8_decode('VALOR'),1,0,'C');
    $this->Cell(20,5,utf8_decode('UNIDAD'),1,0,'C');
    $x=$this->GetX();
    $this->SetXY($x,$y);

    $this->Cell(20,10,'P. PUBLICO',1,0,'C');/*15*/
    $this->Cell(20,10,'EXIST.',1,1,'C');/*20*/
  }

  function Footer()
  {
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
  }

  function array_procesor($array)
  {
    $ygg=0;
    $maxlines=1;
    $array_a_retornar=array();
    foreach ($array as $key => $value) {
      /*Descripcion*/
      $nombr=$value[0];
      /*character*/
      $longitud=$value[1];
      /*fpdf width*/
      $size=$value[2];
      /*fpdf alignt*/
      $aling=$value[3];
      if(strlen($nombr) > $longitud)
      {
        $i=0;
        $nom = divtextlin($nombr, $longitud);
        foreach ($nom as $nnon)
        {
          $array_a_retornar[$ygg]["valor"][]=$nnon;
          $array_a_retornar[$ygg]["size"][]=$size;
          $array_a_retornar[$ygg]["aling"][]=$aling;
          $i++;
        }
        $ygg++;
        if ($i>$maxlines) {
          // code...
          $maxlines=$i;
        }
      }
      else {
        // code...
        $array_a_retornar[$ygg]['valor'][]=$nombr;
        $array_a_retornar[$ygg]['size'][]=$size;
        $array_a_retornar[$ygg]["aling"][]=$aling;
        $ygg++;

      }
    }

    $ygg=0;
    foreach($array_a_retornar as $keys)
    {
      for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
        // code...
        $array_a_retornar[$ygg]["valor"][]="";
        $array_a_retornar[$ygg]["size"][]=$array_a_retornar[$ygg]["size"][0];
        $array_a_retornar[$ygg]["aling"][]=$array_a_retornar[$ygg]["aling"][0];
      }
      $ygg++;
    }
    return $array_a_retornar;

  }
}

$pdf=new PDF('L','mm', 'Letter');
$pdf->AliasNbPages();
$pdf->SetLeftMargin(5);
$pdf->SetTopMargin(10);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();
$pdf->SetFont('Times','',10);


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

        $array_data = array(
          0 => array($descripcion,20,45,"L"),
          1 => array( $presentacion." ".$descripcion_pr,17,35,"L"),
          2 => array($marca,17,35,"L"),
          3 => array($composicion,37,75,"L"),
          4 => array($cvalor,150,20,"R"),
          5 => array($cunidad,150,20,"R"),
          6 => array($precio,35,20,"R"),
          7 => array($exis,40,20,"R"),
        );

        $data=$pdf->array_procesor($array_data);
        $total_lineas=count($data[0]["valor"]);
        $total_columnas=count($data);

        for ($i=0; $i < $total_lineas; $i++) {
          // code...
          for ($j=0; $j < $total_columnas; $j++) {
            // code...
            $salto=0;
            $abajo=0;
            if ($j==$total_columnas-1) {
              // code...
              $salto=1;
            }
            if ($i==$total_lineas-1) {
              // code...
              $abajo="B";
            }
            $pdf->Cell($data[$j]["size"][$i],5,utf8_decode($data[$j]["valor"][$i]),$abajo,$salto,$data[$j]["aling"][$i]);
          }

        }

        /*$pdf->Cell(35,5,utf8_decode($presentacion),0,0,'L',0);
        $pdf->Cell(35,5,utf8_decode($descripcion_pr),0,0,'L',0);
        $pdf->Cell(15,5,utf8_decode(number_format($precio, 2)),0,0,'C',0);
        $pdf->Cell(15,5,utf8_decode($exis),0,0,'C',0);
        $pdf->Cell(20,5,utf8_decode(number_format($total_costo, 4)),0,1,'R',0);*/
      }
    }
    $pdf->Cell(245,5,utf8_decode("TOTAL"),0,0,'L',0);
    $pdf->Cell(25,5,utf8_decode("$".number_format($total_general, 2)),0,1,'R',0);
  }


  ob_clean();
  $pdf->Output("reporte_ventas.pdf","I");
