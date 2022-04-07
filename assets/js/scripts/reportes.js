let url = base_url+"reportes";
let token = $("#csrf_token_id").val()

$(document).ready(function () {
	$('.select').select2();
	$("#generarReporte").click(function(){
		//alert("hola");
		var valor = $('#reportes').val();
		var tipoReporte = $('input[name=tipoReporte]:checked').val();
		var sucursal = $('#sucursal').val();
    //alert(valor);
		if (tipoReporte==undefined) {
			notification("warning","Error","debe seleccionar un tipo de reporte");
		}
		else{
			if (valor==undefined) {
				//alert("no es un numero");
			}
			else {
				//alert("aqui");
				var fechaInicio = $(".fechaInicio").val();
				var fechaFin = $(".fechaFin").val();
				window.open(url + "/generar/"+valor+"/"+tipoReporte+"/"+fechaInicio+"/"+fechaFin+"/"+sucursal);

			}
		}

	});

	$("#generarReporteKardex").click(function(){
		//alert("hola");
		var sucursal = $('#sucursalK').val();
		var idP = $('#selectProductos').val();
		var color = $('#selectProductos option:selected').attr("color");
		//alert(valor);
		//alert("aqui");
		var fechaInicio = $(".fechaInicioK").val();
		var fechaFin = $(".fechaFinK").val();
		if (idP=="") {
			notification("warning","Alerta","debe seleccionar un producto");
		}
		else {
			window.open(url + "/generar_kardex/"+fechaInicio+"/"+fechaFin+"/"+sucursal+"/"+idP+"/"+color);
		}
	});

	$("#generarReporteExist").click(function(){
		//alert("hola");
		var tipo = $("#selectCategoria").val();
		var valor = $('#reportes').val();
		var sucursal = $('#sucursal').val();
		let mostrarCosto = $('input[name="checkboxCostos"]:checked').val();
		//alert(mostrarCosto);
		if (valor==undefined) {
			//alert("no es un numero");
		}
		else {
			//alert("aqui");
			var fechaInicio = $(".fechaInicio").val();
			var fechaFin = $(".fechaFin").val();
			window.open(url + "/generarExist/"+valor+"/"+sucursal+"/"+tipo+"/"+mostrarCosto);
		}
	});

	$("#generarReporteMovimientos").click(function(){
		//alert("hola");
		var tipo = $("#selectMovimientos").val();
		var sucursal = $('#sucursal').val();

		var fechaInicio = $(".fechaInicio").val();
		var fechaFin = $(".fechaFin").val();
		window.open(url + "/generarMovimiento/"+sucursal+"/"+tipo+"/"+fechaInicio+"/"+fechaFin);
	});
});

$("#generarReporteTraslados").click(function(){
	//alert("hola");
	let sucursal_despacho = $('#sucursalDespacho').val();
	let sucursal_destino = $('#sucursalDestino').val();
	//alert(valor);
	//alert("aqui");
	let fechaInicio = $(".fechaInicio").val();
	let fechaFin = $(".fechaFin").val();
	if (sucursal_despacho == "" || sucursal_destino == "") {
		notification("warning","Alerta","Elegir la sucursal de despacho y destino");
	}
	else {
		window.open(url + "/reporte_traslado_rango/"+fechaInicio+"/"+fechaFin+"/"+sucursal_despacho+"/"+sucursal_destino,
		 "_blank");
	}
});

$("#sucursalK").on('change', function(e){
	var sucursal = $(this).val();
	let dataString = "id=" + sucursal+"&csrf_test_name="+token;
	$.ajax({
		type: "POST",
		url: url+"/get_stock_sucursal",
		data: dataString,
		//dataType: 'json',
		success: function (data) {
			$("#selectProductos").html("");
			$("#selectProductos").html(data);
		}
	});
});

$(document).on('click', "input[name=checkboxTipo]", function(event) {
	if($(this).attr('id')=='checkboxConsolidado'){
		$(".contenedor_categoria").prop('hidden', true);
		$("#selectCategoria").val("0").trigger("change");
	}
	else{
		$(".contenedor_categoria").prop('hidden', false);
	}
});