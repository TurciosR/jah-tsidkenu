var vals = 0;
var tipo = parseInt($("#tipo").val());
$(document).ready(function () {
    $("#esfd").focus();
    $(".select").select2();
    var url = base_url+"Examen/get_data";
    $('#editable2').DataTable({
        "pageLength": 50,
        "serverSide": true,
        "order": [[0, "desc"]],
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
        url: 'fetch/QUERY',

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
      //alert("aqui");
      var tipoCliente = $("input:radio[name=tipoCliente]:checked").val();
      if (tipoCliente==1) {
        //cliente nuevo
      }
      else {
        console.log(q);
        var url = 'fetch/'+ q;
        $.ajax({ url: url })
        .done(function(res) {
          if(res)cba(JSON.parse(res))
        })
        .fail(function(err) {
          alert(err);
        });
      }

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
    if ($('#sexo').find("option[value='" + sexo +"']").length)
    {
      $('#sexo').val(sexo).trigger('change');
    }
    $("#edad").focus();
    vals = 0;
  }
});

$(document).on("click", "input:radio[name=tipoCliente]",function(evt){
  //alert($(this).val());
  $("#producto_buscar").val("");
  $("#edad").val("");
  $("#sexo").val("").trigger("change.select2");
});

$(document).on("keyup", "#edad",function(evt){
if($(this).val()!="" && evt.keyCode == 13 && vals)
{
  $('#sexo').select2('open');
}
vals = 1;
});

$(document).on("click", ".typeahead",function(){
  $('.typeahead').typeahead('val', '');
  $("#edad").val("")
  $("#id_cliente").val("")
  if ($('#sexo').find("option[value='" + "" +"']").length) {
  $('#sexo').val("").trigger('change');
  }

})
$(document).on("click", "#submit",function(){
 var nombre = $("#producto_buscar").val();
 var edad = $("#edad").val();
 var sexo = $("#sexo").val();
 if(nombre != "")
 {
   if(edad != "")
   {
     if(sexo != "")
     {
       $("#submit" ).prop( "disabled", true);
       generar_examen();
     }
     else {
       display_notify("Error", "Ingrese una sexo");
     }
   }
   else {
     display_notify("Error", "Ingrese un edad");
   }
 }
 else {
   display_notify("Error", "Ingrese un nombre");
 }
});
function generar_examen()
{
  var id_cliente=$("#id_cliente").val();
  var nombre = $("#producto_buscar").val();
  var edad = $("#edad").val();
  var sexo = $("#sexo").val();

  var stringData = "&id_cliente="+id_cliente+"&nombre="+nombre+"&edad="+edad+"&sexo="+sexo;
  $.ajax({
    type: 'POST',
    url: base_url+'Examen/generar_examen',
    data: stringData,
    dataType: 'JSON',
    success: function(datax)
    {
        if (datax.typeinfo == "Success") {
  				setTimeout("location.replace('"+datax.url+"/cargar_examen/"+datax.id_cliente+"');",1000);
  			}else{
            display_notify(datax.typeinfo, datax.msg);
            $("#submit" ).prop( "disabled", false);
        }
    }
  });
}
$(document).on("click", "#guardar_examen",function(){
 var esfd = $("#esfd").val();
 var esfi = $("#esfi").val();
 var cild = $("#cild").val();
 var cili = $("#cili").val();
 var ejed = $("#ejed").val();
 var ejei = $("#ejei").val();
 var adid = $("#adid").val();
 var adii = $("#adii").val();

 if(esfd != "" && esfi != "" && cild != "" && cili != "" && ejed != "" && ejei != "" && adid != "" && adii != "")
 {
       $("#guardar_examen" ).prop( "disabled", true);
       senddata();
 }
 else {
   display_notify("Error", "Faltan campos que Rellenar!");
 }
});
function senddata()
{
  var esfd = $("#esfd").val();
  var esfi = $("#esfi").val();
  var cild = $("#cild").val();
  var cili = $("#cili").val();
  var ejed = $("#ejed").val();
  var ejei = $("#ejei").val();
  var adid = $("#adid").val();
  var adii = $("#adii").val();
  var sucursal = $("#sucursal").val();
  var di = $("#di").val();
  var ad = $("#ad").val();
  var color_lente = $("#color_lente").val();
  var bif = $("#bif").val();
  var aro = $("#aro").val();
  var color_aro = $("#color_aro").val();
  var observaciones = $("#observaciones").val();
  var tamanio = $("#tamanio").val();
  var id_cliente = $("#id_cliente").val();
  var edad = $("#edad").val();

  var process = $("#process").val();
  var url = $("#url").val();
  if(process == "insert")
  {
    var id_examen = 0;
    var urlq = "Examen/guardar_examen"
  }
  else {
    if(process == "edited")
    {
      var urlq = "Examen/guardar_examen"
    }
    var id_examen = $("#id_examen").val();
  }
  var stringData = "process="+process+"&id_examen="+id_examen+"&esfd="+esfd+"&esfi="+esfi+"&cild="+cild+"&cili="+cili;
      stringData += "&ejed="+ejed+"&ejei="+ejei+"&adid="+adid+"&adii="+adii+"&di="+di+"&ad="+ad;
      stringData += "&color_lente="+color_lente+"&bif="+bif+"&aro="+aro+"&color_aro="+color_aro+"&observaciones="+observaciones+"&id_cliente="+id_cliente+"&edad="+edad;
      stringData += "&tamanio="+tamanio+"&sucursal="+sucursal;
  $.ajax({
    type: 'POST',
    url: url+urlq,
    data: stringData,
    dataType: 'JSON',
    success: function(datax)
    {
      display_notify(datax.typeinfo, datax.msg);
      if(datax.typeinfo == "Success")
      {
          swal({
            type: 'success',
            title: 'Informacion',
            text: 'Examen generado exitosamente, desea imprimirlo ahora ?',
            showCancelButton: true,
            cancelButtonText: "No, Salir",
            confirmButtonText: "Si, Continuar",
            closeOnConfirm: false,
            },
            function(ifConfirm)
            {
              if(ifConfirm)
              {
                  if (datax.o==28){
                      /*window.open(datax.pdf,"_black");
                      location.replace(datax.url);*/
                      select_p(datax.md5,datax.s,datax.url);

                  }else {
                    select_p(datax.md5,datax.s,datax.url);
                    }
              }
              else
              {
                location.replace(datax.url);
              }
            });

      }
      else {
        $("#guardar_examen" ).prop( "disabled", false);
      }
    }
  });
}

 function select_p(amd5,as,aurl) {
  console.log("aca");
      swal({
        type: 'success',
        title: 'Informacion',
        text: 'Seleccione formato de impresion',
        showCancelButton: true,
        cancelButtonText: "Formato preimpreso",
        confirmButtonText: "Papel en Blanco",
        closeOnConfirm: true,
        },
        function(ifConfirm)
        {
          if(ifConfirm)
          {
            imprimir(amd5,as);
            setTimeout(
                function functionName() {
                    location.replace(aurl);
                },
                1500
            );
          }
          else
          {
            imprimir_formato(amd5,as);
            setTimeout(
                function functionName() {
                    location.replace(aurl);
                },
                1500
            );
          }
        });
}

$(document).on("keyup","#esfd",function(evt){
    if(evt.keyCode == 13)
    {
        if($(this).val()!="")
        {
            $("#cild").focus();
        }
        else {
            display_notify('Warning','Ingrese ESF');
        }
    }
});
$(document).on("keyup","#cild",function(evt){
    if(evt.keyCode == 13)
    {
        if($(this).val()!="")
        {
            $("#ejed").focus();
        }
        else {
            display_notify('Warning','Ingrese CIL');
        }
    }
});
$(document).on("keyup","#ejed",function(evt){
    if(evt.keyCode == 13)
    {
        if($(this).val()!="")
        {
            $("#adid").focus();
        }
        else {
            display_notify('Warning','Ingrese EJE');
        }
    }
});
$(document).on("keyup","#adid",function(evt){
    if(evt.keyCode == 13)
    {
        if($(this).val()!="")
        {
            $("#esfi").focus();
        }
        else {
            display_notify('Warning','Ingrese ADI');
        }
    }
});
$(document).on("keyup","#esfi",function(evt){
    if(evt.keyCode == 13)
    {
        if($(this).val()!="")
        {
            $("#cili").focus();
        }
        else {
            display_notify('Warning','Ingrese ESF');
        }
    }
});
$(document).on("keyup","#cili",function(evt){
    if(evt.keyCode == 13)
    {
        if($(this).val()!="")
        {
            $("#ejei").focus();
        }
        else {
            display_notify('Warning','Ingrese CIL');
        }
    }
});
$(document).on("keyup","#ejei",function(evt){
    if(evt.keyCode == 13)
    {
        if($(this).val()!="")
        {
            $("#adii").focus();
        }
        else {
            display_notify('Warning','Ingrese EJE');
        }
    }
});
$(document).on("keyup","#adii",function(evt){
    if(evt.keyCode == 13)
    {
        $("#di").focus();
    }
});
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





$(document).on("click", ".editar_examen",function(){
 var id_examen = $(this).attr("data");
 var esfd = $("#esfd"+id_examen).val();
 var esfi = $("#esfi"+id_examen).val();
 var cild = $("#cild"+id_examen).val();
 var cili = $("#cili"+id_examen).val();
 var ejed = $("#ejed"+id_examen).val();
 var ejei = $("#ejei"+id_examen).val();
 var adid = $("#adid"+id_examen).val();
 var adii = $("#adii"+id_examen).val();

 if(esfd != "" && esfi != "" && cild != "" && cili != "" && ejed != "" && ejei != "" && adid != "" && adii != "")
 {
   $(".editar_examen" ).prop( "disabled", true);
       senddata_editar(id_examen);
 }
 else {
   display_notify("Error", "Faltan campos que Rellenar!");
 }
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

  var id_sucursal = $("#suce"+id_examen).val();

  var color_lente = $("#color_lente"+id_examen).val();
  var bif = $("#bif"+id_examen).val();
  var aro = $("#aro"+id_examen).val();
  var color_aro = $("#color_aro"+id_examen).val();
  var observaciones = $("#observaciones"+id_examen).val();
  var tamanio = $("#tamanio"+id_examen).val();
  var id_cliente = $("#id_cliente").val();
  var edad = $("#edad").val();

  var stringData = "id_examen="+id_examen+"&esfd="+esfd+"&esfi="+esfi+"&cild="+cild+"&cili="+cili;
      stringData += "&ejed="+ejed+"&ejei="+ejei+"&adid="+adid+"&adii="+adii+"&di="+di+"&ad="+ad;
      stringData += "&color_lente="+color_lente+"&bif="+bif+"&aro="+aro+"&color_aro="+color_aro+"&observaciones="+observaciones+"&id_cliente="+id_cliente+"&edad="+edad;
      stringData += "&tamanio="+tamanio+"&sucursal="+id_sucursal;
  $.ajax({
    type: 'POST',
    url: base_url+"Examen/guardar_examen",
    data: stringData,
    dataType: 'JSON',
    success: function(datax)
    {
      display_notify(datax.typeinfo, datax.msg);
      if(datax.typeinfo == "Success")
      {
        setTimeout("location.reload()",1000);
      }
      else {
        $(".editar_examen" ).prop( "disabled", false);
      }
    }
  });
}

$(document).on('click', '.p_exam', function(event) {


  imprimir($(this).attr("id_examen"),$(this).attr("id_sur"))
});

$(document).on('click', '.p_exam2', function(event) {
  imprimir_formato($(this).attr("id_examen"),$(this).attr("id_sur"))
});



function imprimir(id_examen,id_sucursal) {
  $.ajax({
    type: 'POST',
    url: base_url+"Factura/imprimir_examen",
    data: "id_examen="+id_examen+"&id_sucursal="+id_sucursal,
    dataType: 'json',
    success: function(datos)
    {

      var sist_ope = datos.sist_ope;
      var dir_print=datos.dir_print;
      var shared_printer_win=datos.shared_printer_win;
      var shared_printer_pos=datos.shared_printer_pos;
      var headers=datos.headers;
      var footers=datos.footers;

      console.log(datos.facturar);

      if (sist_ope == 'win') {
        $.post("http://"+dir_print+"printexamenwin.php", {
          datosventa: datos.facturar,
		  nofeed: "ok",
          shared_printer_win:shared_printer_win
        })
      } else {
       // $.post("http://"+dir_print+"printexamen.php", {
        $.post("http://"+dir_print+"printexamenwin.php", {
          datosventa: datos.facturar,
		  nofeed: "ok",
	  shared_printer_win:shared_printer_win
        }
      );
      }

  }

});
}

function imprimir_formato(id_examen,id_sucursal) {
  $.ajax({
    type: 'POST',
    url: base_url+"Factura/imprimir_formato",
    data: "id_examen="+id_examen+"&id_sucursal="+id_sucursal,
    dataType: 'json',
    success: function(datos)
    {

      var sist_ope = datos.sist_ope;
      var dir_print=datos.dir_print;
      var shared_printer_win=datos.shared_printer_win;
      var shared_printer_pos=datos.shared_printer_pos;
      var headers=datos.headers;
      var footers=datos.footers;

      console.log(""+datos.facturar);

      if (sist_ope == 'win') {
        $.post("http://"+dir_print+"printexamenwin.php", {
          datosventa: datos.facturar,
          shared_printer_win:shared_printer_win
        })
      } else {
       // $.post("http://"+dir_print+"printexamen.php", {
        $.post("http://"+dir_print+"printexamenwin.php", {
          datosventa: datos.facturar,
	  shared_printer_win:shared_printer_win
        }
      );
      }

  }

});
}

/*function verificar_cliente(id_cliente)
{

  var stringData = "process="+process+"&id_usuario="+id_usuario+"&nombre="+nombre+"&usuario="+usuario+"&clave="+clave;
  $.ajax({
    type: 'POST',
    url: 'Usuarios/guardar_usuario',
    data: stringData,
    dataType: 'JSON',
    success: function(datax)
    {
      if(datax.typeinfo == "Success")
      {

      }
    }
  });
}*/
