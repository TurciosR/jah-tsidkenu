let url = base_url+"traslado";
let token = $("#csrf_token_id").val();
var com = "";
$(window).keydown(function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });

$(document).on('change', '#sucursal', function(event) {
  $("#table_producto").html("");
  $("#total").val("0.00");
});
$(document).ready(function () {
  $(".sel").select2();
  $(".est").select2();
  $(".numeric").numeric({negative:false, decimals:false});
  $(".decimal").numeric({negative:false, decimalPlaces:4});
    var complemento ='/get_data';
    com = complemento;
  generar();

	$("#form_add").on('submit', function(e){
		e.preventDefault();
		$(this).parsley().validate();
		if ($(this).parsley().isValid()){
			$("#btn_add").prop("disabled",true)
			save_data();
		}
	});

	$("#form_edit").on('submit', function(e){
		e.preventDefault();
		$(this).parsley().validate();
		if ($(this).parsley().isValid()){
			$("#btn_edit").prop("disabled",true)
			edit_data();
		}
	});


    $("#scrollable-dropdown-menu #producto").typeahead({
  			highlight: true,
  		},
  		{
  			limit:100,
  			name: 'producto',
  			display: function(data) {
  				prod=data.producto.split("|");
  				return prod[1];
  			},
  			source: function show(q, cb, cba) {
  				$.ajax({
  					type: "POST",
  					data: {"query":q,"csrf_test_name":token,id_sucursal: $("#sucursal").val()},
  					url:  url+'/get_productos',
  				}).done(function(res){
  					if(res) cba(JSON.parse(res));
  				});
  			},
  			templates:{
  				suggestion:function (data) {
  					var prod=data.producto.split("|");
  					return '<div class="tt-suggestion tt-selectable">'+prod[3]+" "+prod[4]+" "+prod[5]+" "+prod[7]+" "+prod[6]+'</div>';
  				}
  			}
  		}).on('typeahead:selected',onAutocompleted_producto);
  	function onAutocompleted_producto($e, datum) {
  		let prod = datum.producto.split("|");
      let id_producto = prod[0];
      let nombre = prod[3]+" "+prod[4]+" "+prod[5]+" "+prod[7]+" "+prod[6];
      let id_color = prod[2];
      $("#id_producto").val(id_producto);
      new_producto(id_producto,nombre,id_color);
  	}
});

$(document).on('change', '#sucursales', function(event) {
  generar();
});
function generar()
{
  dataTable = $('#editable').DataTable().destroy()
  dataTable = $('#editable').DataTable({
    "pageLength": 50,
    "serverSide": true,
    "order": [[0, "desc"]],
    "ajax": {
      url: url+com,
      type: 'POST',
      data:{
        csrf_test_name:token,
        id_sucursal: $("#sucursales").val(),
      }
    },
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ning??n dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "??ltimo",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  }); // End of DataTable
  //dataTable.ajax.reload();
}

function new_producto(id_producto,nombre,id_color)
{
	$("#scrollable-dropdown-menu #producto").typeahead("val","");
	let distinto = true;
	/*if ($("#table_producto tr").length > 0)
	{
		$("#table_producto tr").each(function(){
			let id_p = $(this).find(".id_producto").val();
			if(id_producto == id_p)
			{
				distinto = false
			}
		});
	}*/
  id_venta=0;
  if ($("#proceso").val()=="editar") {
    id_venta = $("#id_venta").val();
  }
	if(distinto){
    var id_sucursal = $("#sucursal").val();
		$.ajax({
			type: 'POST',
			url: url+'/detalle_producto',
			data: "id="+id_producto+"&csrf_test_name="+token+"&id_s="+id_color+"&id_venta="+id_venta+"&id_sucursal="+id_sucursal,
			dataType: 'json',
			success: function (datax) {
        //alert(datax.id_sucursal);
        (datax.ocultar=="")?'':$("#total").hide();
				let fila = "<tr>";
				fila += "<td>"+id_producto+"</td>";
				fila += "<td><input type='hidden' class='id_producto' value='"+id_producto+"'><input type='hidden' class='id_s' value='"+datax.id_s+"'><input type='hidden' class='nombre' value='"+nombre+"'>"+nombre+"</td>";
        fila += "<td><input type='hidden' class='color' value='"+id_color+"'><input type='hidden' class='stock' value='"+datax.stock+"' style='width:100%;'>"+datax.stock+"</td>";
        fila += "<td><input type='text' class='form-control cantidad numeric' value='' style='width:100%;'></td>";
				fila += "<td><input type='hidden' class='form-control costo decimal' value='"+datax.costo+"' style='width:100%;'><select class='est'><option value='NUEVO'>NUEVO</option><option value='USADO'>USADO</option></select></td>";
				fila += "<td>"+datax.precios+"</td>";
				fila += "<td><input "+datax.ocultar+" type='text' class='form-control subtotal' readonly value='"+datax.costo+"' style='width:100%;'></td>";
				fila += "<td class='text-center'><a class='btn btn-danger delete_tr' style='color: white'><i class='mdi mdi-trash-can'></i></a></td>";
				fila +="</tr>";
				$("#table_producto").prepend(fila);
				$(".numeric").numeric({negative:false, decimals:false});
				$(".decimal").numeric({negative:false, decimalPlaces:4});
				$(".sel").select2();
        $(".est").select2();
				$("#table_producto tr:first").find(".cantidad").focus();
			}
		});

	}else{
		notification("Error","Alerta","El producto ya fue agregado");
	}

}

/*keyups de movimiento entre casillas*/
$(document).on("keyup", ".cantidad", function(e){

  tr = $(this).closest('tr');
  stock = parseInt(tr.find('.stock').val());
  id_producto = parseInt(tr.find('.id_s').val());

  fila = tr;
  existencia = parseInt(fila.find('.stock').val());

  a_cant = parseInt($(this).val());

  if (isNaN(a_cant)) {
    a_cant=0;
  }
  a_asignar = 0;

  $('#table_producto tr').each(function(index) {

    if ($(this).find('.id_s').val() == id_producto) {
      t_cant = parseInt($(this).find('.cantidad').val());
      if (isNaN(t_cant)) {
        t_cant = 0;
      }
      a_asignar = a_asignar + t_cant;
    }
  });
  console.log(existencia);
  console.log(a_asignar);
  if (a_asignar > existencia) {
    val = existencia - (a_asignar - a_cant);
    val = Math.trunc(val);
    val = parseInt(val);
    $(this).val(val);
    setTimeout(function() {
      totales();
    }, 1000);
  } else {
    totales();
  }

  if(e.keyCode == 13 && $(this).val()!="")
  {
    tr.find(".est").select2("open");
  }


});
$(document).on("select2:close", ".est", function(){
  var tr = $(this).parents("tr");
  tr.find(".sel").select2("open");
});

$(document).on("select2:close", ".sel", function(){
  totales();
  $("#producto").focus();
});

$(document).on('change', '#sucursal', function(event) {
  event.preventDefault();
  $("#table_producto").html("");
  totales();
});

function totales()
{
  var total = 0;
	$("#table_producto tr").each(function(){
		var tr  = $(this);
		var costo  = tr.find(".precios").val();
		var cantidad  = tr.find(".cantidad").val();
    var stock  = parseInt(tr.find(".stock").val());
		if(costo == "")
		{
			costo = 0;
		}
		if(cantidad == "")
		{
			cantidad = 0;
		}
    if(stock < parseInt(cantidad))
    {
      cantidad = stock;
      tr.find(".cantidad").val(stock);
    }
		var costo_iva = parseFloat(costo)*1.13;
		var subtotal = parseInt(cantidad)*parseFloat(costo);
    total+=subtotal;
		tr.find(".costo_iva").val(costo_iva.toFixed(2));
		tr.find(".subtotal").val(subtotal.toFixed(2));
	});
  $('#total').val(total.toFixed(2));
}
$(document).on("click", ".delete_tr", function(){
	$(this).parents("tr").remove();
});

$(document).on("click", ".delete", function() {
  $(this).parents("tr").remove();
});

function save_data(){
  $("#divh").show();
	$("#main_view").hide();

  var errors = false;
  var error_array = [];
  var array_json = [];
  var cuantos = 0;
	$("#table_producto tr").each(function(index) {
    var id_producto = $(this).find('.id_producto').val();
    var cantidad = parseInt($(this).find('.cantidad').val());
    var costo = parseFloat($(this).find('.costo').val());
    var est = $(this).find('.est').val();
    var precio_sugerido = parseFloat($(this).find('.precios').val());
    var subtotal = parseFloat($(this).find('.subtotal').val());
    var color = parseFloat($(this).find('.color').val());

    if (isNaN(costo)) {
      costo = 0;
      errors = true;
      error_array.push('No. '+id_producto+' hay un costo vacio');
    }
    if (isNaN(precio_sugerido)) {
      costo = 0;
      errors = true;
      error_array.push('No. '+id_producto+' hay un precio sugerido vacio');
    }

		if (!isNaN(cantidad) && cantidad>0)
		{
			var obj = new Object();
			obj.id_producto = id_producto;
      obj.cantidad = cantidad;
      obj.costo = costo;
      obj.precio_sugerido = precio_sugerido;
      obj.subtotal = subtotal;
      obj.color = color;
      obj.est = est;
			//convert object to json string
			text = JSON.stringify(obj);
			array_json.push(text);
      cuantos++;
		}
    else {
      errors = true;
      error_array.push('No. '+id_producto+' hay una cantidad con valor cero o vacia');
    }
	});
	var json_arr = '[' + array_json + ']';
	$("#data_ingreso").val(json_arr);
  if (cuantos==0) {
    errors = true;
    error_array.push('Llene los datos de al menos un producto');
  }

  if ($("#sucursal").val()==$("#sucursal_destino").val()) {
    errors = true;
    error_array.push('El origen y el destino no pueden ser iguales');
  }


  if (errors==false) {
    let form = $("#form_add");
    let formdata = false;
    if (window.FormData) {
      formdata = new FormData(form[0]);
    }
    $.ajax({
      type: 'POST',
      url: url+'/agregar',
      cache: false,
      data: formdata ? formdata : form.serialize(),
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function (data) {
        $("#divh").hide();
        $("#main_view").show();
        notification(data.type,data.title,data.msg);
        if (data.type == "success") {
          setTimeout("reload();", 1500);
        }
        else {
          $("#divh").hide();
          $("#main_view").show();
          $("#btn_add").removeAttr("disabled");
        }
      }
    });
  }
  else {
    notification("Error","Error en formulario",error_array.join(",<br>"));
    $("#btn_add").removeAttr("disabled");
    $("#divh").hide();
    $("#main_view").show();
  }
}


function edit_data(){
  $("#divh").show();
  $("#main_view").hide();

  var errors = false;
  var error_array = [];
  var array_json = [];
  var cuantos = 0;
  $("#table_producto tr").each(function(index) {
    var id_producto = $(this).find('.id_producto').val();
    var cantidad = parseInt($(this).find('.cantidad').val());
    var costo = parseFloat($(this).find('.costo').val());
    var est = $(this).find('.est').val();
    var precio_sugerido = parseFloat($(this).find('.precios').val());
    var subtotal = parseFloat($(this).find('.subtotal').val());
    var color = parseFloat($(this).find('.color').val());

    if (isNaN(costo)) {
      costo = 0;
      errors = true;
      error_array.push('No. '+id_producto+' hay un costo vacio');
    }
    if (isNaN(precio_sugerido)) {
      costo = 0;
      errors = true;
      error_array.push('No. '+id_producto+' hay un precio sugerido vacio');
    }

    if (!isNaN(cantidad) && cantidad>0)
    {
      var obj = new Object();
      obj.id_producto = id_producto;
      obj.cantidad = cantidad;
      obj.costo = costo;
      obj.precio_sugerido = precio_sugerido;
      obj.subtotal = subtotal;
      obj.color = color;
      obj.est = est;
      //convert object to json string
      text = JSON.stringify(obj);
      array_json.push(text);
      cuantos++;
    }
    else {
      errors = true;
      error_array.push('No. '+id_producto+' hay una cantidad con valor cero o vacia');
    }
  });
  var json_arr = '[' + array_json + ']';
  $("#data_ingreso").val(json_arr);
  if (cuantos==0) {
    errors = true;
    error_array.push('Llene los datos de al menos un producto');
  }

  if ($("#id_cliente").val()=="") {
    error_array.push('Seleccione un cliente');
  }

  if (errors==false) {
    let form = $("#form_edit");
    let formdata = false;
    if (window.FormData) {
      formdata = new FormData(form[0]);
    }
    $.ajax({
      type: 'POST',
      url: url+'/editar',
      cache: false,
      data: formdata ? formdata : form.serialize(),
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function (data) {
        $("#divh").hide();
        $("#main_view").show();
        notification(data.type,data.title,data.msg);
        if (data.type == "success") {
          setTimeout("reload();", 1500);
        }
        else {
          $("#divh").hide();
          $("#main_view").show();
          $("#btn_add").removeAttr("disabled");
        }
      }
    });
  }
  else {
    notification("Error","Error en formulario",error_array.join(",<br>"));
    $("#btn_add").removeAttr("disabled");
    $("#divh").hide();
    $("#main_view").show();
  }
}

function reload() {
	location.href = url;
}

function reload_current() {
	location.reload()
}

$(document).on("click",".delete_row", function(event)
{
    event.preventDefault()
    let id_row = $(this).attr("id");
    let dataString = "id=" + id_row+"&csrf_test_name="+token;
    Swal.fire({
        title: 'Alerta!!',
        text: "Estas seguro de eliminar este registro?!",
        type: 'error',
        target:'#page-top',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: url+"/delete",
                data: dataString,
                dataType: 'json',
                success: function (data) {
                    notification(data.type,data.title,data.msg);
                    if (data.type == "success") {
                        setTimeout("reload();", 1500);
                    }
                }
            });
        }
    });
});


$(document).on("click", ".delete_trd", function(){
  $(this).parents("tr").remove();
});


function reload_current_des() {
	location.reload()
}

$(document).on('click', '.detail', function(event) {
	$('#viewModal .modal-content').load(url+"/detalle/"+$(this).attr('data-id'));
});

$(document).on('click', '.status_change', function(event) {
	$('#viewModal .modal-content').load(url+"/change_state/"+$(this).attr('data-id'));
});

$(document).on('click', '.change_s', function(event) {
  let id_row = $(this).attr("id_v");
  var id_estado  = $(".estado").val();
  let dataString = "id=" + id_row+"&csrf_test_name="+token+"&id_estado="+id_estado;
  $.ajax({
      type: "POST",
      url: url+"/change",
      data: dataString,
      dataType: 'json',
      success: function (data) {
          notification(data.type,data.title,data.msg);
          if (data.type == "success") {
              setTimeout("reload();", 1500);
          }
      }
  });
});

//funcion para imprimir ticket de traslado
$(document).on('click', '.imprimir_ticket', function(event) {
  var traslado = $(this).attr("traslado");
  var sucursal = $(this).attr("sucursal");
  var sucursalDestino = $(this).attr("sucursal_destino");
  //alert(sucursalDestino);
  $.ajax({
      type: "POST",
      url: url+"/imprimir_ticket",
      data: {'csrf_test_name':token, 'traslado':traslado, 'sucursal':sucursal, 'sucursal_destino':sucursalDestino},
      dataType: 'json',
      success: function (data) {
          notification(data.type,data.title,data.msg);
          if (data.type == "success") {
              console.log(data.ticket);
              if (data.opsys == 'Linux') {
                $.post("http://"+data.dir_print+"printtraslado1.php", {
                  //encabezado:data.encabezado,
                  cuerpo:data.ticket,
                  });
              }
              else{
                var direc="http://"+data.dir_print+"printposwin1.php";
                if (data.tipodoc == '1') {
                   $.post(direc, {
                    totales:data.totales,
                    total_letras:data.total_letras,
                    efectivo: efectivo,
                    cambio: cambio,
                    encabezado:data.encabezado,
                    cuerpo:data.cuerpo,
                    pie:data.pie,
                    shared_printer_pos:data.dir_print_pos,
                  })
                }
              }
              //setTimeout("reload();", 1500);
          }
      }
  });
});
