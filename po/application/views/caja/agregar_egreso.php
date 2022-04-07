<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"
  aria-hidden="true">&times;</button>
  <h4 class="modal-title">Agregar Egreso</h4>
</div>
<div class="modal-body">
  <!--div class="wrapper wrapper-content  animated fadeInRight"-->
  <div class="row" id="row1">
    <!--div class="col-lg-12"-->
    <form name="form_egreso" id="form_egreso" enctype="multipart/form-data" autocomplete="off">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group has-info single-line">
            <label>Monto </label> <input type='text'  class='form-control numeric' id='monto' name='monto'>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="form-group has-info single-line">
            <label>Responsable</label>
            <input type='text'  class='form-control upper' id='encargado' name='encargado'>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group has-info single-line">
            <label>Concepto</label>
            <textarea class="form-control upper" id='concepto' name='concepto'rows="3"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-actions col-lg-12">
          <button type="submit" id="agregarUnidad" class="btn btn-primary m-t-n-xs pull-right"> Guardar</button>
        </div>
      </div>
    </form>
  </div>
  <!--/div-->
  <!--/div-->

</div>

<script type="text/javascript">
$(document).ready(function () {
  $('#form_egreso').validate({
    rules: {
      monto: {
        required: true,
      },
      concepto: {
        required: true,
      },
      encargado: {
        required: true,
      },
    },
    messages: {
      monto: "Por favor ingrese el monto",
      concepto: "Por favor ingrese el concepto",
      encargado: "Por favor ingrese el responsable",
    },
    submitHandler: function (form) {
      agregar_egreso();
    }
  });
});

$(document).on("submit", "#form_egreso", function (e) {
  e.preventDefault();
  agregar_egreso();
});

$(".numeric").numeric(
  {
    negative:false,
  }
);
$(".upper").blur(function() {
  $(this).val($(this).val().toUpperCase())
});
</script>
