<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox" id="main_view">
                <div class="ibox-title">
                    <h3 class="text-navy"><b><i class="fa fa-pencil"></i> <?= $titulo; ?></b></h3>
                </div>
                <div class="ibox-content">
                    <?php
                    //Dibuja el formulario
                    $salto = 0;
                    echo "<form";
                    foreach ($formulario as $key => $value) {
                        echo " $key='$value' ";
                    }
                    echo ">";

                    //Dibuja campos del formulario
                    echo "<div class='row'>";
                    foreach ($campos as $campo) {
                        if($salto==12){
                            echo "</div>";
                            echo "<div class='row'>";
                            $salto=0;
                        }
                        echo "<div class='form-group ";
                        foreach ($campo as $camp => $valor) {
                            if ($camp == "lenght") {
                                echo "col-lg-$valor'>";
                                $salto+=$valor;
                            } else if ($camp == "nombre") {
                                echo "<label for=''>$valor</label>";
                            } else if ($camp == "tipo") {
                                $tipo = $valor;
                            } else if ($camp == "prop" or $camp == "opciones") {
                                switch ($tipo) {
                                    case 'text':
                                        echo "<input";
                                        foreach ($valor as $atr => $val) {
                                            if ($val != "") {
                                                echo " $atr='$val' ";
                                            } else {
                                                echo " $atr ";
                                            }
                                        }
                                        echo ">";
                                        break;

                                    case 'select':
                                        echo "<select";
                                        foreach ($valor as $atr => $val) {
                                            if ($val != "" and $atr != "valores") {
                                                echo " $atr='$val' ";
                                            } else if ($atr != "valores") {
                                                echo " $atr ";
                                            } else if ($atr == 'valores') {
                                                echo ">";
                                                echo "<option value=''>Seleccione</option>";
                                                foreach ($val as $field => $resp) {
                                                    echo "<option value='$field'>$resp</option>";
                                                }
                                            }
                                        }
                                        echo "</select>";
                                        break;

                                    case 'radio':
                                        foreach ($valor as $opc) {
                                            echo "<div class='radio'>";
                                            echo "<label>";
                                            echo "<input ";
                                            foreach ($opc as $atr => $val) {
                                                if ($val != "") {
                                                    echo " $atr='$val' ";
                                                } else {
                                                    echo " $atr ";
                                                }
                                            }
                                            echo ">" . $opc['txt'] . "</label>";
                                            echo "</div>";
                                        }
                                        break;

                                    case 'radio_inline':
                                        echo "<br>";
                                        foreach ($valor as $opc) {
                                            echo "<label class='radio-inline'>";
                                            echo "<input ";
                                            foreach ($opc as $atr => $val) {
                                                if ($val != "") {
                                                    echo " $atr='$val' ";
                                                } else {
                                                    echo " $atr ";
                                                }
                                            }
                                            echo ">" . $opc['txt'] . "</label>";
                                        }
                                        break;

                                    case 'checkbox':
                                        foreach ($valor as $opc) {
                                            echo "<div class='checkbox'>";
                                            echo "<label>";
                                            echo "<input ";
                                            foreach ($opc as $atr => $val) {
                                                if ($val != "") {
                                                    echo " $atr='$val' ";
                                                } else {
                                                    echo " $atr ";
                                                }
                                            }
                                            echo ">" . $opc['txt'] . "</label>";
                                            echo "</div>";
                                        }
                                        break;
                                    case 'checkbox_inline':
                                        echo "<br>";
                                        foreach ($valor as $opc) {
                                            echo "<label class='checkbox-inline'>";
                                            echo "<input ";
                                            foreach ($opc as $atr => $val) {
                                                if ($val != "") {
                                                    echo " $atr='$val' ";
                                                } else {
                                                    echo " $atr ";
                                                }
                                            }
                                            echo ">" . $opc['txt'] . "</label>";
                                        }
                                        break;


                                    case 'textarea':
                                        echo "<textarea";
                                        foreach ($valor as $atr => $val) {
                                            if ($val != "") {
                                                echo " $atr='$val' ";
                                            } else {
                                                echo " $atr ";
                                            }
                                        }
                                        echo "></textarea>";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        }
                        echo "</div>";

                    }
                    if($salto<12){
                        echo "</div>";
                    }
                    ?>
                    <div class="row">
                        <div class="form-actions col-lg-12">
                            <?php
                            echo "<input";
                            foreach ($proccess as $key => $value) {
                                echo " $key='$value' ";
                            }
                            echo ">";
                            echo "<button";
                            $texto="";
                            foreach ($button as $key => $value) {
                                if($key!="texto"){
                                    echo " $key='$value' ";
                                }else{
                                    $texto = $value;
                                }
                            }
                            echo ">".$texto."</button>";
                            ?>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>">
                    <?php
                    echo "</form>";
                    ?>
                </div>
            </div>
			<div class="ibox" style="display: none;" id="divh">
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content text-center">
								<h2 class="text-danger blink_me">Espere un momento, Procesando Solicitud !!!</h2>
								<section class="sect">
									<div id="loader">
									</div>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
</div>
<script src="<?= base_url(); ?>assets/js/funciones/<?= $urljs; ?>"></script>
