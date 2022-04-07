$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btnDelete", function(event) {
    deleted();
  });
  // Clean the modal form
  $(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });

});

function senddata() {
  var laboratorio = $('#laboratorio').val();
  var process = $('#process').val();

  if (process == 'insert') {
    var id_laboratorio = 0;
    var urlprocess = 'agregar_laboratorio.php';
  }
  if (process == 'edited') {
    var id_laboratorio = $('#id_laboratorio').val();
    var urlprocess = 'editar_laboratorio.php';
  }
  var dataString = 'process=' + process + '&id_laboratorio=' + id_laboratorio + '&laboratorio=' + laboratorio;


  $.ajax({
    type: 'POST',
    url: urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      process = datax.process;
      display_notify(datax.typeinfo, datax.msg);
      if (datax.typeinfo != "Error") {
        setInterval("reload1();", 1000);
      }
    }
  });
}

function reload1() {
  location.href = 'admin_laboratorio.php';
}
