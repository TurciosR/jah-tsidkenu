<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"
  aria-hidden="true">&times;</button>
  <h4 class="modal-title">Editar <?= $tipo ?></h4>
</div>
<div class="modal-body">
  <!--div class="wrapper wrapper-content  animated fadeInRight"-->
  <div class="row" id="row1">
    <!--div class="col-lg-12"-->
    <form name="form_editarmovimiento" id="form_editarmovimiento" enctype="multipart/form-data" autocomplete="off">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group has-info single-line">
            <label>Monto </label> <input type='text'  class='form-control numeric' id='monto' name='monto' value="<?= $total ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group has-info single-line">
            <label>Responsable</label>
            <input type='text'  class='form-control upper' id='encargado' name='encargado' value="<?= $responsable ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group has-info single-line">
            <label>Concepto</label>
            <textarea class="form-control upper" id='concepto' name='concepto'rows="3"><?= $concepto ?></textarea>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="form-actions col-lg-12">
          <input type="hidden" name="id_movimiento" id="id_movimiento" value="<?php echo $id_movimiento;?>">
          <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo;?>">
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
  $('#form_editarmovimiento').validate({
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
      editar_movimiento();
    }
  });
});

$(document).on("submit", "#form_editarmovimiento", function (e) {
  e.preventDefault();
  alert("hola");
  editar_movimiento();
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
