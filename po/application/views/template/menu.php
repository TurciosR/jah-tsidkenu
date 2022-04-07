<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="wrapper">
  <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
      <ul class="nav" id="side-menu">
        <li class="nav-header">
          <div class="dropdown profile-element"> <span>
            <span>
              <img alt="image" class="logo1" id='logo_menu' src="<?php echo base_url();?>assets/img/logo1.png" style="width:60%; margin-left:15%; margin-top:0%;" />
            </span>
          </div>
          <div class="logo-element">
            EOR
          </div>
        </li>
          <li><a href="<?=base_url();?>Dashboard" style='color:white;'><i class="fa fa-home"></i></i> <span class='nav-label'>Inicio</span> <span class='fa arrow'></span></a></li>
				<?php foreach ($menus as $menu): ?>
					<li><a  style='color:white;'><i class="<?=$menu->icono;?>"></i></i> <span class='nav-label'><?=$menu->nombre;?></span> <span class='fa arrow'></span></a>
						<ul class='nav nav-second-level'>
						<?php
							foreach ($modulos[$menu->id_menu] as $modulo):
						?>
							<li><a href="<?=base_url().$modulo->filename;?>"><?=$modulo->nombre;?></a></li>
						<?php endforeach; ?>
						</ul>
				</li>
        <?php
        endforeach;
        ?>
      </ul>
    </div>
  </nav>
  <div id="page-wrapper" class="gray-bg">
    <div class="row border-bottom">
      <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0;">
        <div class="navbar-header">
          <a class="navbar-minimalize minimalize-styl-2 btn btn-primary"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">

            <li>
                <br><span class="m-r-sm text-muted welcome-message"><b><?=$nombre_session;?></b></span>
                <?php
                if($this->session->cargo)
                {
                  ?>
                  <span class="m-r-sm text-muted welcome-message" ><b>(<?=$this->session->cargo;?>)</b></span>
                  <?php
                }
                ?>

            </li>
          <li>
            <a href="<?= base_url("Login/logout"); ?>" class="font-bold">
              <i class="fa fa-sign-out"></i> Cerrar Sesion</span>
            </a>
          </li>
        </ul>

      </nav>
    </div>
