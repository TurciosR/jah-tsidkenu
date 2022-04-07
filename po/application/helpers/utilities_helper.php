<?php
if(!function_exists("nombre_dia"))
{
	setlocale(LC_ALL,'es_ES');
	function nombre_dia($fecha)
	{
		return ucfirst(mb_strtolower(strftime("%A, %d de %B de %Y",strtotime($fecha))));
	}
}
if(!function_exists("d_m_Y"))
{
	function d_m_Y($fecha)
	{
		$dia = substr($fecha,8,2);
		$mes = substr($fecha,5,2);
		$a = substr($fecha,0,4);
		$fecha = "$dia-$mes-$a";
		return $fecha;
	}
}
if(!function_exists("Y_m_d"))
{
	function Y_m_d($fecha)
	{
		$a = substr($fecha,6,4);
		$mes = substr($fecha,3,2);
		$dia = substr($fecha,0,2);
		$fecha = "$a-$mes-$dia";
		return $fecha;
	}
}
if(!function_exists("hora_A_P"))
{
	function hora_A_P($hora)
	{
		$hora_pre = date_create($hora);
		$hora_pos = date_format($hora_pre, 'g:i A');
		return $hora_pos;
	}
}
if(!function_exists("quitar_tildes"))
{
	function quitar_tildes($cadena)
	{
		$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹"," ");
		$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","_");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}
}
if(!function_exists("diferenciaDias"))
{
	function diferenciaDias($inicio, $fin)
	{
		$inicio = strtotime($inicio);
		$fin = strtotime($fin);
		$dif = $fin - $inicio;
		$diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
		return ceil($diasFalt);
	}
}
if(!function_exists("divtextlin"))
{
	function divtextlin( $text, $width = '80', $lines = '10', $break = '\n', $cut = 0 ) {
	  $wrappedarr = array();
	  $wrappedtext = wordwrap( $text, $width, $break , true );
	  $wrappedtext = trim( $wrappedtext );
	  $arr = explode( $break, $wrappedtext );
	  return $arr;
	}
}
if(!function_exists("array_procesor"))
{
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
if(!function_exists("restar_meses")){
	function restar_meses($fecha, $cantidad)
	{
		$nuevafecha = strtotime ( '-'.$cantidad.' month' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		return $nuevafecha;
	}
}
if(!function_exists("sumar_meses")){
	function sumar_meses($fecha, $cantidad)
	{
		$nuevafecha = strtotime ( '+'.$cantidad.' month' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		return $nuevafecha;
	}
}
if(!function_exists("nombre_mes")){
	function nombre_mes($n){
		$mes = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		return $mes[$n-1];
	}
}
if(!function_exists("edad_decimal")){
	function edad_decimal($fecha){
		$dob_day = substr($fecha,8,2);
		$dob_month = substr($fecha,5,2);
		$dob_year = substr($fecha,0,4);
		$year   = gmdate('Y');
		$month  = gmdate('m');
		$day    = gmdate('d');
		//seconds in a day = 86400
		$days = (mktime(0,0,0,$month,$day,$year) - mktime(0,0,0,$dob_month,$dob_day,$dob_year))/86400;
		return $days / 365.242199;
	}
}

if(!function_exists("num2letras")){
	function num2letras($num, $fem = true, $dec = true) {
	$matuni[0]  = "cero";
   $matuni[2]  = "dos";
   $matuni[3]  = "tres";
   $matuni[4]  = "cuatro";
   $matuni[5]  = "cinco";
   $matuni[6]  = "seis";
   $matuni[7]  = "siete";
   $matuni[8]  = "ocho";
   $matuni[9]  = "nueve";
   $matuni[10] = "diez";
   $matuni[11] = "once";
   $matuni[12] = "doce";
   $matuni[13] = "trece";
   $matuni[14] = "catorce";
   $matuni[15] = "quince";
   $matuni[16] = "dieciseis";
   $matuni[17] = "diecisiete";
   $matuni[18] = "dieciocho";
   $matuni[19] = "diecinueve";
   $matuni[20] = "veinte";
   $matunisub[2] = "dos";
   $matunisub[3] = "tres";
   $matunisub[4] = "cuatro";
   $matunisub[5] = "quin";
   $matunisub[6] = "seis";
   $matunisub[7] = "sete";
   $matunisub[8] = "ocho";
   $matunisub[9] = "nove";

   $matdec[2] = "veint";
   $matdec[3] = "treinta";
   $matdec[4] = "cuarenta";
   $matdec[5] = "cincuenta";
   $matdec[6] = "sesenta";
   $matdec[7] = "setenta";
   $matdec[8] = "ochenta";
   $matdec[9] = "noventa";
   $matsub[3]  = 'mill';
   $matsub[5]  = 'bill';
   $matsub[7]  = 'mill';
   $matsub[9]  = 'trill';
   $matsub[11] = 'mill';
   $matsub[13] = 'bill';
   $matsub[15] = 'mill';
   $matmil[4]  = 'millones';
   $matmil[6]  = 'billones';
   $matmil[7]  = 'de billones';
   $matmil[8]  = 'millones de billones';
   $matmil[10] = 'trillones';
   $matmil[11] = 'de trillones';
   $matmil[12] = 'millones de trillones';
   $matmil[13] = 'de trillones';
   $matmil[14] = 'billones de trillones';
   $matmil[15] = 'de billones de trillones';
   $matmil[16] = 'millones de billones de trillones';

   $num = trim((string)@$num);
   if ($num[0] == '-') {
      $neg = 'menos ';
      $num = substr($num, 1);
   }else
      $neg = '';
  while ($num[0] == '0') $num[0] = substr($num[0], 1);

   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
   $zeros = true;
   $punt = false;
   $ent = '';
   $fra = '';
   for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,'''", $n) === false)) {
         if ($punt) break;
         else{
            $punt = true;
            continue;
         }

      }elseif (! (strpos('0123456789', $n) === false)) {
         if ($punt) {
            if ($n != '0') $zeros = false;
            $fra .= $n;
         }else

            $ent .= $n;
      }else

         break;

   }
   $ent = '     ' . $ent;
   if ($dec and $fra and ! $zeros) {
      $fin = ' coma';
      for ($n = 0; $n < strlen($fra); $n++) {
         if (($s = $fra[$n]) == '0')
            $fin .= ' cero';
         elseif ($s == '1')
            $fin .= $fem ? ' un' : ' un';
         else
            $fin .= ' ' . $matuni[$s];
      }
   }else
      $fin = '';
   if ((int)$ent === 0) return 'cero ' . $fin;
   $tex = '';
   $sub = 0;
   $mils = 0;

   $neutro = false;
   while ( ($num = substr($ent, -3)) != '   ') {
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
         $matuni[1] = 'uno';
         $subcent = 'os';
      }else{
         $matuni[1] = $neutro ? 'un' : 'uno';
         $subcent = 'os';
      }
      $t = '';
      $n2 = substr($num, 1);
      if ($n2 == '00') {
      }elseif ($n2 < 21)
         $t = ' ' . $matuni[(int)$n2];
      elseif ($n2 < 30) {
         $n3 = $num[2];
         if ($n3 != 0) $t = 'i' . $matuni[$n3];
         $n2 = $num[1];
         $t = ' ' . $matdec[$n2] . $t;
      }else{
         $n3 = $num[2];
         if ($n3 != 0) $t = ' y ' . $matuni[$n3];
         $n2 = $num[1];
         $t = ' ' . $matdec[$n2] . $t;
      }
      $n = $num[0];
      if ($n == 1) {
         $t = ' ciento' . $t;
      }elseif ($n == 5){
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
      }elseif ($n != 0){
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
      }
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
         if ($num == 1) {
            $t = ' mil';
         }elseif ($num > 1){
            $t .= ' mil';
         }
      }elseif ($num == 1) {
         $t .= ' ' . $matsub[$sub] . 'on';
      }elseif ($num > 1){
         $t .= ' ' . $matsub[$sub] . 'ones';
      }
      if ($num == '000') $mils ++;
      elseif ($mils != 0) {
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
         $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
   }
   $tex = $neg . substr($tex, 1) . $fin;
   return ($tex);
}
}
