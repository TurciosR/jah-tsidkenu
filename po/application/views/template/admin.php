<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: Wed, 1 Jan 2020 00:00:00 GMT"); // Anytime in the past

defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row" id="row1">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">

				<?php if (isset($url_agregar)): ?>
                    <div class="ibox-title">
                    <?php if(isset($modal_agregar)): ?>
                        <a href='<?=$url_agregar?>' class='btn btn-primary' role='button' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-plus icon-large'></i> <?=$txt_agregar;?></a>
                    <?php else: ?>
                        <a href='<?=$url_agregar?>' class='btn btn-primary'><i class='fa fa-plus icon-large'></i> <?=$txt_agregar;?></a>
                    <?php endif; ?>
                    </div>
				<?php endif; ?>

                <div class="ibox-content">
                    <!--load datables estructure html-->
                    <header>
                        <h3 class="text-navy"><i class="<?=$icono;?>"></i> <?=$nombre_archivo?></h3>
                    </header>
                    <section>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable datatable" id="editable2">
                                <thead class="thead-dark">
                                    <tr>
                                        <?php foreach ($tabla as $key => $value): ?>
                                            <th class='col-md-<?=$value?> text-success font-bold'><?= $key ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!--div class='ibox-content'-->
                    </section>
                    <input type="hidden" value="<?= $tipo; ?>" id="tipo">
                    <?php
                      if (isset($cent_cuenta_cob)) {
                        // code...
                        ?>
                        <input type="hidden" value="<?= $cent_cuenta_cob; ?>" id="cent_cuenta_cob">
                        <?php
                      }
                      else {
                        // code...
                        ?>
                        <input type="hidden" value="" id="cent_cuenta_cob">
                        <?php
                      }
                     ?>
                    <!--Show Modal Popups View & Delete -->
                </div>
                <!--div class='ibox-content'-->
            </div>
            <!--<div class='ibox float-e-margins' -->
        </div>
        <!--div class='col-lg-12'-->
    </div>
    <!--div class='row'-->
</div>

<div class='modal fade' id='viewModal' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-md'>
        <div class='modal-content modal-md'>
		</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script src="<?= base_url(); ?>assets/js/funciones/<?=$urljs; ?>"></script>
