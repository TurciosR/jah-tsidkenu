
$(document).ready(function () {
	$('.select').select2();
	$('#editable2').DataTable({
		"pageLength": 50,
		"serverSide": true,
		"order": [[0, "asc"]],
		"ajax": {
			url: 'Caja/get_data',
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


function agregar_ingreso() {
	let form = $("#form_ingreso");
	let formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	$.ajax({
		type: 'POST',
		url: base_url+'Caja/agregar_ingreso',
		cache: false,
		data: formdata ? formdata : form.serialize(),
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function (datax) {
			display_notify(datax.typeinfo, datax.msg);
			if (datax.typeinfo == "Success") {
				setTimeout("location.replace('"+datax.url+"');",1000);
			}
		}
	});
}

function agregar_egreso() {
	let form = $("#form_egreso");
	let formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	$.ajax({
		type: 'POST',
		url: base_url+'Caja/agregar_egreso',
		cache: false,
		data: formdata ? formdata : form.serialize(),
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function (datax) {
			display_notify(datax.typeinfo, datax.msg);
			if (datax.typeinfo == "Success") {
				setTimeout("location.replace('"+datax.url+"');",1000);
			}
		}
	});
}

function editar_movimiento() {
	let form = $("#form_editarmovimiento");
	let formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	$.ajax({
		type: 'POST',
		url: base_url+'Caja/editar_movimiento',
		cache: false,
		data: formdata ? formdata : form.serialize(),
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function (datax) {
			display_notify(datax.typeinfo, datax.msg);
			if (datax.typeinfo == "Success") {
				setTimeout("location.replace('"+datax.url+"');",1000);
			}
		}
	});
}

$(document).on("click", ".eliminar", function (event) {
	var id = $(this).attr("data");
	dataString = "id=" + id;
	swal({
			title: "Eliminar",
			text: "Esta seguro que desea eliminar este movimiento?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Si, Continuar",
			cancelButtonText: "No, Cerrar",
			closeOnConfirm: true
		},
		function () {
			$.ajax({
				type: "POST",
				url: "Caja/eliminar_movimento",
				data: dataString,
				dataType: 'json',
				success: function (datax) {
					display_notify(datax.typeinfo, datax.msg);
					if (datax.typeinfo == "Success") {
						setTimeout("location.replace('"+datax.url+"');",1000);
					}
				}
			});
		});

});
