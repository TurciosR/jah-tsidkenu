<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . 'third_party/fpdf/libraries/fpdf.php');

class ReporteRied extends FPDF {

  var $ary = array();
  // Cabecera de página\
  public function Header()
  {
    $this->Image($this->ary['imagen'],5,5,15,15);
    $this->SetFont('Arial','',10);
    $this->Cell(205,5,"RESUMEN ".utf8_decode(mb_strtoupper($this->ary['empresa']))." DEL DIA ".$this->ary['fecha'],0,1,'C',0);
    $this->Ln(5);
  }

  public function Footer()
  {
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial', 'I', 8);
    // Número de página requiere $pdf->AliasNbPages();
    //utf8_decode() de php que convierte nuestros caracteres a ISO-8859-1
    $this-> Cell(100, 10, (utf8_decode('Fecha de impresión: ').date('d-m-Y')), 0, 0, 'L');
    $this->Cell(110, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'R');
  }
  public function setear($a)
  {
    # code...
    $this->ary = $a;
  }

  function LineWrite($array)
  {
    $ygg=0;
    $maxlines=1;
    $array_a_retornar=array();
    foreach ($array as $key => $value) {
      /*Descripcion*/
      $nombr=$value[0];
      /*fpdf width*/
      $size=$value[1];
      /*fpdf alignt*/
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
        $this->Cell($data[$j]["size"][$i],5,utf8_decode($data[$j]["valor"][$i]),$abajo,$salto,$data[$j]["aling"][$i]);
      }

    }
  }
  public function getInstance($a,$b,$c){
      return new ReporteRied($a,$b,$c);
  }
}
?>
