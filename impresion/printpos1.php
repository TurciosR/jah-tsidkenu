<?php
/* Este script es el que se redirecciona a localhost donde esta el printer
y debe haber un apache corriendo con soporte php
Agregar el usuario al grupo en debian
usermod -a -G lp www-data
Permisos al puerto
su -c 'chmod 777 /dev/usb/lp0'
*/
header("Access-Control-Allow-Origin: *");


$line1=str_repeat("_",42)."\n";
/*
$puerto=system('ls /dev/usb/lp*');
if ($puerto=='/dev/usb/lp0')
	$printer="/dev/usb/lp0";
else
	$printer="/dev/usb/lp1";
*/
//printer set con udev rules
$printer="/dev/Bematech";	
$totales= $_REQUEST['totales'];
$total_letras = $_REQUEST['total_letras'];
$e= strtoupper($_REQUEST['encabezado']);
$c = strtoupper($_REQUEST['cuerpo']);
$p = strtoupper($_REQUEST['pie']);
$efectivo = $_REQUEST['efectivo'];
$cambio = $_REQUEST['cambio'];

try {
	//iniciar string
	
$string="";
	$latinchars = array( 'ñ','á','é', 'í', 'ó','ú','ü','Ñ','Á','É','Í','Ó','Ú','Ü','°');
	$encoded = array("\xa5","A","E","I","O","U","\x9a","\xa5","A","E","I","O","U","\x9a","\xf8");
	$encabezado = str_replace($latinchars, $encoded, $e);
	$cuerpo = str_replace($latinchars, $encoded, $c);
	$pie = str_replace($latinchars, $encoded, $p);

	$string.= chr(27).chr(64); // Reset to defaults	
	$string.= chr(27).chr(97).chr(1); //Center
	$string.=chr(13).$encabezado."\n";
	$string.= chr(27).chr(97).chr(0); //Left
	$string.=chr(13).$cuerpo."\n";
	$string.= chr(27).chr(97).chr(2); //Right
	$string.=chr(13).$totales."\n";
	$string.= chr(27).chr(97).chr(1); //Center
	$string.=chr(13).$total_letras."\n";
	if ($efectivo>0){
		$efectivo=sprintf("%.2f", $efectivo);
		$cambio=sprintf("%.2f", $cambio);
		$string.=chr(13)."EFECTIVO $ ".$efectivo."  CAMBIO   $ ".$cambio."\n"; // Print text
	}
	$string.= chr(27).chr(33).chr(1); //FONT B
	$string.=chr(13).$pie."\n";
	
	for($n=0;$n<3;$n++){
		$string.=chr(13)."\n"; // Print text
	}
	
	$string.=chr(10); //Line Feed
	$string.=chr(29).chr(86)."1";  // CORTAR PAPEL AUTOMATICO
	$string.= chr(16).chr(4).chr(0); //open drawer
	$string.=chr(27).chr(112)."0"."25";  // Abrir cajon
	//FIN ENVIO DATOS COMUN LINUX WIN
	//send data to USB printer
	$fp0=fopen($printer, 'wb');
	fwrite($fp0,$string);
	fclose($fp0);

}
 catch (Throwable $t) {
    // Executed only in PHP 7, will not match in PHP 5.x
     echo 'NO SE PUDO IMPRIMIR: " Excepción capturada: ',  $t->getMessage(), "\n";
} catch (Exception $e) {
    // Executed only in PHP 5.x, will not be reached in PHP 7
    echo 'NO SE PUDO IMPRIMIR: " Excepción capturada: ',  $e->getMessage(), "\n";
}
?>
