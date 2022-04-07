var tipo = parseInt($("#tipo").val());
$(document).ready(function () {
    var url = "Aros/get_data";
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
});
$(document).on("click", "#submit",function(){
 var codigo = $("#codigo").val();
 var marca = $("#marca").val();
 var casa = $("#casa").val();
 if(codigo != "")
 {
   if(marca != "")
   {
     if(casa != "")
     {
       senddata();
     }
     else {
       display_notify("Error", "Ingrese una casa");
     }
   }
   else {
     display_notify("Error", "Ingrese un marca");
   }
 }
 else {
   display_notify("Error", "Ingrese un codigo");
 }
});
$(document).on("click", "#solicitar",function(){
    var id_sur = $(this).attr("id_sur");
    var codigo = $("#codigo").val();
    swal({
            title: "Esta seguro que desea solicitar este aro????",
            text: "Usted no podra deshacer este cambio",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Solicitar",
            cancelButtonText: "No, Cerrar",
            closeOnConfirm: true
        },
        function() {
           $.ajax({
                type: "POST",
                url: base_url+"Aros/ingresar_solicitud",
                data: "id_sur=" + id_sur +"&codigo=" + codigo,
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
$(document).on("click", "#btn_gdr",function(){
    var codigo = $("#codigo").val();
    var cantidad = $("#cantidad").val();
    var motivo = $("#motivo").val();
    if (codigo !="" && cantidad !="" && motivo !="") {
        $.ajax({
            type: "POST",
            url: base_url + "Aros/ingresar_salida_aro",
            data: "cantidad=" + cantidad + "&codigo=" + codigo + "&motivo=" + motivo,
            dataType: "JSON",
            success: function (datax) {
                if (datax.typeinfo == "success" || datax.typeinfo == "Success") {
                    setTimeout("location.reload();", 1000);
                }
                display_notify(datax.typeinfo, datax.msg);
            }
        });
    }else {
        display_notify("Error", "Falta datos que llenar");
    }

});
function senddata()
{
  var codigo = $("#codigo").val();
  var marca = $("#marca").val();
  var casa = $("#casa").val();
  var existencia = $("#existencia").val();
  var process = $("#process").val();
  var url = $("#url").val();
  if(process == "insert")
  {
    var id_aro = 0;
    var urlq = "Aros/guardar_aro"
  }
  else {
    if(process == "edited")
    {
      var urlq = "Aros/guardar_aro"
    }
    else if(process == "ingresar") {
      var urlq = "Aros/ingresar_aros";
    }
    var id_aro = $("#id_aro").val();
  }
  var stringData = "process="+process+"&id_aro="+id_aro+"&codigo="+codigo+"&marca="+marca+"&casa="+casa+"&existencia="+existencia;
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
        setTimeout("location.replace('"+datax.url+"');",1000);
      }
    }
  });
}
$(document).on("keyup","#codigo",function(evt){
    if(evt.keyCode == 13)
    {
        $("#marca").focus();
    }
});
$(document).on("keyup","#marca",function(evt){
    if(evt.keyCode == 13)
    {
        $("#casa").focus();
    }
});
$(document).on("keyup","#casa",function(evt){
    if(evt.keyCode == 13)
    {
        $("#existencia").focus();
    }
});
$(document).on("click", ".elim", function()
{
  var id = $(this).attr("id");
  swal({
      title: "Esta seguro que desea eliminar este aro????",
      text: "Usted no podra deshacer este cambio",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Si, Eliminar",
      cancelButtonText: "No, Cerrar",
      closeOnConfirm: true
    },
    function() {
      $.ajax({
        type: "POST",
        url: "Aros/borrar_aro",
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
