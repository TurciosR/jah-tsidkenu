<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?=base_url();?>assets/img/logo1.png" rel="icon" type="image/png">

  <title>Punto Optico</title>

<!-- CSS -->
  <link href="<?=base_url();?>assets/css/bootstrap.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/plugins/iCheck/custom.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/modals.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet"  >
	<link href="<?=base_url();?>assets/css/waiting.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/css/steps.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/typeahead.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/util.css">

  <!-- Toastr style -->
  <link href="<?=base_url();?>assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

  <!-- Select 2 -->
  <link href="<?=base_url();?>assets/css/plugins/select2/select2.min.css" rel="stylesheet">
  <!-- Gritter -->

  <!-- Data Tables -->
  <link href="<?=base_url();?>assets/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

	<link rel="stylesheet" href="<?=base_url();?>assets/css/plugins/fileinput/fileinput.css" />
  <link rel="stylesheet" href="<?=base_url();?>assets/css/plugins/fileinput/themes/explorer-fa/theme.css" />
  <link rel="stylesheet" href="<?=base_url();?>assets/css/plugins/timepicker/mdtimepicker.css"/>

	<link href="<?=base_url();?>assets/css/main.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/animate.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/util.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/css/timeline.css">
    <!-- JS --->
    <!-- Mainly scripts -->
    <script src="<?=base_url();?>assets/js/plugins/jquery/jquery-2.1.1.js"></script>
    <script src="<?=base_url();?>assets/js/jquery.mask.js"></script>
    <script src="<?=base_url();?>assets/js/jquery.mask.min.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Flot -->
    <script src="<?=base_url();?>assets/js/plugins/iCheck/icheck.min.js"></script>
    <!-- Peity -->
    <script src="<?=base_url();?>assets/js/plugins/peity/jquery.peity.min.js"></script>
    <!-- jQuery UI -->
    <script src="<?=base_url();?>assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="<?=base_url();?>assets/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="<?=base_url();?>assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src='<?=base_url();?>assets/js/plugins/arrowtable/arrow-table.js'></script>

    <!-- ChartJS-->
    <script src="<?=base_url();?>assets/js/plugins/chartJs/Chart.js"></script>

    <!-- Toastr -->
    <script src="<?=base_url();?>assets/js/plugins/toastr/toastr.min.js"></script>

    <!-- Select 2 -->
    <script src="<?=base_url();?>assets/js/plugins/select2/select2.min.js"></script>



    <!-- Data Tables -->
    <script src="<?=base_url();?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/dataTables/dataTables.tableTools.min.js"></script>

    <!-- The basic File Upload plugin -->
    <script src="<?=base_url();?>assets/js/plugins/fileinput/fileinput.js"></script>
    <script src="<?=base_url();?>assets/css/plugins/fileinput/themes/explorer-fa/theme.js"></script>
    <!-- The File Upload processing plugin -->
    <script src="<?=base_url();?>assets/js/inspinia.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/pace/pace.min.js"></script>
    <script src='<?=base_url();?>assets/js/plugins/typeahead11/bloodhound.min.js'></script>
    <script src='<?=base_url();?>assets/js/plugins/typeahead11/typeahead.jquery.min.js'></script>
    <script src="<?=base_url();?>assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/datapicker/moment.min.js"></script>
    <script src="<?=base_url();?>assets/js/plugins/timepicker/mdtimepicker.js"></script>
    <script type="text/javascript" src="<?=base_url();?>assets/js/plugins/numeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="<?=base_url();?>assets/js/main.js"></script>

    <script type="text/javascript" src="<?=base_url();?>assets/js/plugins/fullcalendar/fullcalendar.js"></script>

    <!-- Moment js -->
    <script src="<?=base_url();?>assets/js/moment.js"></script>
    <script>var base_url = '<?php echo base_url() ?>';</script>
    <!--script type="text/javascript" src="<?=base_url();?>assets/js/plugins/fullcalendar/es.js"></script-->

</head>
<body id="page-top" class="fixed-sidebar fixed-navbar pace-done" landing-scrollspy="" ng-controller="MainCtrl as main">
<div id="cargando" class="cargando" style="display: none;"><div class="loader"></div></div>
