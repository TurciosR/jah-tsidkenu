<?php
require('_core.php');
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    var $a = array();
    // Cabecera de página\
    public function Header()
    {

        // Logo
        $this->Image('img/logo_sys.png', 10, 10, 33);
        $this->AddFont('latin','','latin.php');
        $this->SetFont('latin', '', 10);

        $this-> Cell(195, 10, "REPORTE VENTAS DEL ".$this->a['fini']." AL ".$this->a['ffin'], 0, 1, 'C');
        $this->Ln(5);
        $this-> Cell(20, 5,"FECHA", 1, 0, 'C');
        $this-> Cell(60, 5,"VENTA DE PRODUCTOS", 1, 0, 'C');
        $this-> Cell(60, 5,"VENTA DE SERVICIOS", 1, 0, 'C');
        $this-> Cell(55, 5,"TOTAL VENTAS", 1, 1, 'C');
    }

    public function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página requiere $pdf->AliasNbPages();
        //utf8_decode() de php que convierte nuestros caracteres a ISO-8859-1
        $this-> Cell(40, 10, utf8_decode('Fecha de impresión: '.date('Y-m-d')), 0, 0, 'L');
        $this->Cell(156, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'R');
    }
    public function setear($a)
    {
      $this->a=$a;
    }

    public function LineWriteB($array)
    {
      $ygg=0;
      $maxlines=1;
      $array_a_retornar=array();
      $array_max= array();
      foreach ($array as $key => $value) {
        // /Descripcion/
        $nombr=$value[0];
        // /fpdf width/
        $size=$value[1];
        // /fpdf alignt/
        $aling=$value[2];
        $jk=0;
        $w = $size;
        $h  = 0;
        $txt=$nombr;
        $border=0;
        if(!isset($this->CurrentFont))
          $this->Error('No font has been set');
        $cw = &$this->CurrentFont['cw'];
        if($w==0)
          $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
          $nb--;
        $b = 1;

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while($i<$nb)
        {
          // Get next character
          $c = $s[$i];
          if($c=="\n")
          {
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;

            $i++;
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            $nl++;
            if($border && $nl==2)
              $b = $b2;
            continue;
          }
          if($c==' ')
          {
            $sep = $i;
            $ls = $l;
            $ns++;
          }
          $l += $cw[$c];
          if($l>$wmax)
          {
            // Automatic line break
            if($sep==-1)
            {
              if($i==$j)
                $i++;
              $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
              $array_a_retornar[$ygg]["size"][]=$size;
              $array_a_retornar[$ygg]["aling"][]=$aling;
              $jk++;
            }
            else
            {
              $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$sep-$j);
              $array_a_retornar[$ygg]["size"][]=$size;
              $array_a_retornar[$ygg]["aling"][]=$aling;
              $jk++;

              $i = $sep+1;
            }
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            $nl++;
            if($border && $nl==2)
              $b = $b2;
          }
          else
            $i++;
        }
        // Last chunk
        if($this->ws>0)
        {
          $this->ws = 0;
        }
        if($border && strpos($border,'B')!==false)
          $b .= 'B';
        $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
        $array_a_retornar[$ygg]["size"][]=$size;
        $array_a_retornar[$ygg]["aling"][]=$aling;
        $jk++;
        $ygg++;
        if ($jk>$maxlines) {
          // code...
          $maxlines=$jk;
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



      $data=$array_a_retornar;
      $total_lineas=count($data[0]["valor"]);
      $total_columnas=count($data);

      for ($i=0; $i < $total_lineas; $i++) {
        // code...
        for ($j=0; $j < $total_columnas; $j++) {
          // code...
          $salto=0;
          $abajo="LR";
          if ($i==0) {
            // code...
            $abajo="TLR";
          }
          if ($j==$total_columnas-1) {
            // code...
            $salto=1;
          }
          if ($i==$total_lineas-1) {
            // code...
            $abajo="BLR";
          }
          if ($i==$total_lineas-1&&$i==0) {
            // code...
            $abajo="1";
          }
          // if ($j==0) {
          //   // code...
          //   $abajo="0";
          // }
          $str = $data[$j]["valor"][$i];
          $this->Cell($data[$j]["size"][$i],4,$str,$abajo,$salto,$data[$j]["aling"][$i]);
        }

      }
    }
}

$fini = $_REQUEST['fini'];
$ffin = $_REQUEST['fin'];
$a = array(
  "fini" => ED($fini),
  "ffin" => ED($ffin)
);

$pdf = new PDF('P', 'mm', 'letter');

$pdf->setear($a);
$pdf->SetMargins(10, 10);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 15);
$pdf->AliasNbPages();
$pdf->AddPage();

$totales = 0;
$serviciost = 0;
//SELECT factura_detalle.* FROM factura JOIN factura_detalle ON factura_detalle.id_factura=factura.id_factura WHERE factura.anulada=0 AND factura.tipo_documento!='DEV' AND factura.tipo_documento!='NC' AND factura.fecha='2021-02-01' AND factura_detalle.servicio=1 AND factura_detalle.id_prod_serv!=5
$sql= _query("SELECT factura.fecha,SUM(factura.total) as total FROM factura WHERE factura.anulada=0 AND factura.finalizada=1 AND factura.tipo_documento!='DEV' AND factura.tipo_documento!='NC' AND factura.fecha BETWEEN '$fini' AND '$ffin' GROUP BY factura.fecha");
while($row = _fetch_array($sql))
{
  $total = round($row['total'],2);

  $totales =  $totales +$total;
  $sql2 = _fetch_array(_query("SELECT factura.fecha,SUM(factura_detalle.precio_venta) as total
  FROM factura
  JOIN factura_detalle ON factura_detalle.id_factura=factura.id_factura
  WHERE factura.anulada=0 AND factura.finalizada=1  AND factura.tipo_documento!='DEV' AND factura.tipo_documento!='NC' AND factura.fecha='$row[fecha]'
  AND factura_detalle.servicio=1 AND factura_detalle.id_prod_serv!=5"));
  $servicios = round($sql2['total'],2);
  $serviciost =  $serviciost + $servicios;
  $array_data = array(
    array(ED($row['fecha']),20,"C"),
    array(number_format($total-$servicios,2),60,"R"),
    array(number_format($servicios,2),60,"R"),
    array("$".number_format($total,2),55,"R"),
  );
  $pdf->LineWriteB($array_data);
}

$pdf-> Cell(20, 5,"TOTAL", 1, 0, 'C');
$pdf->Cell(60, 5,"$".number_format($totales-$serviciost,2), 1, 0, 'R');
$pdf->Cell(60, 5,"$".number_format($serviciost,2), 1, 0, 'R');
$pdf->Cell(55, 5,"$".number_format($totales,2), 1, 0, 'R');

$pdf->Output("reporte.pdf", "I");
