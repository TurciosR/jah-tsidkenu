<?php
include_once "_core.php";

function initial()
{
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];

  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);
  //permiso del script
  ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Agregar Laboratorio</h4>
  </div>
  <div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
      <div class="row" id="row1">
        <div class="col-lg-12">
          <?php if($links != 'NOT' || $admin == '1'){ ?>
            <form name="formulario" id="formulario" autocomplete="off">
              <div class="form-group has-info single-line">
                <label class="control-label" >Laboratorio</label>
                <input type="text" placeholder="Laboratorio" class="form-control" id="laboratorio" name="laboratorio">
              </div>
              <div>
                <input type="submit" class="btn btn-primary" value="Guardar">
                <input type="hidden" name="process" id="process" value="insert">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
      $('#formulario').validate({
        rules: {
          laboratorio: {
            required: true,
          },
        },
        messages: {
          laboratorio: "Por favor ingrese el laboratorio",
        },
        highlight: function(element) {
          $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
          $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form) {
          senddata();
        }
      });
      $(".select").select2();

    });
    </script>
    <?php

  }
  else
  {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
  }
}
function insert()
{
  $laboratorio = $_POST["laboratorio"];
  $sql_result= _query("SELECT * FROM laboratorio WHERE laboratorio='$laboratorio'");
  $row_update=_fetch_array($sql_result);
  $numrows=_num_rows($sql_result);

  $table = 'laboratorio';
  $form_data = array(
    'laboratorio' => $laboratorio,
  );

  if ($numrows == 0 && trim($laboratorio)!='') {
    $insertar = _insert($table, $form_data);
    if ($insertar)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Registro ingresado correctamente!';
      $xdatos['process']='insert';
    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Registro no pudo ser ingresado!';
      $xdatos['process']='none';
    }
  }
  else
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Ya se registro una ubicación con estos datos!';
    $xdatos['process']='none';
  }
  echo json_encode($xdatos);
}

if (!isset($_POST['process'])) {
  initial();
} else {
  if (isset($_POST['process'])) {
    switch ($_POST['process']) {
      case 'insert':
      insert();
      break;
    }
  }
}
?>
