$(document).ready(function()
{
  //evitar submit

  // $("form").on('submit', function(evt){
  //   evt.preventDefault();
  //   // tu codigo aqui
  // });
  $(".numeric").numeric({
    negative: false,
    decimal: false
  });
  $(".decimal").numeric({
    negative: false,
    decimalPlaces: 2
  });
  $(".decimalp").numeric({
    negative: true,
    decimalPlaces: 2
  });
  $('.tel').mask('0000-0000');
  $(".upper").blur(function() {
    $(this).val($(this).val().toUpperCase())
  });
  $(".lower").blur(function() {
    $(this).val($(this).val().toLowerCase())
  });
  $('.nit').mask("0000-000000-000-0");
  $('.dui').mask("00000000-0");

  $('.i-checks').iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green',
  });
  /*$('#editable').dataTable({
    "paging": true,
    "pageLength": 50,
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
  });*/
  $.fn.datepicker.dates['es'] = {
    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
  };
  //window.prettyPrint && prettyPrint();
  $(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    language: 'es',
  });
  var fecha = new Date();
  fecha.setDate(fecha.getDate()+10);

  $('.datepicker1').datepicker({
      format: 'dd-mm-yyyy',
      language: 'es',
      startDate: fecha,
  });
  $('.timepicker').mdtimepicker({
    timeFormat: 'hh:mm:ss.000',
    format: 'h:mm tt',
    theme: 'blue',
    readOnly: true,
    hourPadding: false
  });
});

function display_notify(typeinfo, msg, process) {
  // Use toastr for notifications get an parameter from other function
  var infotype = typeinfo;
  var msg = msg;
  toastr.options.positionClass = "toast-top-full-width";
  toastr.options.progressBar = true;
  toastr.options.debug = false;
  toastr.options.showDuration = 800;
  toastr.options.hideDuration = 1000;
  toastr.options.timeOut = 7000; // 1.5s
  toastr.options.showMethod = "fadeIn";
  toastr.options.hideMethod = "fadeOut";
  toastr.options.closeButton = true;
  if (infotype == 'success' || infotype == "Success") {
    toastr.success(msg, infotype);
    if (process == 'insert') {
      cleanvalues();
    }
  }
  if (infotype == 'info' || infotype == "Info") {
    toastr.info(msg, infotype);
  }
  if (infotype == 'warning' || infotype == "Warning") {
    toastr.warning(msg, infotype);
  }
  if (infotype == 'error' || infotype == "Error") {
    toastr.error(msg, infotype);
  }

}

function calcularDias(fecha1,fecha2){
  var dia1= fecha1.substr(0,2);
  var mes1= fecha1.substr(3,2);
  var anyo1= fecha1.substr(6);

  var dia2= fecha2.substr(0,2);
  var mes2= fecha2.substr(3,2);
  var anyo2= fecha2.substr(6);

  var nuevafecha1= new Date(anyo1+","+mes1+","+dia1);
  var nuevafecha2= new Date(anyo2+","+mes2+","+dia2);

  var diasDif = nuevafecha2.getTime() - nuevafecha1.getTime();

  var dias = Math.round(diasDif/(1000 * 60 * 60 * 24));

  return dias;
}
