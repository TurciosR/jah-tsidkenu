<?php
include '../_conexion.php';
function confirm()
{
	$id_activo = file_get_contents('http://demos.apps-oss.com/sms/get.php');
	$sql_host = _query("SELECT ruta AS url FROM rutas WHERE id='$id_activo'");
	$datos_host = _fetch_array($sql_host);
	$host = $datos_host["url"];
	$puerto = "8000";
	if($id_activo == "1")
	{
		$puerto = "8082";
	}
	$datos_post = http_build_query(
	    array(
	        'proceso' => '1',
	        'cliente' => 'LA FE 1',
	    )
	);

	$opciones = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $datos_post
	    )
	);

	$contexto = stream_context_create($opciones);
	$resultado = file_get_contents($host.'/confirm.php', false, $contexto);
}
if(!isset($_POST['process']))
{
    confirm();
}
else
{
    if(isset($_POST['process']))
    {
        switch ($_POST['process'])
        {
            case 'confirm':
                confirm();
                break;
        }
    }
}
?>
