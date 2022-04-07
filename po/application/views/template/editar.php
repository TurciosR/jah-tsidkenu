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
                    //echo "<input type='hidden' id='id_tipo_permiso' name='id_tipo_permiso' value='$id_tipo_permiso'>";
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
                                        $seleccionado=0;
                                        foreach ($valor as $atr => $val) {
                                            if ($val != "" and $atr != "valores") {
                                                echo " $atr='$val' ";
                                            } else if ($atr != "valores") {
                                                echo " $atr ";
                                            } else if ($atr == 'valores') {
                                                echo ">";
                                                //echo "<option value=''>Seleccione</option>";
                                                foreach ($val as $field => $resp) {
                                                    if($field=="selected"){
                                                        $seleccionado = $resp;
                                                    }else{
                                                        if($seleccionado==$field){
                                                            echo "<option value='$field' selected>$resp</option>";
                                                        }else{
                                                            echo "<option value='$field'>$resp</option>";
                                                        }

                                                    }

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
                                                    if($atr=="0"){
                                                        echo " $val='$atr' ";
                                                    }else{
                                                        echo " $atr='$val' ";   
                                                    }
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
                                        $getAtr = false;
                                        foreach ($valor as $atr => $val) {
                                            if ($val != "" and $atr != "value") {
                                                echo " $atr='$val' ";
                                            } else {
                                                echo " $atr ";
                                            }
                                            if($atr=="value"){
                                                $getAtr=true;   
                                            }
                                        }
                                        if($getAtr==true){
                                            echo ">$val</textarea>";
                                        }else{
                                            echo "></textarea>";
                                        }


                                        break;
                                    case 'imagen':
                                        echo "<div class='file-loading'>";
                                        echo "<input";
                                        foreach ($valor as $atr => $val) {
                                            if ($val != "") {
                                                echo " $atr='$val' ";
                                            } else {
                                                echo " $atr ";
                                            }
                                        }
                                            echo "></div>";


                                        break;

                                    default:
                                        
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
						<input type="hidden" name="id_cambio" value="<?=$id_cambio?>">
                    </div>
                    <?php
                    echo "</form>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/funciones/<?= $urljs; ?>"></script>
