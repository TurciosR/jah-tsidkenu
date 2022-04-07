<?php
if(!function_exists("validar_session"))
{
	function validar_session($obj)
	{
		if ($obj->session->logged_in == "") { redirect('Login', 'refresh'); }
	}
}
?>
