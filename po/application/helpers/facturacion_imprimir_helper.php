<?php

if (!function_exists('print_fact')) {
	function print_fact($id_factura,$tipo_id)
	{
		$ci =& get_instance();
		$ci->load->model('Imprimir_model');
		$ci->load->helper('utilities_helper');
		// $this->load->model('');
		$info_factura = "";
		$id_sucursal=$_SESSION['id_sucursal'];

		if ($id_sucursal==1 || $id_sucursal==4 ) {
			// code...
			$id_factura=$id_factura;
			$tipo_id=$tipo_id;

			$logitud_array=28;
		  $arrayL= array();
		  for ($i=0; $i < $logitud_array; $i++) {
		    // code...
		    $arrayL[$i]=st("",60)."\n";
		  }

			//Obtener informacion de tabla Factura
			// $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
			$row_fact = $ci->Imprimir_model->get_factura($id_factura);
			$id_cliente=$row_fact->id_cliente;
			$id_factura = $row_fact->id_factura;
			$id_usuario=$row_fact->id_usuario;
			$fecha=$row_fact->fecha;
			$total=number_format(round($row_fact->total, 2),2,'.',',');
			$iva=number_format(round($row_fact->iva, 2),2,'.',',');

			$total_final = number_format(round($total + $iva, 2), 2,'.',',');

			$dia = substr($fecha,8,2);
	    $mes = substr($fecha,5,2);
	    $a = substr($fecha,0,4);
	    $fecha = "$dia-$mes-$a";


			$row_cliente = $ci->Imprimir_model->get_cliente($id_cliente);
			// $id_cliente=$row_cliente->id_cliente;
			$nombre=$row_cliente->nombre;
			$nit=$row_cliente->nit;
			$nrc=$row_cliente->nrc;
			$dui=$row_cliente->dui;
			$direccion=$row_cliente->direccion;


			$arrayL[5]=st("",8).st(substr($nombre,0,44),44," ","R").st("",7).st($fecha,10," ","L")."\n";
			$arrayL[7]=st("",9).st(substr($direccion,0,44),44," ","R")."\n";
			// $arrayL[9]=st("",6).st(substr($nombre,0,24),24," ","R")."\n";
			// $arrayL[10]=st("",30).st($fecha,20," ","L")."\n";
			// $arrayL[12]=st("",31).st($nrc,20," ","L")."\n";
			// $arrayL[13]=st("",35).st($nit,20," ","L")."\n";

			$row_fact_det = $ci->Imprimir_model->get_factura_detalle($id_factura);

			$i = 10;
			foreach ($row_fact_det as $fila)
			{
				$cantidad=$fila->cantidad;
				$valor=number_format(round($fila->valor, 2),2,'.',',');
				$subtotal=number_format(round($fila->sub_total, 2),2,'.',',');
				$descripcion=$fila->descripcion;

				$array_data = array(
					0 => array($cantidad,150),
					1 => array($descripcion,37),
					2 => array($valor,150),
					3 => array($subtotal,150),
				);

				$data=array_procesor2($array_data);
				$total_lineas=count($data[0]["valor"]);
				$total_columnas=count($data);

				for ($w=0; $w < $total_lineas; $w++) {
					$arrayL[$i]=
					str_pad("",2," ",STR_PAD_LEFT)
					.str_pad($data[0]["valor"][$w],6," ",STR_PAD_BOTH)
					.str_pad_unicode(substr($data[1]["valor"][$w],0,37),37," ",STR_PAD_RIGHT)
					.str_pad(" ",2," ",STR_PAD_LEFT)
					.str_pad($data[2]["valor"][$w],6," ",STR_PAD_LEFT)
					.str_pad($data[3]["valor"][$w],15," ",STR_PAD_LEFT)."\n";
					$i ++;
				}

			}

			// $total_txt0=num2letras($total_final);

			list($entero,$decimal)=explode('.',$total_final);
			$enteros_txt=num2letras($entero);
			if(strlen($decimal)==1){
				$decimales_txt=$decimal."0";
			}
			else{
				$decimales_txt=$decimal;
			}

			$total_txt0= $enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
			$array_painc = array(
				0 => 19,
				1 => 20,
				2 => 21,
			);
			$array_nocon= dtl($total_txt0,28);
			foreach ($array_nocon as $key => $value) {
				// code...
				$arrayL[$array_painc[$key]]=st("",7).st($value,28," ","R").st("",25)."\n";
			}


			$arrayL[26]=st("",7).st($dui,28," ","R").st("",25)."\n";

			$arrayL[19]=substr($arrayL[19], 0,44).st($total,24," ","L")."\n";/*sumas*/
		  // $arrayL[22]=substr($arrayL[22], 0,33).st($iva,24," ","L")."\n";/*vtas extentas*/
		  $arrayL[22]=substr($arrayL[22], 0,44).st($total_final,24," ","L")."\n";/*subtotal*/
		  $arrayL[27]=substr($arrayL[27], 0,44).st($total_final,24," ","L")."\n";/*total*/

		  	$info_factura.="\n";
			//$value = $id_factura;
			foreach ($arrayL as $key => $value) {
		    $info_factura.=$value;
		  }

		  // retornar valor generado en funcion
		  return ($info_factura);
		}
		elseif ($id_sucursal==2) {
			// code... factura 1501 REG 2110852-2
			$id_factura=$id_factura;
			$tipo_id=$tipo_id;

			$logitud_array=28;
			$arrayL= array();
			for ($i=0; $i < $logitud_array; $i++) {
				// code...
				$arrayL[$i]=st("",60)."\n";
			}

			//Obtener informacion de tabla Factura
			// $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
			$row_fact = $ci->Imprimir_model->get_factura($id_factura);
			$id_cliente=$row_fact->id_cliente;
			$id_factura = $row_fact->id_factura;
			$id_usuario=$row_fact->id_usuario;
			$fecha=$row_fact->fecha;
			$total=number_format(round($row_fact->total, 2),2,'.',',');
			$iva=number_format(round($row_fact->iva, 2),2,'.',',');

			$total_final = number_format(round($total + $iva, 2), 2,'.',',');

			$dia = substr($fecha,8,2);
			$mes = substr($fecha,5,2);
			$a = substr($fecha,0,4);
			$fecha = "$dia-$mes-$a";


			$row_cliente = $ci->Imprimir_model->get_cliente($id_cliente);
			// $id_cliente=$row_cliente->id_cliente;
			$nombre=$row_cliente->nombre;
			$nit=$row_cliente->nit;
			$nrc=$row_cliente->nrc;
			$dui=$row_cliente->dui;
			$direccion=$row_cliente->direccion;


			$arrayL[5]=st("",8).st(substr($nombre,0,44),44," ","R").st("",7).st($fecha,10," ","L")."\n";
			$arrayL[7]=st("",9).st(substr($direccion,0,44),44," ","R")."\n";
			// $arrayL[9]=st("",6).st(substr($nombre,0,24),24," ","R")."\n";
			// $arrayL[10]=st("",30).st($fecha,20," ","L")."\n";
			// $arrayL[12]=st("",31).st($nrc,20," ","L")."\n";
			// $arrayL[13]=st("",35).st($nit,20," ","L")."\n";

			$row_fact_det = $ci->Imprimir_model->get_factura_detalle($id_factura);

			$i = 10;
			foreach ($row_fact_det as $fila)
			{
				$cantidad=$fila->cantidad;
				$valor=number_format(round($fila->valor, 2),2,'.',',');
				$subtotal=number_format(round($fila->sub_total, 2),2,'.',',');
				$descripcion=$fila->descripcion;

				$array_data = array(
					0 => array($cantidad,150),
					1 => array($descripcion,37),
					2 => array($valor,150),
					3 => array($subtotal,150),
				);

				$data=array_procesor2($array_data);
				$total_lineas=count($data[0]["valor"]);
				$total_columnas=count($data);

				for ($w=0; $w < $total_lineas; $w++) {
					$arrayL[$i]=
					str_pad("",2," ",STR_PAD_LEFT)
					.str_pad($data[0]["valor"][$w],6," ",STR_PAD_BOTH)
					.str_pad_unicode(substr($data[1]["valor"][$w],0,37),37," ",STR_PAD_RIGHT)
					.str_pad(" ",2," ",STR_PAD_LEFT)
					.str_pad($data[2]["valor"][$w],6," ",STR_PAD_LEFT)
					.str_pad($data[3]["valor"][$w],15," ",STR_PAD_LEFT)."\n";
					$i ++;
				}

			}

			// $total_txt0=num2letras($total_final);

			list($entero,$decimal)=explode('.',$total_final);
			$enteros_txt=num2letras($entero);
			if(strlen($decimal)==1){
				$decimales_txt=$decimal."0";
			}
			else{
				$decimales_txt=$decimal;
			}

			$total_txt0= $enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
			$array_painc = array(
				0 => 19,
				1 => 20,
				2 => 21,
			);
			$array_nocon= dtl($total_txt0,28);
			foreach ($array_nocon as $key => $value) {
				// code...
				$arrayL[$array_painc[$key]]=st("",7).st($value,28," ","R").st("",25)."\n";
			}


			$arrayL[26]=st("",7).st($dui,28," ","R").st("",25)."\n";

			$arrayL[19]=substr($arrayL[19], 0,44).st($total,24," ","L")."\n";/*sumas*/
			// $arrayL[22]=substr($arrayL[22], 0,33).st($iva,24," ","L")."\n";/*vtas extentas*/
			$arrayL[22]=substr($arrayL[22], 0,44).st($total_final,24," ","L")."\n";/*subtotal*/
			$arrayL[27]=substr($arrayL[27], 0,44).st($total_final,24," ","L")."\n";/*total*/

				$info_factura.="\n";
			//$value = $id_factura;
			foreach ($arrayL as $key => $value) {
				$info_factura.=$value;
			}
			// retornar valor generado en funcion
			return ($info_factura);
		}
		else {
			//factura 650 REG 140796-1
			$id_factura=$id_factura;
			$tipo_id=$tipo_id;

			$logitud_array=28;
			$arrayL= array();
			for ($i=0; $i < $logitud_array; $i++) {
				// code...
				$arrayL[$i]=st("",60)."\n";
			}

			//Obtener informacion de tabla Factura
			// $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
			$row_fact = $ci->Imprimir_model->get_factura($id_factura);
			$id_cliente=$row_fact->id_cliente;
			$id_factura = $row_fact->id_factura;
			$id_usuario=$row_fact->id_usuario;
			$fecha=$row_fact->fecha;
			$total=number_format(round($row_fact->total, 2),2,'.',',');
			$iva=number_format(round($row_fact->iva, 2),2,'.',',');

			$total_final = number_format(round($total + $iva, 2), 2,'.',',');

			$dia = substr($fecha,8,2);
			$mes = substr($fecha,5,2);
			$a = substr($fecha,0,4);
			$fecha = "$dia-$mes-$a";


			$row_cliente = $ci->Imprimir_model->get_cliente($id_cliente);
			// $id_cliente=$row_cliente->id_cliente;
			$nombre=$row_cliente->nombre;
			$nit=$row_cliente->nit;
			$nrc=$row_cliente->nrc;
			$dui=$row_cliente->dui;
			$direccion=$row_cliente->direccion;

			$arrayL[5]=st("",8).st(substr($nombre,0,44),44," ","R").st("",7).st($fecha,10," ","L")."\n";
			$arrayL[7]=st("",9).st(substr($direccion,0,44),44," ","R")."\n";
			// $arrayL[9]=st("",6).st(substr($nombre,0,24),24," ","R")."\n";
			// $arrayL[10]=st("",30).st($fecha,20," ","L")."\n";
			// $arrayL[12]=st("",31).st($nrc,20," ","L")."\n";
			// $arrayL[13]=st("",35).st($nit,20," ","L")."\n";

			$row_fact_det = $ci->Imprimir_model->get_factura_detalle($id_factura);

			$i = 11;
			foreach ($row_fact_det as $fila)
			{
				$cantidad=$fila->cantidad;
				$valor=number_format(round($fila->valor, 2),2,'.',',');
				$subtotal=number_format(round($fila->sub_total, 2),2,'.',',');
				$descripcion=$fila->descripcion;

				$array_data = array(
          0 => array($cantidad,150),
          1 => array($descripcion,37),
          2 => array($valor,150),
          3 => array($subtotal,150),
        );

				$data=array_procesor2($array_data);
        $total_lineas=count($data[0]["valor"]);
        $total_columnas=count($data);

				for ($w=0; $w < $total_lineas; $w++) {
					$arrayL[$i]=
					str_pad("",2," ",STR_PAD_LEFT)
					.str_pad($data[0]["valor"][$w],5," ",STR_PAD_BOTH)
					.str_pad_unicode(substr($data[1]["valor"][$w],0,37),37," ",STR_PAD_RIGHT)
					.str_pad(" ",2," ",STR_PAD_LEFT)
					.str_pad($data[2]["valor"][$w],6," ",STR_PAD_LEFT)
					.str_pad($data[3]["valor"][$w],16," ",STR_PAD_LEFT)."\n";
					$i ++;
        }
			}

			// $total_txt0=num2letras($total_final);

			list($entero,$decimal)=explode('.',$total_final);
			$enteros_txt=num2letras($entero);
			if(strlen($decimal)==1){
				$decimales_txt=$decimal."0";
			}
			else{
				$decimales_txt=$decimal;
			}

			$total_txt0= $enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
			$array_painc = array(
				0 => 17,
				1 => 18,
				2 => 19,
			);
			$array_nocon= dtl($total_txt0,28);
			foreach ($array_nocon as $key => $value) {
				// code...
				$arrayL[$array_painc[$key]]=st("",7).st($value,28," ","R").st("",25)."\n";
			}

			$arrayL[22]=st("",7).st($dui,28," ","R").st("",25)."\n";


			//19 16
			//22 19
			//27 24
			$arrayL[17]=substr($arrayL[17], 0,44).st($total,24," ","L")."\n";/*sumas*/
			// $arrayL[22]=substr($arrayL[22], 0,33).st($iva,24," ","L")."\n";/*vtas extentas*/
			$arrayL[20]=substr($arrayL[20], 0,44).st($total_final,24," ","L").Chr(27). chr(50)."\n";/*subtotal*/
			$arrayL[23]=substr($arrayL[23], 0,44).st($total_final,24," ","L")."\n";/*total*/

			//$value = $id_factura;
			foreach ($arrayL as $key => $value) {
				$info_factura.=$value;
			}

			// retornar valor generado en funcion
			return ($info_factura);
		}

	}
}

if (!function_exists('print_ccf')) {
	function print_ccf($id_fact,$tipo_id){
		// $this->load->model("Imprimir_model");
		$os = array("46", "52", "3005", "256","258","3003");
		$ci =& get_instance();
		$ci->load->model('Imprimir_model');
		$ci->load->helper('utilities_helper');
		// $this->load->model('');
		$info_factura = "";
		$id_sucursal=$_SESSION['id_sucursal'];

		if ($id_sucursal==1 || $id_sucursal==4 ) {
			// code...
			$id_factura=$id_fact;
			$tipo_id=$tipo_id;

			$logitud_array=58;
			$arrayL= array();
			for ($i=0; $i < $logitud_array; $i++) {
				// code...
				$arrayL[$i]=st("",60)."\n";
			}

			//Obtener informacion de tabla Factura
			// $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
			$row_fact = $ci->Imprimir_model->get_factura($id_factura);
			$id_cliente=$row_fact->id_cliente;
			$id_factura = $row_fact->id_factura;
			$id_usuario=$row_fact->id_usuario;
			$fecha=$row_fact->fecha;
			$total=number_format(round(($row_fact->total/1.13), 2),2,'.',',');
			$iva=number_format(round(($total*0.13), 2),2,'.',',');

			$total_final = number_format(round($total + $iva, 2), 2,'.',',');

			$dia = substr($fecha,8,2);
			$mes = substr($fecha,5,2);
			$a = substr($fecha,0,4);
			$fecha = "$dia-$mes-$a";


			$row_cliente = $ci->Imprimir_model->get_cliente($id_cliente);
			// $id_cliente=$row_cliente->id_cliente;
			$nombre=$row_cliente->nombre;
			$nit=$row_cliente->nit;
			$nrc=$row_cliente->nrc;

			$arrayL[9]=st("",6).st(substr($nombre,0,50),50," ","R")."\n";
			$arrayL[10]=st("",30).st($fecha,20," ","L")."\n";
			$arrayL[12]=st("",31).st($nrc,20," ","L")."\n";
			$arrayL[13]=st("",35).st($nit,20," ","L")."\n";

			$row_fact_det = $ci->Imprimir_model->get_factura_detalle($id_factura);

			$i = 21;
			foreach ($row_fact_det as $fila)
			{
				$cantidad=$fila->cantidad;
				$valor=number_format(round(($fila->valor/1.13), 2),2,'.',',');
				$subtotal=number_format(round(($fila->sub_total/1.13), 2),2,'.',',');
				$descripcion=$fila->descripcion;

				$array_data = array(
					0 => array($cantidad,150),
					1 => array($descripcion,27),
					2 => array($valor,150),
					3 => array($subtotal,150),
				);

				$data=array_procesor2($array_data);
				$total_lineas=count($data[0]["valor"]);
				$total_columnas=count($data);

				for ($w=0; $w < $total_lineas; $w++) {
					$arrayL[$i]=
					str_pad("",2," ",STR_PAD_LEFT)
					.str_pad($data[0]["valor"][$w],5," ",STR_PAD_BOTH)
					.str_pad_unicode(substr($data[1]["valor"][$w],0,27),27," ",STR_PAD_RIGHT)
					.str_pad($data[2]["valor"][$w],6," ",STR_PAD_LEFT)
					.str_pad($data[3]["valor"][$w],18," ",STR_PAD_LEFT)."\n";
					$i ++;
				}
			}

			// $total_txt0=num2letras($total_final);
			if (in_array($id_cliente, $os)) {
			   $retencion=number_format(round($total*0.01, 2),2,'.','');
			}
			else {
				// code...
				$retencion=number_format(round(0, 2),2,'.','');
			}
			list($entero,$decimal)=explode('.',number_format(round($total_final - $retencion, 2), 2,'.',''));
			$enteros_txt=num2letras($entero);
			if(strlen($decimal)==1){
				$decimales_txt=$decimal."0";
			}
			else{
				$decimales_txt=$decimal;
			}

			$total_txt0= $enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
			$array_painc = array(
				0 => 40,
				1 => 41,
				2 => 42,
			);
			$array_nocon= dtl($total_txt0,28);
			foreach ($array_nocon as $key => $value) {
				// code...
				$arrayL[$array_painc[$key]]=st("",7).st($value,28," ","R").st("",25)."\n";
			}


			//$retencion=number_format(round($total*0.01, 2),2,'.',',');

			$arrayL[40]=substr($arrayL[40], 0,33).st($total,25," ","L")."\n";/*sumas*/
			$arrayL[42]=substr($arrayL[42], 0,33).st($iva,25," ","L")."\n";/*vtas extentas*/
			$arrayL[44]=substr($arrayL[44], 0,33).st($total_final,25," ","L")."\n";/*subtotal*/
			$arrayL[46]=substr($arrayL[46], 0,33).st($retencion,25," ","L")."\n";/*iva retenido*/
			$arrayL[54]=substr($arrayL[54], 0,33).st(number_format(round($total_final-$retencion,2),2,'.',','),25," ","L")."\n";/*total*/

			//$value = $id_factura;
			foreach ($arrayL as $key => $value) {
				$info_factura.=$value;
			}

			// retornar valor generado en funcion
			return ($info_factura);
		}
		elseif ($id_sucursal==2) {
			// code... REG 2110
			$id_factura=$id_fact;
			$tipo_id=$tipo_id;

			$logitud_array=58;
			$arrayL= array();
			for ($i=0; $i < $logitud_array; $i++) {
				// code...
				$arrayL[$i]=st("",60)."\n";
			}

			//Obtener informacion de tabla Factura
			// $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
			$row_fact = $ci->Imprimir_model->get_factura($id_factura);
			$id_cliente=$row_fact->id_cliente;
			$id_factura = $row_fact->id_factura;
			$id_usuario=$row_fact->id_usuario;
			$fecha=$row_fact->fecha;

			$total=number_format(round(($row_fact->total/1.13), 2),2,'.',',');
			$iva=number_format(round(($total*0.13), 2),2,'.',',');

			$total_final = number_format(round($total + $iva, 2), 2,'.',',');


			// $total=number_format(round($row_fact->total, 2),2,'.',',');
			// $iva=number_format(round($row_fact->iva, 2),2,'.',',');
			// $retencion=number_format(round($row_fact->retencion, 2),2,'.',',');
			// $total_final = number_format(round($total + $iva, 2), 2,'.',',');

			$dia = substr($fecha,8,2);
			$mes = substr($fecha,5,2);
			$a = substr($fecha,0,4);
			$fecha = "$dia-$mes-$a";


			$row_cliente = $ci->Imprimir_model->get_cliente($id_cliente);
			// $id_cliente=$row_cliente->id_cliente;
			$nombre=$row_cliente->nombre;
			$nit=$row_cliente->nit;
			$nrc=$row_cliente->nrc;

			$arrayL[8]=st("",10).st(substr($nombre,0,50),50," ","R")."\n";
			$arrayL[9]=st("",35).st($fecha,20," ","L")."\n";
			$arrayL[11]=st("",35).st($nrc,20," ","L")."\n";
			$arrayL[13]=st("",40).st($nit,20," ","L")."\n";

			$row_fact_det = $ci->Imprimir_model->get_factura_detalle($id_factura);

			$i = 20;
			foreach ($row_fact_det as $fila)
			{
				$cantidad=$fila->cantidad;
				$valor=number_format(round($fila->valor/1.13, 2),2,'.',',');
				$subtotal=number_format(round($fila->sub_total/1.13, 2),2,'.',',');
				$descripcion=$fila->descripcion;

				$array_data = array(
					0 => array($cantidad,150),
					1 => array($descripcion,30),
					2 => array($valor,150),
					3 => array($subtotal,150),
				);

				$data=array_procesor2($array_data);
				$total_lineas=count($data[0]["valor"]);
				$total_columnas=count($data);

				for ($w=0; $w < $total_lineas; $w++) {
					$arrayL[$i]=
					str_pad("",4," ",STR_PAD_LEFT)
					.str_pad($data[0]["valor"][$w],5," ",STR_PAD_BOTH)
					.str_pad_unicode(substr($data[1]["valor"][$w],0,30),30," ",STR_PAD_RIGHT)
					.str_pad($data[2]["valor"][$w],6," ",STR_PAD_LEFT)
					.str_pad($data[3]["valor"][$w],16," ",STR_PAD_LEFT)."\n";
					$i ++;
				}
			}

			// $total_txt0=num2letras($total_final);
			if (in_array($id_cliente, $os)) {
			   $retencion=number_format(round($total*0.01, 2),2,'.','');
			}
			else {
				// code...
				$retencion=number_format(round(0, 2),2,'.','');
			}
			list($entero,$decimal)=explode('.',number_format(round($total_final - $retencion, 2), 2,'.',''));
			$enteros_txt=num2letras($entero);
			if(strlen($decimal)==1){
				$decimales_txt=$decimal."0";
			}
			else{
				$decimales_txt=$decimal;
			}

			$total_txt0= $enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
			$array_painc = array(
				0 => 40,
				1 => 41,
				2 => 42,
			);
			$array_nocon= dtl($total_txt0,28);
			foreach ($array_nocon as $key => $value) {
				// code...
				$arrayL[$array_painc[$key]]=st("",9).st($value,28," ","R").st("",25)."\n";
			}

			//$retencion=number_format(round($total*0.01, 2),2,'.',',');

			$arrayL[39]=substr($arrayL[39], 0,36).st($total,25," ","L")."\n";/*sumas*/
			$arrayL[41]=substr($arrayL[41], 0,36).st($iva,25," ","L")."\n";/*vtas extentas*/
			$arrayL[43]=substr($arrayL[43], 0,36).st($total_final,25," ","L")."\n";/*subtotal*/
			$arrayL[45]=substr($arrayL[45], 0,36).st($retencion,25," ","L")."\n";/*iva retenido*/
			$arrayL[53]=substr($arrayL[53], 0,36).st(number_format(round($total_final-$retencion,2),2,'.',','),25," ","L")."\n";/*total*/

			//$value = $id_factura;
			foreach ($arrayL as $key => $value) {
				$info_factura.=$value;
			}

			// retornar valor generado en funcion
			return ($info_factura);
		}
		else {
			// code...
			$id_factura=$id_fact;
			$tipo_id=$tipo_id;

			$logitud_array=58;
			$arrayL= array();
			for ($i=0; $i < $logitud_array; $i++) {
				// code...
				$arrayL[$i]=st("",60)."\n";
			}

			//Obtener informacion de tabla Factura
			// $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
			$row_fact = $ci->Imprimir_model->get_factura($id_factura);
			$id_cliente=$row_fact->id_cliente;
			$id_factura = $row_fact->id_factura;
			$id_usuario=$row_fact->id_usuario;
			$fecha=$row_fact->fecha;

			$total=number_format(round(($row_fact->total/1.13), 2),2,'.',',');
			$iva=number_format(round(($total*0.13), 2),2,'.',',');

			$total_final = number_format(round($total + $iva, 2), 2,'.',',');

			// $total=number_format(round($row_fact->total, 2),2,'.',',');
			// $iva=number_format(round($row_fact->iva, 2),2,'.',',');
			// $retencion=number_format(round($row_fact->retencion, 2),2,'.',',');
			// $total_final = number_format(round($total + $iva, 2), 2,'.',',');

			$dia = substr($fecha,8,2);
			$mes = substr($fecha,5,2);
			$a = substr($fecha,0,4);
			$fecha = "$dia-$mes-$a";


			$row_cliente = $ci->Imprimir_model->get_cliente($id_cliente);
			// $id_cliente=$row_cliente->id_cliente;
			$nombre=$row_cliente->nombre;
			$nit=$row_cliente->nit;
			$nrc=$row_cliente->nrc;

			$arrayL[9]=st("",10).st(substr($nombre,0,50),50," ","R")."\n";
			$arrayL[10]=st("",35).st($fecha,20," ","L")."\n";
			$arrayL[12]=st("",35).st($nrc,20," ","L")."\n";
			$arrayL[14]=st("",40).st($nit,20," ","L")."\n";

			$row_fact_det = $ci->Imprimir_model->get_factura_detalle($id_factura);

			$i = 21;
			foreach ($row_fact_det as $fila)
			{
				$cantidad=$fila->cantidad;
				$valor=number_format(round($fila->valor/1.13, 2),2,'.',',');
				$subtotal=number_format(round($fila->sub_total/1.13, 2),2,'.',',');
				$descripcion=$fila->descripcion;

				$array_data = array(
					0 => array($cantidad,150),
					1 => array($descripcion,30),
					2 => array($valor,150),
					3 => array($subtotal,150),
				);

				$data=array_procesor2($array_data);
				$total_lineas=count($data[0]["valor"]);
				$total_columnas=count($data);

				for ($w=0; $w < $total_lineas; $w++) {
					$arrayL[$i]=
					str_pad("",3," ",STR_PAD_LEFT)
					.str_pad($data[0]["valor"][$w],5," ",STR_PAD_BOTH)
					.str_pad_unicode(substr($data[1]["valor"][$w],0,30),30," ",STR_PAD_RIGHT)
					.str_pad($data[2]["valor"][$w],6," ",STR_PAD_LEFT)
					.str_pad($data[3]["valor"][$w],16," ",STR_PAD_LEFT)."\n";
					$i ++;
				}



			}

			// $total_txt0=num2letras($total_final);
			if (in_array($id_cliente, $os)) {
			   $retencion=number_format(round($total*0.01, 2),2,'.','');
			}
			else {
				// code...
				$retencion=number_format(round(0, 2),2,'.','');
			}
			list($entero,$decimal)=explode('.',number_format(round($total_final - $retencion, 2), 2,'.',''));
			$enteros_txt=num2letras($entero);
			if(strlen($decimal)==1){
				$decimales_txt=$decimal."0";
			}
			else{
				$decimales_txt=$decimal;
			}

			$total_txt0= $enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
			$array_painc = array(
				0 => 40,
				1 => 41,
				2 => 42,
			);
			$array_nocon= dtl($total_txt0,28);
			foreach ($array_nocon as $key => $value) {
				// code...
				$arrayL[$array_painc[$key]]=st("",8).st($value,28," ","R").st("",25)."\n";
			}

			//$retencion=number_format(round($total*0.01, 2),2,'.',',');

			$arrayL[40]=substr($arrayL[40], 0,36).st($total,24," ","L")."\n";/*sumas*/
			$arrayL[42]=substr($arrayL[42], 0,36).st($iva,24," ","L")."\n";/*vtas extentas*/
			$arrayL[44]=substr($arrayL[44], 0,36).st($total_final,24," ","L")."\n";/*subtotal*/
			$arrayL[46]=substr($arrayL[46], 0,36).st($retencion,24," ","L")."\n";/*iva retenido*/
			$arrayL[54]=substr($arrayL[54], 0,36).st(number_format(round($total_final-$retencion,2),2,'.',','),24," ","L")."\n";/*total*/

			//$value = $id_factura;
			foreach ($arrayL as $key => $value) {
				$info_factura.=$value;
			}

			// retornar valor generado en funcion
			return ($info_factura);
		}
}
}
if (!function_exists('print_abono')) {
	function print_abono($id_fact,$tipo_id){
		$esp_tot_fin=espacios_izq(" ",100);
		$text="Hola id".$id_fact." y tipo ".$esp_tot_fin."".$tipo_id;
		return ($text);
	}
}




function str_pad_unicode($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT) {
    $str_len = mb_strlen($str);
    $pad_str_len = mb_strlen($pad_str);
    if (!$str_len && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
        $str_len = 1; // @debug
    }
    if (!$pad_len || !$pad_str_len || $pad_len <= $str_len) {
        return $str;
    }

    $result = null;
    $repeat = ceil($str_len - $pad_str_len + $pad_len);
    if ($dir == STR_PAD_RIGHT) {
        $result = $str . str_repeat($pad_str, $repeat);
        $result = mb_substr($result, 0, $pad_len);
    } else if ($dir == STR_PAD_LEFT) {
        $result = str_repeat($pad_str, $repeat) . $str;
        $result = mb_substr($result, -$pad_len);
    } else if ($dir == STR_PAD_BOTH) {
        $length = ($pad_len - $str_len) / 2;
        $repeat = ceil($length / $pad_str_len);
        $result = mb_substr(str_repeat($pad_str, $repeat), 0, floor($length))
                    . $str
                       . mb_substr(str_repeat($pad_str, $repeat), 0, ceil($length));
    }

    return $result;
}



function st($input,$lengt,$carac=" ",$di="R")
{
	// code..
	$r = "";
	switch ($di) {
		case 'L':
		// code...
		$r=str_pad_unicode($input, $lengt, $carac, STR_PAD_LEFT);
		break;
		case 'R':
		// code...
		$r=str_pad_unicode($input, $lengt, $carac, STR_PAD_RIGHT);
		break;
		case 'B':
		// code...
		$r=str_pad_unicode($input, $lengt, $carac, STR_PAD_BOTH);
		break;
		default:
		// code...
		break;
	}
	return $r;
}

function dtl( $text, $width = '80', $lines = '10', $break = '\n', $cut = 0 ) {
	$wrappedarr = array();
	$wrappedtext = wordwrap( $text, $width, $break , true );
	$wrappedtext = trim( $wrappedtext );
	$arr = explode( $break, $wrappedtext );
	return $arr;
}

if (!function_exists('print_examen')) {
	function print_examen($id){
				$info_factura="";
				$ci =& get_instance();
				$ci->load->model('Utils_model');
				$ci->load->model('Examen_model');
				$data = $ci->Examen_model->get_datos_exa($id);
				$id_cliente = $data->id_cliente;
				$nombre = $data->nombre;
				$edad = $data->edad;
				$sexo = $data->sexo;
				$esfd = $data->esfd;
				$cild = $data->cild;
				$ejed = $data->ejed;
				$adid = $data->adid;
				$esfi = $data->esfi;
				$cili = $data->cili;
				$ejei = $data->ejei;
				$adii = $data->adii;
				$di = $data->di;
				$ad = $data->ad;
				$color_lente = $data->color_lente;
				$bif = $data->bif;
				$aro = $data->aro;
				$tamanio = $data->tamanio;
				$color_aro = $data->color_aro;
				$id_ref = $data->id;
				$observaciones = $data->observaciones;
				$sucursal = $data->nombre_sucursal;
				$telefono = $data->telefono;
				$direccion = $data->direccion;
				$optometrista = $data->optometrista;

				$fee = $data->fecha;

				if ($fee=="" || $fee =="0000-00-00") {
					// code...
					$fee=Date("d/m/y");
				}
				else {
					$fee = d_m_Y($fee);
				}
				$logitud_array=42;
				$arrayL= array();
				for ($i=0; $i < $logitud_array; $i++) {
					// code...
					$arrayL[$i]=st("",90)."\n";
				}
				$arriba="-";
				$l="|";
				$arrayL[0]=st(utf8_decode($sucursal),90," ","B")."\n";
				$arrayL[1]=st(utf8_decode("TODO EN SALUD VISUAL"),90," ","B")."\n";
				$arrayL[2]=st(utf8_decode($direccion),70," ","R").st(utf8_decode("TEL: ").utf8_decode($telefono),20," ","L")."\n";
				$arrayL[3]=st("",90,$arriba)."\n";
				$arrayL[4]=
				$l.
				"REFERENCIA:".st($id_ref,10," ","L").
				$l.
				"ID CLIENTE:".st($id_cliente,10," ","L").
				$l.
				"SEXO:".st(strtoupper(mb_strtolower($sexo)),16," ","L").
				$l.
				"EDAD:".st(utf8_decode(($edad . " AÑOS ")),16," ","L").
				$l."\n"
				;

				$arrayL[5]=st("",90,$arriba)."\n";

				$arrayL[6]=
				$l.
				"NOMBRE:".st(utf8_decode(strtoupper(mb_strtolower($nombre))),58," ","L"). //66
				$l.
				"FECHA:".st(strtoupper(mb_strtolower($fee)),16," ","L").
				$l."\n"
				;

				$arrayL[7]=st("",90,$arriba)."\n";

				$arrayL[8]=
				$l.
				 st("",16," ","B").
				$l.
				 st("ESF",17," ","B").
				$l.
				 st("CIL",17," ","B").
				$l.
				 st("EJE",17," ","B").
				$l.
				 st("ADICION",17," ","B").
				$l."\n"
				;

				$arrayL[9]=st("",90,$arriba)."\n";

				$arrayL[10]=
				$l.
				 st("O.D",16," ","B").
				$l.
				 st($esfd,17," ","B").
				$l.
				 st($cild,17," ","B").
				$l.
				 st($ejed,17," ","B").
				$l.
				 st($adid,17," ","B").
				$l."\n"
				;

				$arrayL[11]=st("",90,$arriba)."\n";

				$arrayL[12]=
				$l.
				 st("O.I",16," ","B").
				$l.
				 st($esfi,17," ","B").
				$l.
				 st($cili,17," ","B").
				$l.
				 st($ejei,17," ","B").
				$l.
				 st($adii,17," ","B").
				$l."\n"
				;

				$arrayL[13]=st("",90,$arriba)."\n";

				$i = 14;

				$array_data = array(
					0 => array("D.I:".st(intval($di),12," ","L"),16),
					1 => array("A.D:".st("",8," ","L"),12),
					2 => array("MATERIAL:".st(utf8_decode(strtoupper(mb_strtolower($color_lente))),19," ","L"),29),
					3 => array("TIPO LENTE:".st(utf8_decode(strtoupper(mb_strtolower($bif))),17," ","L"),28),
				);

				$data=array_procesor2($array_data);
				$total_lineas=count($data[0]["valor"]);
				$total_columnas=count($data);
				for ($w=0; $w < $total_lineas; $w++) {

					$arrayL[$i]=
					$l.
					str_pad_unicode($data[0]["valor"][$w],16," ",STR_PAD_RIGHT).
					$l.
					str_pad_unicode($data[1]["valor"][$w],12," ",STR_PAD_RIGHT).
					$l.
					str_pad_unicode($data[2]["valor"][$w],29," ",STR_PAD_RIGHT).
					$l.
					str_pad_unicode($data[3]["valor"][$w],28," ",STR_PAD_RIGHT).
					$l."\n"
					;
					$i ++;
				}

				$arrayL[$i]=st("",90,$arriba)."\n";

				$i++;
				$array_data = array(
					0 => array("ARO: ".utf8_decode(strtoupper(mb_strtolower($aro))),43),//39
					1 => array(("TAMAñO: ").strtoupper(mb_strtolower($tamanio)),21), //14
					2 => array("COLOR: ".utf8_decode(($color_aro)),22),//12
				);
				$data=array_procesor2($array_data);
				$total_lineas=count($data[0]["valor"]);
				$total_columnas=count($data);
				for ($w=0; $w < $total_lineas; $w++) {

					$arrayL[$i]=
					$l.
					str_pad_unicode($data[0]["valor"][$w],43," ",STR_PAD_RIGHT).
					$l.
					utf8_decode(str_pad_unicode($data[1]["valor"][$w],21," ",STR_PAD_RIGHT)).
					$l.
					str_pad_unicode($data[2]["valor"][$w],22," ",STR_PAD_RIGHT).
					$l."\n"
					;
					$i ++;
				}
				$arrayL[$i]=st("",90,$arriba)."\n";
				$i++;

				$array_data = array(
					0 =>array("OBSERVACIONES: ".st(utf8_decode(strtoupper(mb_strtolower($observaciones))),73," ","R"),88),//88
				);

				$data=array_procesor2($array_data);
				$total_lineas=count($data[0]["valor"]);
				$total_columnas=count($data);
				for ($w=0; $w < $total_lineas; $w++) {

					$arrayL[$i]=
					$l.
					str_pad_unicode($data[0]["valor"][$w],88," ",STR_PAD_RIGHT).
					$l."\n"
					;
					$i ++;
				}

				$arrayL[$i]=st("",90,$arriba)."\n";
				$i++;

				$arrayL[$i]=
				" ".
				"OPTOMETRISTA:".st(utf8_decode(strtoupper(mb_strtolower($optometrista))),76," ","R").
				"\n"
				;

				for ($j=1; $j < 23; $j++) {
					// code...
						$arrayL[$i+$j]="\n";
				}

				foreach ($arrayL as $key => $value) {
					$info_factura.=$value;
				}

				return ($info_factura);
	}
}

if (!function_exists('print_examen_formato')) {
	function print_examen_formato($id){
		$info_factura="";
		$ci =& get_instance();
		$ci->load->model('Utils_model');
		$ci->load->model('Examen_model');
		$data = $ci->Examen_model->get_datos_exa($id);
		$id_cliente = $data->id_cliente;
		$nombre = $data->nombre;
		$edad = $data->edad;
		$sexo = $data->sexo;
		$esfd = $data->esfd;
		$cild = $data->cild;
		$ejed = $data->ejed;
		$adid = $data->adid;
		$esfi = $data->esfi;
		$cili = $data->cili;
		$ejei = $data->ejei;
		$adii = $data->adii;
		$di = $data->di;
		$ad = $data->ad;
		$color_lente = $data->color_lente;
		$bif = $data->bif;
		$aro = $data->aro;
		$tamanio = $data->tamanio;
		$color_aro = $data->color_aro;
		$id_ref = $data->id;
		$observaciones = $data->observaciones;
		$sucursal = $data->nombre_sucursal;
		$telefono = $data->telefono;
		$direccion = $data->direccion;
		$optometrista = $data->optometrista;

		$fee = $data->fecha;

		if ($fee=="" || $fee =="0000-00-00") {
			// code...
			$fee=Date("d/m/y");
		}
		else {
			$fee = d_m_Y($fee);
		}

		$logitud_array=31;
		$arrayL= array();
		for ($i=0; $i < $logitud_array; $i++) {
			// code...
			$arrayL[$i]=st("",95)."\n";
		}



		$arrayL[15]=st("",70," ","B").st(strtoupper(mb_strtolower($fee)),20," ","R")."\n";

		$arrayL[18]= st("",12," ","B").st(utf8_decode(strtoupper(mb_strtolower($nombre))),78," ","R");

		$arrayL[25]=
		st("",12," ","B").
		" ".//13
		 st($esfd,14," ","B").
		" ".//28
		 st($cild,15," ","B").
		" ".//44
		 st($ejed,15," ","B").
		" ".//60
		 st($adid,34," ","B").
		" "."\n"
		;

		$arrayL[29]=
		st("",12," ","B").
		" ".
		 st($esfi,14," ","B").
		" ".
		 st($cili,15," ","B").
		" ".
		 st($ejei,15," ","B").
		" ".
		 st($adii,34," ","B").
		" "."\n"
		;

		foreach ($arrayL as $key => $value) {
			$info_factura.=$value;
		}
		return ($info_factura);

	}
}

function array_procesor2($array)
{
    $ygg=0;
    $maxlines=1;
    $array_a_retornar=array();
    foreach ($array as $key => $value) {
      /*Descripcion*/
      $nombr=$value[0];
      /*character*/
      $longitud=$value[1];

      if(strlen($nombr) > $longitud)
      {
        $i=0;
        $nom = divtextlin($nombr, $longitud);
        foreach ($nom as $nnon)
        {
          $array_a_retornar[$ygg]["valor"][]=$nnon;
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
        $ygg++;

      }
    }

    $ygg=0;
    foreach($array_a_retornar as $keys)
    {
      for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
        // code...
        $array_a_retornar[$ygg]["valor"][]="";
      }
      $ygg++;
    }
    return $array_a_retornar;

  }


?>
