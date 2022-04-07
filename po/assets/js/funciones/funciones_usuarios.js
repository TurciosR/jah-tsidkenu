var tipo = parseInt($("#tipo").val());
$(document).ready(function () {
  $("#tipo2").select2();
    var url = "Usuarios/get_data";
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
 var nombre = $("#nombre").val();
 var usuario = $("#usuario").val();
 var clave = $("#clave").val();
 if(nombre != "")
 {
   if(usuario != "")
   {
     if(clave != "")
     {
       senddata();
     }
     else {
       display_notify("Error", "Ingrese una clave");
     }
   }
   else {
     display_notify("Error", "Ingrese un usuario");
   }
 }
 else {
   display_notify("Error", "Ingrese un nombre");
 }
});
function senddata()
{
  var nombre = $("#nombre").val();
  var usuario = $("#usuario").val();
  var clave = $("#clave").val();
  var tipo2 = $("#tipo2").val();
  var process = $("#process").val();
  var url = $("#url").val();
  if(process == "insert")
  {
    var id_usuario = 0;
  }
  else {
    var id_usuario = $("#id_usuario").val();
  }
  var stringData = "process="+process+"&id_usuario="+id_usuario+"&nombre="+nombre+"&usuario="+usuario+"&clave="+clave+"&tipo2="+tipo2;
  $.ajax({
    type: 'POST',
    url: url+'Usuarios/guardar_usuario',
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
$(document).on("click", ".elim", function()
{
  var id = $(this).attr("id");
  swal({
      title: "Esta seguro que desea eliminar este usuario????",
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
        url: "Usuarios/borrar_usuario",
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
