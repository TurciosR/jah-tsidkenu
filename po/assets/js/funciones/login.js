$(document).ready(function() {
  $("#correo").keyup(function(event) {
    if ($(this).val() != "") {
      if (event.keyCode == 13) {
        $("#clave").focus();
      }
    }
  });
  $("#clave").keyup(function(event) {
    if ($(this).val() != "") {
      if (event.keyCode == 13) {
        iniciar_sesion();
      }
    }
  });
});
$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btn_ini_sesion", function(event) {
    iniciar_sesion();
  });
});

function iniciar_sesion() {
  var correo = $("#correo").val();
  var clave = $("#clave").val();
  $.ajax({
    type: 'POST',
    url: "Login/iniciar_sesion",
    data: "correo=" + correo + "&clave=" + clave,
    dataType: 'JSON',
    success: function(datax) {
    // swal(datax.title, datax.msg, datax.typeinfo);
      if (datax.typeinfo == 'success')
      {
        setTimeout("location.replace('Dashboard');", 500);
      }
    }
  });
}
