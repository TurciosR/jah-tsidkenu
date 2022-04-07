var tipo = parseInt($("#tipo").val());
$(document).ready(function () {
  $(".select").select2();
  var url = base_url+"Solicitud/get_data";
  $('#editable2').DataTable({
    "pageLength": 50,
    "serverSide": true,
    "ajax": {
      url: url,
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

  var position = new Bloodhound({
    datumTokenizer: function (datum) {
      return Bloodhound.tokenizers.whitespace(datum.producto);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    //prefetch: '../data/films/post_1960.json',
    remote: {
      wildcard: '%QUERY',
      url: 'fetch/%QUERY',

      transform:function (positionList) {
        // Map the remote source JSON array to a JavaScript object array
        return $.map(positionList, function (position) {
          return {
            producto: position.producto
          };
        });
      }
    }
  });
  $("#scrollable-dropdown-menu #producto_buscar").typeahead({
    highlight: true,
  },
  {
    limit:100,
    name: 'productos',
    display: function(data) {
      prod=data.producto.split("|");
      return prod[1];
    },
    source: function show(q, cb, cba) {
      console.log(q);
      var url = 'fetch/'+ q;
      $.ajax({ url: url })
      .done(function(res) {
        cba(JSON.parse(res));
      })
      .fail(function(err) {
        alert(err);
      });
    },
    templates:{
      suggestion:function (data) {
        var prod=data.producto.split("|");
        return '<div class="tt-suggestion tt-selectable">'+prod[1]+'</div>';

      }
    }
  }).on('typeahead:selected', onAutocompleted);
  function onAutocompleted($e, datum) {  //
    var prod0=datum.producto;
    var prod= prod0.split("|");
    var id_cliente= prod[0];
    var edad = prod[2];
    var sexo = prod[3];
    $("#edad").val(edad);
    $("#id_cliente").val(id_cliente);
    $("#sexo").val(sexo);
  }


  $("#precio").keyup(function(evt){
    if(evt.keyCode == 13 && $(this).val()!="")
    {
      var cantidad = parseFloat($("#cant").val());
      var descripcion = $("#desc").val();
      var precio = parseFloat($("#precio").val());
      var subtotal = cantidad * precio;

      var tr = "<tr>";
      tr += "<td class='cant'>"+cantidad+"</td>";
      tr += "<td class='desc'>"+descripcion+"</td>";
      tr += "<td class='prec'>"+precio.toFixed(2)+"</td>";
      tr += "<td class='subt'>"+subtotal.toFixed(2)+"</td>";
      tr += "<td class='text-center'><a class='btn del'><i class='fa fa-trash'><i></a></td>";
      tr += "</tr>";
      $("#appde").append(tr);
      $("#cant").val("");
      $("#desc").val("");
      $("#precio").val("");
      $("#cant").focus();
      total();
    }
  });
  $("#cant").keyup(function(evt){
    if(evt.keyCode == 13 && $(this).val()!="")
    {
      $("#desc").focus();
    }
  });
  $("#desc").keyup(function(evt){
    if(evt.keyCode == 13 && $(this).val()!="")
    {
      $("#precio").focus();
    }
  });



});
function total()
{
  var subt =0;
  $("#appde tr").each(function(index) {
    subt += parseFloat($(this).find("td:eq(3)").text());
  });
  $("#total").text("$"+subt.toFixed(2));
}
$(document).on("click", ".del", function()
{
  $(this).parents("tr").remove();
  var i =1;
  $("#appde tr").each(function(){
    $(this).find("td:eq(0)").text(i);
    i++;
  });
  total();
})
$(document).on("click", ".typeahead",function(){
  $('.typeahead').typeahead('val', '');
  $("#edad").val("");
  $("#sexo").val("");

})

$(document).on("click", "#submit",function(){
  guardar_factura();
});
$(document).on("click", "#abonar",function(){
    guardar_abono();
});
function guardar_factura()
{
  var error=0;
  var msg="";
  var id_cliente = $("#id_cliente").val();
  var tipo = $("#tipo").val();
  var num_doc = $("#num_doc").val();
  var abono= $("#abono").val();

  var array_json = new Array();
  var tot = 0;
  $("#appde tr").each(function(index) {
    var cant = parseFloat($(this).find("td:eq(0)").text());
    var desc = $(this).find("td:eq(1)").text();
    var prec = parseFloat($(this).find("td:eq(2)").text());
    var subt = parseFloat($(this).find("td:eq(3)").text());
    var obj = new Object();
    tot += subt;
    obj.cant = cant;
    obj.desc = desc;
    obj.prec = prec;
    obj.subt = subt;
    text = JSON.stringify(obj);
    array_json.push(text);
  });
  json_arr = '[' + array_json + ']';

    if($("#appde tr").length <= 0)
    {
      error=1;
      msg="Por favor ingrese al menos un detalle";
    }

  if (num_doc=="") {
    error=1;
    msg="Por favor agregue un numero de documento";
  }
  if (id_cliente=="") {
    error=1;
    msg="Por favor seleccione un cliente";
  }

  var url="id_cliente="+id_cliente+"&numero_doc="+num_doc+"&total="+tot;
  url+="&abono="+abono;
  url+="&datos="+json_arr;
  if (error!=1) {
    $.ajax({
      type: 'POST',
      url: base_url+"Cuenta/guardar_cuenta",
      data: url,
      dataType: 'json',
      success: function(datax)
      {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "success" || datax.typeinfo == "Success")
        {
          setTimeout("reload('" + datax.url + "');", 1500);
        }
      }
    });

  }else {
    display_notify("Error", msg);
  }
}
$(document).on('keyup', '#abono', function(event) {
    if (event.keyCode == 13) {
        $("#abonar").click();
    }
    $("#abonar").attr("disabled", false);
    var monto = round(parseFloat($(this).val()), 2);
    var deuda = round(parseFloat($('#saldo').val()), 2);
    if (monto > deuda) {
        $(this).val(deuda);
    }
});
function guardar_abono()
{
    var error=0;
    var msg="";
    var id_cuenta = $("#id_cuenta").val();
    var num_doc = $("#num_doc").val();
    var abono= $("#abono").val();
    var abonado= $("#abonado").val();
    var saldo= $("#saldo").val();


    if (abono=="") {
        error=1;
        msg="Por favor agregue monto a abonar";
    }
    if (num_doc=="") {
        error=1;
        msg="Por favor agregue un numero de documento";
    }

    var url="id_cuenta="+id_cuenta+"&numero_doc="+num_doc+"&abono="+abono;
    url+="&saldo="+saldo+"&abonado="+abonado;
    if (error!=1) {
        $.ajax({
            type: 'POST',
            url: base_url+"Cuenta/guardar_abono",
            data: url,
            dataType: 'json',
            success: function(datax)
            {
                display_notify(datax.typeinfo, datax.msg);
                if (datax.typeinfo == "success" || datax.typeinfo == "Success")
                {
                        var fila = "<tr>";
                        var i=0;
                         i=$("#listdetalle tr").length +1;
                        fila += "<td>" + i + "</td>";
                        fila += "<td>" + parseFloat(datax.abono).toFixed(2) + "</td>";
                        fila += "<td>" + datax.fecha + "</td>";
                        fila += "</tr>";
                        if ($("#listdetalle tr").length > 0) {
                            $("#listdetalle > tr:first").before(fila);
                        } else {
                            $("#listdetalle").append(fila);
                            //$("#listdetalle").prepend(fila);
                        }
                        $("#saldo").val(parseFloat(datax.saldo).toFixed(2));
                        $("#abonado").val(parseFloat(datax.abonado).toFixed(2));
                        if (datax.saldo == 0) {
                            $("#num_doc").attr("readonly", true);
                            $("#abono").attr("readonly", true);
                            $("#abonar").attr("disabled", true);
                        }
                   $("#num_doc").val("");
                   $("#abono").val("");

                    //setTimeout("reload('" + datax.url + "');", 1500);
                }
            }
        });

    }else {
        display_notify("Error", msg);
    }
}


$(document).on("click", "#imprimir",function(){
  var id_factura = $("#id_factura1").val();
  var tipo_impresion = $("#tipo_impresion").val();
  $.ajax({
    type: 'POST',
    url: "Factura/imprimir_fact",
    data: "id_factura="+id_factura+"&tipo_impresion="+tipo_impresion,
    dataType: 'json',
    success: function(datos)
    {

      var sist_ope = datos.sist_ope;
      var dir_print=datos.dir_print;
      var shared_printer_win=datos.shared_printer_win;
      var shared_printer_pos=datos.shared_printer_pos;
      var headers=datos.headers;
      var footers=datos.footers;

      console.log(tipo_impresion);
      if (tipo_impresion == 'TIK') {
        if (sist_ope == 'win') {
          $.post("http://"+dir_print+"printposwin1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin,
            shared_printer_pos:shared_printer_pos,
            headers:headers,
            footers:footers,
            a_pagar:a_pagar,
            monto_vale: monto_vale,
          })
        } else {
          $.post("http://"+dir_print+"printpos1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin,
            headers:headers,
            footers:footers,
            a_pagar:a_pagar,
            monto_vale: monto_vale,
          });
        }
        setInterval("reload1();", 1500);
      }
      if (tipo_impresion == 'COF') {
        if (sist_ope == 'win') {
          $.post("http://"+dir_print+"printfactwin1.php", {
            datosventa: datos.facturar,
            shared_printer_win:shared_printer_win
          })
        } else {
          $.post("http://"+dir_print+"printfact1.php", {
            datosventa: datos.facturar,
          }
        );
      }
    }

    if (tipo_impresion == 'CCF') {
      if (sist_ope == 'win') {
        $.post("http://"+dir_print+"printcfwin1.php", {
          datosventa: datos.facturar,
          shared_printer_win:shared_printer_win
        })
      } else {
        $.post("http://"+dir_print+"printfact1.php", {
          datosventa: datos.facturar,
        }, function(data, status) {
        });
      }
    }


  }



});

});

function send() {
    var id_cuenta = $('#id_cuenta').val();
    var monto = $('#abono').val();
    var num_doc = $('#num_doc').val();

    var dataString = 'process=abonar'+'&id_factura='+id_factura+"&monto="+monto+"&num_doc="+num_doc+"&num_doc_pago="+num_doc_pago+"&tipo="+tipo;
    dataString += "&fecha="+fecha;
    $.ajax({
        type: "POST",
        url: "credit_payment.php",
        data: dataString,
        dataType: 'JSON',
        success: function(datax) {
            display_notify(datax.typeinfo,datax.msg);
            if (datax.typeinfo == "Success") {
                //setInterval("reload1();", 1000);
                //$("#clos").click();
                var fila = "<tr>";
                fila += "<td>" + datax.fecha + "</td>";
                fila += "<td>" + datax.hora + "</td>";
                fila += "<td>" + num_doc + "</td>";
                fila += "<td class='mont'>" + datax.monto + "</td>";
                fila += "<td><a class='btn delee' id='" + datax.id_abono_credito + "'><i class='fa fa-trash'></i></a></td>";
                fila += "</tr>";
                if ($("#appas tr").length > 0) {
                    $("#appas > tr:first").before(fila);
                } else {
                    $("#appas").append(fila);
                }
                var tot = parseFloat($("#total").text());
                var deuda = parseFloat($("#deuda").val());
                var abonos = parseFloat($("#abonos").val());
                tot += parseFloat(datax.monto);
                deuda -= parseFloat(datax.monto);
                abonos += parseFloat(datax.monto);
                $("#total").text(round(tot, 2));
                $("#deuda").val(round(deuda, 2));
                $("#abonos").val(round(abonos, 2));
                if (deuda == 0) {
                    $("#monto").attr("readonly", true);
                    $("#abonar").attr("disabled", true);
                }

            } else {
                $("#abonar").attr("disabled", false);
            }
        }
    });
}
function round(value, decimals) {
  return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

function reload(base) {
	location.href = base;
}
$(function ()
{

  // Clean the modal form
  $(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });

});


$(document).on("click", ".confirmar", function (event) {
  var id = $(this).attr("data");
  dataString = "id=" + id;
	swal({
			title: "Confirmar",
			text: "Esta seguro que desea confirmar esta solicitud?",
			type: "success",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Si, Continuar",
			cancelButtonText: "No, Cerrar",
			closeOnConfirm: true
		},
		function () {
			$.ajax({
				type: "POST",
				url: base_url+"Solicitud/confirmar_solicitud",
				data: dataString,
				dataType: 'json',
				success: function (datax) {
					display_notify(datax.typeinfo, datax.msg);
					if (datax.typeinfo == "Success") {
						setTimeout("reload('" + datax.base + "');", 1500);
					}
				}
			});
		});

});

$(document).on("click", ".cancelar", function (event) {
  var id = $(this).attr("data");
  dataString = "id=" + id;
	swal({
			title: "Cancelar",
			text: "Esta seguro que desea cancelar esta solicitud?",
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
				url: base_url+"Solicitud/cancelar_solicitud",
				data: dataString,
				dataType: 'json',
				success: function (datax) {
					display_notify(datax.typeinfo, datax.msg);
					if (datax.typeinfo == "Success") {
						setTimeout("reload('" + datax.base + "');", 1500);
					}
				}
			});
		});

});
