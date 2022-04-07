
$(document).ready(function () {
	//calcular_fecha_nac();
	$('.select').select2();
	$('#editable2').DataTable({
		"pageLength": 50,
		"serverSide": true,
		"order": [[0, "asc"]],
		"ajax": {
			url: 'Cliente/get_data',
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

	$('#formulario').validate({
		rules: {
			nombre: {
				required: true,
			},
			edad: {
				required: true,
			},
			sexo: {
				required: true,
			},
		},
		messages: {
			nombre: "Por favor ingrese el nombre",
			edad: "Por favor ingrese la edad",
			sexo: "Por favor seleccione el sexo",
		},
		submitHandler: function (form) {
			$("#submit1" ).prop( "disabled", true);
			save();
		}
	});
	$('#formulario2').validate({
		rules: {
			nombre: {
				required: true,
			},
			edad: {
				required: true,
			},
			sexo: {
				required: true,
			},
		},
		messages: {
			nombre: "Por favor ingrese el nombre",
			edad: "Por favor ingrese la edad",
			sexo: "Por favor seleccione el sexo",
		},
		submitHandler: function (form) {
			$("#submit1" ).prop( "disabled", true);
			update();
		}
	});


});
$(function () {
	//binding event click for button in modal form
	$(document).on("click", ".desactivar", function (event) {
		var id = $(this).attr("data");
		desactivar(id);
	});
	$(document).on("click", ".activar", function (event) {
		var id = $(this).attr("data");
		activar(id);
	});

	// Clean the modal form
	$(document).on('hidden.bs.modal', function (e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});
	$(document).on('click', '.lndelete', function(e) {
		$(this).closest('tr').remove();
	});

	$(document).on("submit", "#formulario", function (e) {
		e.preventDefault();
		save();
	});

	$(document).on("submit", "#formulario2", function (e) {
		e.preventDefault();
		update();
	});

});
$(document).on('change', '#departamento', function(event) {
	$("#municipio *").remove();
	$("#select2-municipio-container").text("");
	var ajaxdata = {
		"id_departamento": $("#departamento").val()
	};
	$.ajax({
		url: base_url+'Cliente/municipio',
		type: "POST",
		data: ajaxdata,
		success: function(opciones) {
			$("#select2-municipio-container").text("Seleccione");
			$("#municipio").html(opciones);
			$("#municipio").val();
		}
	})
});

function save() {
	let form = $("#formulario");
	let formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	$.ajax({
		type: 'POST',
		url: base_url+'Cliente/insertar_cliente',
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
			else {
				$("#submit1" ).prop( "disabled", false);
			}
		}
	});
}
function update() {
	let form = $("#formulario2");
	let formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	$.ajax({
		type: 'POST',
		url:  base_url+'Cliente/editar_cliente',
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
			else {
				$("#submit1" ).prop( "disabled", false);
			}
		}
	});
}

$(document).on("click", ".editar_examen",function(){
	var id_examen = $(this).attr("data");
	var aro = $("#aro"+id_examen).val();



		senddata_editar(id_examen);


});

function senddata_editar(id_examen)
{
	var esfd = $("#esfd"+id_examen).val();
	var esfi = $("#esfi"+id_examen).val();
	var cild = $("#cild"+id_examen).val();
	var cili = $("#cili"+id_examen).val();
	var ejed = $("#ejed"+id_examen).val();
	var ejei = $("#ejei"+id_examen).val();
	var adid = $("#adid"+id_examen).val();
	var adii = $("#adii"+id_examen).val();
	var di = $("#di"+id_examen).val();
	var ad = $("#ad"+id_examen).val();
	var sucursal = $("#sucursal"+id_examen).val();
	var color_lente = $("#color_lente"+id_examen).val();
	var bif = $("#bif"+id_examen).val();
	var fecha = $("#fecha"+id_examen).val();
	var aro = $("#aro"+id_examen+" option:selected").text();
	var id_aro = $("#aro"+id_examen).val();
	var color_aro = $("#color_aro"+id_examen).val();
	var observaciones = $("#observaciones"+id_examen).val();
	var tamanio = $("#tamanio"+id_examen).val();
	var id_cliente = $("#id_cliente").val();
	var nombre_cli = $("#nombre").val();

	var stringData = "id_examen="+id_examen+"&esfd="+esfd+"&esfi="+esfi+"&cild="+cild+"&cili="+cili;
	stringData += "&ejed="+ejed+"&ejei="+ejei+"&adid="+adid+"&adii="+adii+"&di="+di+"&ad="+ad;
	stringData += "&color_lente="+color_lente+"&bif="+bif+"&aro="+aro+"&color_aro="+color_aro+"&observaciones="+observaciones+"&id_cliente="+id_cliente;
	stringData += "&tamanio="+tamanio+"&id_aro="+id_aro+"&nombre_cli="+nombre_cli+"&sucursal="+sucursal+"&fecha="+fecha;
	$.ajax({
		type: 'POST',
		url: base_url+"Cliente/guardar_examen",
		data: stringData,
		dataType: 'JSON',
		success: function(datax)
		{
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setTimeout("location.replace('"+datax.url+"');",1000);
			}
		}
	});
}
$(document).on("keyup","#di",function(evt){
	if(evt.keyCode == 13)
	{
		$("#ad").focus();
	}
});
$(document).on("keyup","#ad",function(evt){
	if(evt.keyCode == 13)
	{
		$("#color_lente").focus();
	}
});
$(document).on("keyup","#color_lente",function(evt){
	if(evt.keyCode == 13)
	{
		$("#bif").focus();
	}
});
$(document).on("keyup","#bif",function(evt){
	if(evt.keyCode == 13)
	{
		$("#aro").focus();
	}
});
$(document).on("keyup","#aro",function(evt){
	if(evt.keyCode == 13)
	{
		$("#color_aro").focus();
	}
});
$(document).on("keyup","#color_aro",function(evt){
	if(evt.keyCode == 13)
	{
		$("#tamanio").focus();
	}
});
$(document).on("keyup","#tamanio",function(evt){
	if(evt.keyCode == 13)
	{
		$("#observaciones").focus();
	}
});
$(document).on("click",".day",function(evt){
	//alert("hola");
	//procedemos a calcular la edad en base a la fecha de nacimiento
	var edad = $("#edad").val();
	$.ajax({
		type: 'POST',
		url: base_url+"Cliente/calcular_edad",
		data: "fecha="+edad,
		//dataType: 'JSON',
		success: function(datax)
		{
			$("#vistaEdad").val(datax);
		}
	});
});
$(document).on("change, keyup","#vistaEdad",function(evt){
	//alert("hola");
	calcular_fecha_nac();
});
function calcular_fecha_nac(){
	//procedemos a calcular la edad en base a la fecha de nacimiento
	var vistaEdad = $("#vistaEdad").val();
	(vistaEdad=="")?vistaEdad=0:'';
	$.ajax({
		type: 'POST',
		url: base_url+"Cliente/restar_edad",
		data: "fecha="+vistaEdad,
		//dataType: 'JSON',
		success: function(datax)
		{
			//alert(datax);
			$("#edad").val(datax);
		}
	});
}
$(document).on("click","#actualizarFechas",function(evt){
	//alert("hola");
	//procedemos a calcular la edad en base a la fecha de nacimiento
	var edad = $("#edad").val();
	$.ajax({
		type: 'POST',
		url: base_url+"Cliente/actualizarFechas",
		data: "fecha="+edad,
		dataType: 'JSON',
		success: function(datax)
		{
			display_notify(datax.typeinfo, datax.msg);
			//$("#vistaEdad").val(datax);
		}
	});
});
function reload(base) {
	location.href = "../";
}
