<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo base_url(); ?>assets/img/logo1.png" rel="icon" type="image/png">

  <title>Punto Optico</title>

  <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/plugins/iCheck/custom.css" rel="stylesheet">

  <!-- Toastr style -->
  <link href="<?php echo base_url(); ?>assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">

  <!-- Select 2 -->
  <link href="<?php echo base_url(); ?>assets/css/plugins/select2/select2.min.css" rel="stylesheet">

  <!-- Gritter -->
  <link href="<?php echo base_url(); ?>assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

  <link href="<?=base_url();?>assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

  <!-- Data Tables -->
  <link href="<?php echo base_url(); ?>assets/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

  <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/animate.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">

</head>

<body class="white-bg">
  <div class="loginColumns animated fadeInDown">
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6 text-center">
        <h2 class="font-bold">Punto Optico</h2>
        <div>
          <center>
            <img alt="image" style="width: 50%; margin-top:0%;" src="<?php echo base_url() . "assets/img/logo_eor.png"; ?>" />
          </center>
        </div>
        <div class="ibox-content">
          <label>Bienvenido! <br>Por favor ingrese sus credenciales.</label>
          <form class="m-t" role="form" method="POST">
            <div class="form-group">
              <input type="text" name="correo" class="form-control" id="correo" placeholder="mail@eor.com" required="">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" id="clave" required="" name="clave">
            </div>
            <div class="row">
              <div class="col-lg-12">
                <button type="button" class="btn btn-primary block full-width m-b" id="btn_ini_sesion">Iniciar Sesi&oacute;n</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</body>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery/jquery-2.1.1.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/sweetalert/sweetalert.min.js"></script>
<script>var base_url = '<?php echo base_url() ?>';</script>
<script src="<?php echo base_url(); ?>assets/js/funciones/login.js"></script>
</html>
