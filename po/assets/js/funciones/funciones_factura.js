var tipo = parseInt($("#tipo").val());
$(document).ready(function () {
  $(".select").select2();
  var url = base_url+"Factura/get_data";
  $('#editable2').DataTable({
    "pageLength": 50,
    "serverSide": true,
    "order": [[0, "asc"]],
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
        if(res)cba(JSON.parse(res))
      })
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
    var nit = prod[4];
    var nrc = prod[5];
    $("#edad").val(edad);
    $("#id_cliente").val(id_cliente);
    $("#sexo").val(sexo);
    var tipo=$("#tipo").val();
    if (tipo=="ABONO") {
      verificar_abono(id_cliente);
    }
    if (tipo=="CCF") {
      $("#nit").val(nit);
      $("#nrc").val(nrc);
    }else {
      $("#nit").val("");
      $("#nrc").val("");

    }

  }


  $("#precio").keyup(function(evt){
    if(evt.keyCode == 13 && $(this).val()!="")
    {
      var cantidad = parseFloat($("#cant").val());
      var descripcion = $("#desc").val();
      var precio = parseFloat($("#precio").val());
      var id_sucursal = $("#id_sucursal").val();
      if (id_sucursal==3){
        var subtotal = precio;

      }else {
        var subtotal = cantidad * precio;
      }


      var tr = "<tr>";
      tr += "<td class='cant'>"+cantidad+"</td>";
      tr += "<td class='desc'>"+descripcion+"</td>";
      tr += "<td class='prec'>"+precio.toFixed(2)+"</td>";
      tr += "<td class='subt'>"+subtotal.toFixed(2)+"</td>";
      tr += "<td class='text-center'><a class='btn del'><i class='fa fa-trash'><i></a></td>";
      tr += "<td class='lin'>"+"</td>";
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

  caracteres = 999;
  lineas_maximas = 100;

  id_sucursal = $("#id_sucursal").val();
  tipo = $("#tipo").val();

  switch (tipo) {
    case "COF":
    switch (id_sucursal) {
      case "1":
      lineas_maximas=8;
      caracteres=37;
        break;
      case "2":
      lineas_maximas=6;
      caracteres=37;
        break;
      case "3":
      lineas_maximas=5;
      caracteres=37;
        break;
      default:
    }
      break;
    case "CCF":
    switch (id_sucursal) {
      case "1":
      lineas_maximas=18;
      caracteres=27;
        break;
      case "2":
      lineas_maximas=18;
      caracteres=30;
        break;
      case "3":
      lineas_maximas=18;
      caracteres=30;
        break;
      default:

    }
      break;
    default:

  }

  lineas = 0
  $("#appde tr").each(function(index) {
    subt += parseFloat($(this).find("td:eq(3)").text());
    lineas += lin =  wordWrap( $(this).find("td:eq(1)").text(), caracteres);
    $(this).find("td:eq(5)").text(lin);
    console.log(lineas);
  });

  if (lineas>lineas_maximas) {
    $("#submit").attr('disabled', 'disabled');
    display_notify("Error","Ha usado "+lineas+" lineas el formato solo acepta "+lineas_maximas + " lineas")
  }
  else {
    $("#submit").removeAttr('disabled');
  }
  $("#total").text("$"+subt.toFixed(2));
}

function wordWrap(str, maxWidth) {
  lineas=1;
    var newLineStr = "\n"; done = false; res = '';
    while (str.length > maxWidth) {

        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
                res = res + [str.slice(0, i), newLineStr].join('');
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res += [str.slice(0, maxWidth), newLineStr].join('');
            str = str.slice(maxWidth);
        }
        lineas++;
    }

    console.log(res + str);
    return lineas;
}

function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
};

$(document).on("click", ".del", function()
{
  $(this).parents("tr").remove();
  var i =1;
  $("#appde tr").each(function(){
    $(this).find("td:eq(0)").text(i);
    i++;
  });
  setTimeout(function() {
      total();
  },500)

})
$(document).on("click", ".typeahead",function(){
  $('.typeahead').typeahead('val', '');
  $("#edad").val("");
  $("#sexo").val("");
  var tipo=$("#tipo").val();

  if (tipo=="ABONO") {
    $(".ccf").hide();
    $(".abono").hide();
    $(".cofcff").hide();
    $("#saldo_anterior").val("");
    $("#abono_hoy").val("");
    $("#saldo_actual").val("");
    $("#id_cuenta").val("");
  }


})
$(document).on("change", "#tipo",function(){
  $(".typeahead").click();
  var tipo=$(this).val();
  if(tipo==""){
    $("#producto_buscar").attr("readonly",true);
    $("#producto_buscar").css("background-color", "#eee");
    $(".ccf").hide();
    $(".abono").hide();
    $(".cofcff").show();

  }
  if(tipo=="COF"){
    $("#producto_buscar").attr("readonly",false);
    $("#producto_buscar").css("background-color", "#FFFFFF");
    $(".ccf").hide();
    $(".abono").hide();
    $(".cofcff").show();

  }
  if(tipo=="CCF"){
    $("#producto_buscar").attr("readonly",false);
    $("#producto_buscar").css("background-color", "#FFFFFF");
    $(".ccf").show();
    $(".abono").hide();
    $(".cofcff").show();
  }
  if(tipo=="ABONO"){
    $("#producto_buscar").attr("readonly",false);
    $("#producto_buscar").css("background-color", "#FFFFFF");
    $(".ccf").hide();
    $(".abono").hide();
    $(".cofcff").hide();
  }

  total();

})
$(document).on("click", "#submit",function(){
  guardar_factura();
});
function guardar_factura()
{
  var error=0;
  var msg="";
  var id_cliente = $("#id_cliente").val();
  var nit = $("#nit").val();
  var nrc = $("#nrc").val();
  var tipo = $("#tipo").val();
  var num_doc = $("#num_doc").val();
  if( $('#retencion_bol').is(':checked') ) {
    var retencion_bol=1;
  }else {
    var retencion_bol=0;
  }
  var total_iva=0;
  var total_retencion=0;

  var id_cuenta = $("#id_cuenta").val();
  var saldo_anterior= $("#saldo_anterior").val();
  var abono_hoy= $("#abono_hoy").val();
  var saldo_actual= $("#saldo_actual").val();
  var abono_anterior= $("#abono_anterior").val();
  var fecha_actual= $("#fecha_actual").val();

  var array_json = new Array();
  var tot = 0;
  var iva_input= $("#iva_input").val();
  var retencion_input= $("#retencion_input").val();
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



  if(tipo=="COF"){
    if($("#appde tr").length <= 0)
    {
      error=1;
      msg="Por favor ingrese al menos un detalle";
    }
  }
  if(tipo=="CCF"){
    total_iva = tot * (parseFloat(iva_input));
    total_iva = round(total_iva, 2);
    total_iva = total_iva.toFixed(2);
    if (retencion_bol==1) {
      total_retencion = (tot/(1+parseFloat(iva_input))) * (parseFloat(retencion_input));
      total_retencion = round(total_retencion, 2);
      total_retencion = total_retencion.toFixed(2);

    }
    if($("#appde tr").length <= 0)
    {
      error=1;
      msg="Por favor ingrese al menos un detalle";
    }
  }
  if(tipo=="ABONO"){
    if (saldo_actual=="") {
      error=1;
      msg="Por favor agregue el saldo actual";
    }
    if (abono_hoy=="") {
      error=1;
      msg="Por favor agregue el abono hoy";
    }
    if (saldo_anterior=="") {
      error=1;
      msg="Por favor agregue el saldo anterior";
    }


  }
  if (num_doc=="") {
    error=1;
    msg="Por favor agregue un numero de documento";
  }
  if (tipo=="") {
    error=1;
    msg="Por favor seleccione un tipo";
  }
  if (id_cliente=="") {
    error=1;
    msg="Por favor seleccione un cliente";
  }

  var url="id_cliente="+id_cliente+"&tipo="+tipo+"&num_doc="+num_doc+"&retencion_bol="+retencion_bol+"&total="+tot+"&total_iva="+total_iva;
  url+="&saldo_anterior="+saldo_anterior+"&abono_hoy="+abono_hoy+"&saldo_actual="+saldo_actual+"&total_retencion="+total_retencion+"&id_cuenta="+id_cuenta;
  url+="&datos="+json_arr+"&abono_anterior="+abono_anterior+"&nit="+nit+"&nrc="+nrc+"&fecha_actual="+fecha_actual;
  if (error!=1) {
    $.ajax({
      type: 'POST',
      url: base_url+"Factura/guardar_factura",
      data: url,
      dataType: 'json',
      success: function(datax)
      {
        if (datax.typeinfo == "success" || datax.typeinfo == "Success")
        {
          swal({
            title: "Impresión",
            text: "Desea imprimir ahora?!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si!",
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              display_notify(datax.typeinfo, datax.msg);
              id_factura=datax.id_factura;
              tipo_impresion=datax.tipo_impresion;
              imprimir(id_factura,tipo_impresion);
              setTimeout("reload('" + datax.url + "');", 1500);
            } else {
              display_notify(datax.typeinfo, datax.msg);
              setTimeout("reload('" + datax.url + "');", 1500);
            }
          });

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
  imprimir(id_factura,tipo_impresion);

});
function round(value, decimals) {
  return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

function reload(base) {
  location.href = base;
}
function verificar_abono(id_cliente) {
  dataString = "id_cliente=" + id_cliente;
  $.ajax({
    type: "POST",
    url: base_url+"Factura/verificar_abono",
    data: dataString,
    dataType: 'json',
    success: function (datax) {
      if (datax.typeinfo == "Success") {
        var array=datax.datos;
        if (array.length==1) {
          id_cuenta=array[0].id_cuenta;
          saldo=array[0].saldo;
          abono_anterior=array[0].abono;
          $(".ccf").hide();
          $(".abono").show();
          $(".cofcff").hide();
          $("#saldo_anterior").val(saldo);
          $("#saldo_actual").val(saldo);
          $("#abono_anterior").val(abono_anterior);
          $("#id_cuenta").val(id_cuenta);


        }else {
          for (var i = 0; i < array.length; i++) {
            id_cuenta=array[i].id_cuenta;
            cliente=array[i].nombre;
            fecha=array[i].fecha;
            monto=array[i].monto;
            saldo=array[i].saldo;
            abono_anterior=array[i].abono;
            $("#creditos_activos").modal("show");
            $("#cliente_credito").val(cliente);
            var tr = "<tr>";
            tr += "<td >"+fecha+"</td>";
            tr += "<td >"+monto+"</td>";
            tr += "<td >"+saldo+"</td>";
            tr += "<td ><a class='btn seleccionar' saldo='"+saldo+"' id_cuenta='"+id_cuenta+"' abono_anterior='"+abono_anterior+"'><i class='fa fa-check-square-o'><i></a></td>";
            tr += "</tr>";
            $("#lista_creditos").append(tr);
          }
        }
      }
      if (datax.typeinfo == "Error") {
        swal({
          title: "No cuenta con un credito",
          text: "Desea crear uno?!",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Si!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm) {
          if (isConfirm) {
            window.open(base_url+"Cuenta/agregar_cuenta","_blank");
          } else {
            swal("Cancelada", "La acción fue cancelada", "error");
            $(".typeahead").click();
          }
        });
      }
    }
  });

}
$(document).on('keyup', '#abono_hoy', function(event) {
  var saldo_anterior=$("#saldo_anterior").val();
  var abono_hoy=$(this).val();
  if (abono_hoy!="") {
    if (parseFloat(abono_hoy)>parseFloat(saldo_anterior)) {
      $(this).val(parseFloat(saldo_anterior));
      $("#saldo_actual").val(0);
    }else {
      var saldo_anterior=parseFloat(saldo_anterior)-parseFloat(abono_hoy);
      saldo_anterior = round(saldo_anterior, 2);
      saldo_anterior = saldo_anterior.toFixed(2);
      $("#saldo_actual").val(saldo_anterior);
    }
  }else {
    $("#saldo_actual").val(saldo_anterior);
  }

});
$(document).on("click", ".seleccionar", function()
{

  id_cuenta=$(this).attr("id_cuenta");
  saldo=$(this).attr("saldo");
  abono_anterior=$(this).attr("abono_anterior");
  $(".ccf").hide();
  $(".abono").show();
  $(".cofcff").hide();
  $("#saldo_anterior").val(saldo);
  $("#saldo_actual").val(saldo);
  $("#abono_anterior").val(abono_anterior);
  $("#id_cuenta").val(id_cuenta);
  $("#creditos_activos").modal("hide");

})

function imprimir(id_factura,tipo_impresion) {
  $.ajax({
    type: 'POST',
    url: base_url+"Factura/imprimir_fact",
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
}

$(document).on("click", ".anular", function()
{
  var id = $(this).attr("id");
  swal({
    title: "Esta seguro que desea anular esta factura?",
    text: "Usted no podra deshacer este cambio",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Si, Anular",
    cancelButtonText: "No, Cerrar",
    closeOnConfirm: true
  },
  function() {
    $.ajax({
      type: "POST",
      url: base_url+"Factura/anular",
      data: "id=" + id,
      dataType: "JSON",
      success: function(datax) {
        if (datax.typeinfo == "success" || datax.typeinfo == "Success") {
          setTimeout("location.reload();", 1000);
        }
        display_notify(datax.typeinfo, datax.msg);
      }
    });
  });
});

$(document).on("click", ".copiaranular", function()
{

  var id = $(this).attr("id");
  swal({
    title: "Esta seguro que desea anular esta factura?",
    text: "Usted no podra deshacer este cambio",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Si, Anular",
    cancelButtonText: "No, Cerrar",
    closeOnConfirm: false,
    closeOnCancel: true
  },
  function(isConfirm) {
    if (isConfirm) {
      swal({
        title: "Numero documento!",
        type: "input",
        showCancelButton: true,
        confirmButtonText: 'Continuar',
        closeOnConfirm: false,
        inputPlaceholder: "Numero documento"
      }, function (num_doc) {
        if (num_doc === false) return false;
        if (num_doc === "") {
          swal.showInputError("Por favor ingrese el numero de documento de la nueva factura!");
          return false
        }
        $(".confirm").attr('disabled', 'disabled');
        $.ajax({
          type: "POST",
          url: base_url+"Factura/copiaranular",
          data: "id=" + id+"&num_doc=" + num_doc,
          dataType: 'json',
          success: function (datax) {
            if (datax.typeinfo == "Success") {
              $(".confirm").attr('disabled', false);
              swal({
                title: "Impresión",
                text: "Desea imprimir ahora?!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Si!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
              },
              function(isConfirm) {
                if (isConfirm) {
                  display_notify(datax.typeinfo, datax.msg);
                  id_factura=datax.id_factura;
                  tipo_impresion=datax.tipo_impresion;
                  imprimir(id_factura,tipo_impresion);
                  setTimeout("reload('" + datax.url + "');", 1500);
                } else {
                  display_notify(datax.typeinfo, datax.msg);
                  setTimeout("reload('" + datax.url + "');", 1500);
                }
              });
            }
          }
        });
      });

    }
  });

});
