
$(document).ready(function () {
	$('.select').select2();
	$('#editable2').DataTable({
		"pageLength": 50,
		"serverSide": true,
		"order": [[0, "asc"]],
		"ajax": {
			url: 'Corte_caja/get_data',
			type: 'POST'
		},
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		},
	}); // End of DataTable




});
$(function () {
	// Clean the modal form
	$(document).on('hidden.bs.modal', function (e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});
});

function corte()
{
	var ingresos = $("#ingresos").text();
	var abonos = $("#abonos").text();
	var otros_ingresos = $("#otros_ingresos").text();
	var egresos = $("#egresos").text();
	var total_efectivo = $("#total_efectivo").text();
	var efectivo_caja = $("#efectivo_caja").val();
	var observaciones = $("#observaciones").val();
	var ajaxdata = "ingresos="+ingresos+"&abonos="+abonos+"&otros_ingresos="+otros_ingresos;
	ajaxdata +="&egresos="+egresos+"&total_efectivo="+total_efectivo+"&efectivo_caja="+efectivo_caja;
	ajaxdata +="&observaciones="+observaciones;
	if (efectivo_caja!="") {
		$.ajax({
			type:'POST',
			url:base_url+"Corte_caja/insertar",
			data: ajaxdata,
			dataType: 'json',
			success: function(datax)
			{
				display_notify(datax.typeinfo, datax.msg);
				if(datax.typeinfo == "Success")
				{
					setTimeout("location.replace('"+datax.url+"');",1000);
				}
			}
		});
	}else {
		display_notify("Warning", "Agregue el efectivo en caja");

	}

}
$(document).on("click","#btn_corte", function()
{
	corte();

});
