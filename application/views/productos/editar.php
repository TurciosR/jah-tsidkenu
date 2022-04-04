<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox" id="main_view">
                <div class="ibox-title">
                    <h3 class="text-success"><b><i class="mdi mdi-square-edit-outline"></i> Editar Producto</b></h3>
                </div>
                <div class="ibox-content">
                    <form id="form_edit" novalidate>
                      <div class="row">
                        <div class="col-lg-12" >
                          <div class="form-group single-line">
                            <label for="nombre">Descripción</label>
                            <input type="text" name="nombre" id="nombre" class="form-control mayu"  value="<?=$row->nombre?>"
                            placeholder="Ingrese descripcion"
                            required  data-parsley-trigger="change">
                          </div>
                        </div>
                      </div>
                        <div class="row">
                          <div class="col-lg-6">
                              <div class="form-group single-line">
                                  <label for="marca">Marca</label>
                                  <input type="text" name="marca" id="marca" class="form-control mayu"  placeholder="Ingrese una marca" value="<?=$row->marca?>"
                                          data-parsley-trigger="change">
                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group single-line">
                                  <label for="modelo">Modelo</label>
                                  <input type="text" name="modelo" id="modelo" class="form-control mayu"  placeholder="Ingrese un modelo" value="<?=$row->modelo?>"
                                          data-parsley-trigger="change">
                              </div>
                          </div>
                            <div class="col-lg-3" hidden>
                                <div class="form-group single-line">
                                    <label for="codigo_generico">Codigo Generico</label>
                                    <input type="text" name="codigo_generico" id="codigo_generico" class="form-control mayu"  placeholder="Ingrese un codigo" value="<?=$row->codigo_generico?>"
                                            data-parsley-trigger="change">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-4">
                              <div class="form-group single-line">
                                  <label for="codigo_barra">Codigo de Barra</label>
                                  <input type="text" name="codigo_barra" id="codigo_barra" class="form-control mayu"  placeholder="Ingrese un codigo" value="<?=$row->codigo_barra?>"
                                         data-parsley-trigger="change">
                              </div>
                          </div>
                          <div class="col-lg-2">
                              <div class="form-group single-line">
                                  <label for="categoria">Categoria</label>
                                  <select name="categoria" id="categoria" class="form-control select2" required data-parsley-trigger="change">
                                      <?php foreach ($categorias as $cat): ?>
                                          <option value="<?=$cat->id_categoria?>"
                                          <?php if($cat->id_categoria==$row->id_categoria) echo "selected"; ?>
                                          ><?=$cat->nombre?></option>
                                      <?php endforeach; ?>
                                  </select>
                              </div>
                          </div>
                            <div class="col-sm-4">
                              <div class="form-group has-info single-line">
                                <label>Costo sin IVA</label>
                                <input required type="text" placeholder="Costo" class="form-control ccos decimal" id="ultcosto" value="<?=number_format($row->costo_s_iva,2,".","") ?>">
                                <input type="hidden" id="costo_s_iva" name="costo_s_iva" value="0">
                                <input type="hidden" id="costo_c_iva" name="costo_c_iva" value="0">
                                <input type="hidden" id="precio_sugerido" name="precio_sugerido">
                                <input type="hidden" id="preciosg" name="preciosg">
                                <input type="hidden" id="porcentaje_iva" name="porcentaje_iva" value="<?=$config_impuestos->iva?>">

                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="">Exento</label>
                              <div class="row">
                                <div class="col-sm-6 float_left">
                                  <label for="">Si</label><br>
                                  <input type="radio" class="exento_iva" name="exento_iva" value="1" <?=($row->exento==1)?'checked':'';?>>
                                </div>
                                <div class="col-sm-6 float_left">
                                  <label for="">No</label><br>
                                  <input type="radio" class="exento_iva" name="exento_iva" value="0" <?=($row->exento==1)?'':'checked';?>>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3" style="display:none;">
                                <div class="form-group single-line">
                                    <fieldset>
                                        <legend>
                                            Cesc
                                        </legend>
                                        <div class="form-check abc-radio abc-radio form-check-inline">
                                            <input class="form-check-input" type="radio" id="cesc1" value="1" name="cesc" <?php if ($row->cesc==1) echo "checked"; ?>>
                                            <label class="form-check-label" for="cesc1"> SI </label>
                                        </div>
                                        <div class="form-check abc-radio form-check-inline">
                                            <input class="form-check-input" type="radio" id="cesc2" value="0" name="cesc" <?php if ($row->cesc==0) echo "checked"; ?>>
                                            <label class="form-check-label" for="cesc2"> NO </label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-3" hidden>
                                <div class="form-group single-line">
                                    <fieldset>
                                        <legend>
                                            Seguro
                                        </legend>
                                        <div class="form-check abc-radio abc-radio form-check-inline">
                                            <input class="form-check-input" type="radio" id="seguro1" value="1" name="seguro" <?php if ($row->seguro==1) echo "checked"; ?>>
                                            <label class="form-check-label" for="seguro1"> SI </label>
                                        </div>
                                        <div class="form-check abc-radio form-check-inline">
                                            <input class="form-check-input" type="radio" id="seguro2" value="0" name="seguro" <?php if ($row->seguro==0) echo "checked"; ?>>
                                            <label class="form-check-label" for="seguro2"> NO </label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-lg-3 preciiseg" <?php if ($row->seguro==0) echo "hidden"; ?>>
                                <div class="form-group single-line">
                                    <label for="precio_seguro">Precio Seguro</label>
                                    <input type="text" name="precio_seguro" id="precio_seguro" class="form-control decimal"  placeholder="Ingrese un precio sugerido" value="<?=number_format($row->precio_sugerido,2,".","") ?>"
                                           required data-parsley-trigger="change">
                                </div>
                            </div>
                            <div class="col-lg-3" style="display:none;">
                                <div class="form-group single-line">
                                    <label for="dias_garantia">Dias de Garantia Nuevo</label>
                                    <input type="text" name="dias_garantia" id="dias_garantia" class="form-control numeric"  placeholder="Ingrese los dias de garantia" value="<?=$row->dias_garantia?>"
                                           required data-parsley-trigger="change">
                                </div>
                            </div>

                        </div>
                        <div class="row">

                          <div class="col-lg-3" style="display:none;">
                              <div class="form-group single-line">
                                  <label for="dias_garantia">Dias de Garantia Usado</label>
                                  <input type="text" name="dias_garantia_usado" id="dias_garantia_usado" class="form-control numeric"  placeholder="Ingrese los dias de garantia" value="0"
                                         required data-parsley-trigger="change">
                              </div>
                          </div>
                          <div class="col-lg-3" style="display:none;">
                              <div class="form-group single-line">
                                  <fieldset>
                                      <legend>
                                          Requiere IMEI
                                      </legend>
                                      <div class="form-check abc-radio abc-radio form-check-inline">
                                          <input class="form-check-input" type="radio" id="imei1" value="1" name="imei" <?php if ($row->imei==1) echo "checked"; ?>>
                                          <label class="form-check-label" for="imei1"> SI </label>
                                      </div>
                                      <div class="form-check abc-radio form-check-inline">
                                          <input class="form-check-input" type="radio" id="imei2" value="0" name="imei" <?php if ($row->imei==0) echo "checked"; ?>>
                                          <label class="form-check-label" for="imie2"> NO </label>
                                      </div>
                                  </fieldset>
                              </div>
                          </div>
                          <div class="col-lg-3 imeis" <?php if ($row->imei==0) echo "hidden"; ?>>
                            <div class="form-group single-line">
                              <label for="precio_seguro">Numero de IMEI's</label>
                              <select style="width:100%" class="select2" id="n_imei" name="n_imei">
                                <?php
                                for ($i=1; $i <= 5; $i++) {
                                  // code...
                                  ?>
                                  <option value="<?=$i?>" <?php if($row->n_imei==$i) echo "selected";?>><?=$i?></option>
                                  <?php
                                }
                                 ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-12">
                            <table class="table table-striperd table-hover table-bordered">
                              <thead>
                                <tr>
                                  <th class="" style="text-align: center">Descripción</th>
                                  <th class="" style="text-align: center">Costo</th>
                                  <th class="" style="text-align: center">IVA</th>
                                  <th class="" style="text-align: center">CESC</th>
                                  <th class="" style="text-align: center">Costo Total</th>
                                  <th class="" style="text-align: center">Precio Venta</th>
                                  <th class="" style="text-align: center">Ganancia $</th>
                                  <!--th class="" style="text-align: center">Accion</th-->
                                </tr>
                              </thead>
                              <tbody id="precios">
                                <?php
                                $lista = "";
                                if ($row->exento==1) {
                                  // si el producto es exento de iva...
                                  $ivaPorc = 0;
                                }
                                else {
                                  // code...
                                  $ivaPorc = 0.13;
                                }
                                foreach ($precios as $row_por)
                                {
                                  $costo = $row_por->costo;
                                  $iva = round($costo * $ivaPorc, 2);
                                  $cesc = round($costo * 0.00, 2);
                                  $ctotal = $costo+$iva+$cesc;
                                  $id = $row_por->id_listaprecio;

                                  $costo_iva = $row_por->costo_iva;
                                  $detalle = $row_por->descripcion;

                                  $resultado2 = $row_por->precio_venta;;
                                  $gana = $resultado2 - $ctotal;
                                  $lista .= "<tr>";

                                  $lista .= "<td style='text-align: right' class='td_desc'><input type='hidden' class='form-control lista_pr' id='id_lista_pr' name='id_lista_pr' value='".$id."'><input type='text' style='width:350px;' class='form-control desc_td' id='desc_td' name='desc_td' value='".$detalle."' readonly></td>";
                                  $lista .= "<td style='text-align: right' class='td_costo'><input type='hidden' class='form-control costo_td' id='costo_td' name='costo_td' value='".$costo."'>$ ".number_format($costo,2, '.', '')."</td>";
                                  $lista .= "<td style='text-align: right' class='td_costo_iva'><input type='hidden' class='form-control costo_td_iva' id='costo_td_iva' name='costo_td_iva' value='".$iva."'>$ ".number_format($iva,2, '.', '')."</td>";
                                  $lista .= "<td style='text-align: right' class='td_precio'><input type='hidden' class='form-control precio_td' id='precio_td' name='precio_td' value='".$cesc."'>$ ".number_format($cesc, 2, '.', '')."</td>";
                                  $lista .= "<td style='text-align: right' class='td_precio_iva'><input type='hidden' class='form-control precio_td_iva' id='precio_td_iva' name='precio_td_iva' value='".$ctotal."'>$ ".number_format($ctotal,2, '.', '')."</td>";
                                  $lista .= "<td style='text-align: right' class='td_preciolista'><input type='text' class='form-control listaprecios' id='preciolista' name='preciolista' value='".number_format($resultado2,2, '.', '')."'></td>";
                                  $lista .= "<td style='text-align: right' class='td_ganancia'><input type='hidden' class='form-control ganancia_td' id='ganancia' name='ganancia' value='".$gana."'>$ ".number_format($gana, 2, '.', '')."</td>";
                                //  $lista .= "<td style='text-align: right'><button type='button' id='delete' class='btn btn-success delete'><i class='mdi mdi-delete'></i></button></td>";
                                  $lista .= "</tr>";
                                }

                                echo $lista;
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-3">
                            <div class="form-group single-line">
                              <label for="color">Color</label>
                              <select class="form-control" id="color" name=""></select>
                               <input type="hidden" name="coloresg" id="coloresg">

                            </div>
                          </div>
                          <div class="col-lg-1"><br>
                            <button type="button" id="btn_add_col" name="btn_add_col"
                            class="btn btn-success m-t-n-xs"><i
                            class="mdi mdi-content-add"></i>
                            Agregar
                          </button>
                          </div>
                          <div class="col-lg-5">

                          </div>
                          <div class="col-lg-3"><br>
                            <button type="button" id="btn_color_express" name="btn_color_express"
                            data-toggle="modal" data-target="#modalColorExpress"
                            class="btn btn-success m-t-n-xs"><i
                            class="mdi mdi-content-add"></i>
                            Agregar Nuevo Color
                          </button>
                          </div>
                          </div>
                          <div class="row">
                          <div class="col-lg-12">
                            <table class="table table-striperd table-hover table-bordered">
                              <thead>
                                <tr>
                                  <th style="width: 80%; text-align: center">Color</th>
                                  <th style="width: 20%; text-align: center">Accion</th>
                                </tr>
                              </thead>
                              <tbody id="colores">
                                <?php
                                $nc=0;
                                foreach ($colores as $color)
                                {

                                  echo "<tr id='".$nc."'>";
                                  echo "<td class='colora'>".$color->color."</td>";
                                  echo "<td class='text-center'><a class='btn btn-danger delete_tr' style='color: white'><i class='mdi mdi-trash-can'></i></a></td>";
                                  echo "</tr>";
                                  $nc++;
                                }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mt-3">
                                    <label for="foto">Fotos del producto</label>
                                    <div class="input-images-edit" style="padding-top: .5rem;"></div>
                                    <p class="text-muted text-center mt-2 mb-0">Haz click en el cuadro para agregar imagenes</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-actions col-lg-12">
                                <input type="hidden" name="id_producto" id="id_producto" value="<?=$row->id_producto?>">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" id="csrf_token_id">
                                <button type="submit" id="btn_edit" name="btn_edit" class="btn btn-success m-t-n-xs float-right"><i class="mdi mdi-content-save"></i>
                                    Guardar Registro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="ibox" style="display: none;" id="divh">
                <div class="ibox-content text-center">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="text-danger blink_me">Espere un momento, procesando su solicitud!</h2>
                            <section class="sect">
                                <div id="loader">
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="modalColorExpress" tabindex="-1" role="dialog" aria-labelledby="modalColorExpress" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form id="form_add_color_express" method="post">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalColorExpress">Agregar Color</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label for="">Color:</label>
                          <input type="text" class="form-control mayu" name="color" id="color" placeholder="Ingrese color" required value="">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                        value="<?php echo $this->security->get_csrf_hash(); ?>" id="csrf_token_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                      </div>
                    </form>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>
<script>
    let preloaded = [];
    let token1 = $("#csrf_token_id").val()
    $.ajax({
        type: "POST",
        url: base_url+"productos/get_images",
        data: {id :$("#id_producto").val(),csrf_test_name:token1},
        dataType: 'json',
        success: function (data) {
            $.each(data, function(index, item) {
                preloaded.push({id:item['id'],src:item['imagen']})
            });
        },
        complete:function () {
            $('.input-images-edit').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'photos',
                preloadedInputName: 'old'
            });
        }
    });


</script>
