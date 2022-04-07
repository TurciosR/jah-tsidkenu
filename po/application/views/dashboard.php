<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-9">
		<br>
		<h3>Buen Dia, <?=$this->session->usuario;?></h3>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">

	<!--Administrador-->
		<div class="row">
		<?php if ($this->session->tipo == 1){ ?>
		<div class="col-lg-3">
				<a href="<?=base_url()."Examen/agregar_examen";?>">
					<div class="ibox float-e-margins " >
						<div class="ibox-title navy-bg">
							<span class="label pull-right" style="background:#ffffff;color:#1c84c6">
								<h2><?=1?></h2>
							</span>
							<i class="fa fa-drivers-license fa-5x "></i>
						</div>
						<div class="ibox-content navy-bg">
							<h3 class="no-margins">Nuevo Examen</h3>
						</div>
					</div>
				</a>
			</div>
		<?php } ?>
			<!--
			<div class="col-lg-3">
				<a href="<?=base_url();?>Permisos/agregar">
					<div class="ibox float-e-margins " >
						<div class="ibox-title yellow-bg">
							<span class="label pull-right" style="background:#ffffff;color:#ed5565">
								<h2><?=1?></h2>
							</span>
							<i class="fa fa-credit-card  fa-5x "></i>
						</div>
						<div class="ibox-content yellow-bg">
							<h3 class="no-margins">Solicitudes Vacaciones</h3>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-3">
				<a href="<?=base_url();?>Vacaciones">
					<div class="ibox float-e-margins " >
						<div class="ibox-title red-bg">
							<span class="label pull-right" style="background:#ed5565;color:#fff">
								<h2>Gestionar</h2>
							</span>
							<i class="fa fa-cogs fa-5x "></i>
						</div>
						<div class="ibox-content red-bg">
							<h3 class="no-margins">Configuraci&oacute;n</h3>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-3">
				<a href="<?=base_url();?>Vacaciones/agregar">
					<div class="ibox float-e-margins " >
						<div class="ibox-title navy-bg">
							<span class="label pull-right" style="background:#1c84c6;color:#fff">
								<h2>Gestionar</h2>
							</span>
							<i class="fa fa-users fa-5x "></i>
						</div>
						<div class="ibox-content navy-bg">
							<h3 class="no-margins">Colaboradores</h3>
						</div>
					</div>
				</a>
			</div>-->

		</div>


</div>
