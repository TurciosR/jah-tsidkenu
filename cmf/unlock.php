<?php
include ("_core.php");

function initial(){
	if(true)
	{
		$a= uniqid();
		$b= uniqid();
		$c= uniqid();
		$habi = $_REQUEST['u'];
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Habilitar edición de precio</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Codigo</label>
			<input type="password" class='form-control <?=$b ?>' id="<?=$b?>" name="<?=$b?>" value="">
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary <?=$a?>" id="<?=$a ?>">Habilitar</button>
	<button type="button" class="btn btn-default <?=$c?>" data-dismiss="modal">Cerrar</button>
</div>
<script type="text/javascript">
$(document).on('click', '.<?=$a?>', function(event) {
	var pass = $('.<?=$b?>').val();
	$.ajax({
		url: 'unlock.php',
		type: 'POST',
		dataType: 'json',
		data: {process: 'habilitar',code:pass},
		success: function(xdatos)
		{
			display_notify(xdatos.typeinfo,xdatos.msg)
			if(xdatos.typeinfo == 'Success')
			{
				$(".<?=$habi?>").find("#precio_venta").removeAttr('readonly');
				$(".<?=$c?>").click();
			}
		}
	});
});
</script>

<?php


}
else
{
	echo "<div></div><br><br><div class='alert alert-warning text-center'>No se ha encontrado una apertura vigente.</div>";
}
}

function habilitar()
{
	$precode = "newprice";

	if($precode == $_REQUEST['code'])
	{
		$xdatos['typeinfo']='Success';
		$xdatos['msg']='Habilitando edicion !';
	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Codigo invalido';
	}
	echo json_encode($xdatos);
}

if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'formDelete' :
				initial();
				break;
			case 'habilitar' :
				habilitar();
				break;
		}
	}
}

?>
