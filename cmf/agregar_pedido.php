  <?php
  include_once "_core.php";

  function initial()
  {
    $title = "Agregar Pedido";
    $_PAGE = array();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $id_sucursal=$_SESSION["id_sucursal"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    $fecha_actual=date("Y-m-d");

    ?>

    <div class="row wrapper border-bottom white-bg page-heading">
      <div class="col-lg-2"></div>
    </div>
    <div class="wrapper wrapper-content  animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox">
            <div class="ibox-title">
              <h5><?php echo $title;?></h5>
            </div>
            <?php if ($links!='NOT' || $admin=='1') { ?>
              <div class="ibox-content">

                <div class='row' id='form_invent_inicial'>
                  <div class="col-lg-6">
                    <div class="form-group has-info">
                      <label>Cliente</label>
                      <input type='text' class='form-control' value='' id='cliente_buscar' name='cliente_buscar'>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group has-info">
                      <label>Lugar de entrega</label>
                      <input type="text" class='form-control' name="lugar_entrega" id="lugar_entrega">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class='col-lg-6'>
                    <div class='form-group has-info'>
                      <label>Fecha</label>
                      <input type='text' class='datepick form-control' value='<?php echo $fecha_actual; ?>' id='fecha' name='fecha'>
                    </div>
                  </div>
                  <div class='col-lg-6'>
                    <div class='form-group has-info'>
                      <label>Fecha entrega</label>
                      <input type='text' class='datepick form-control' value='<?php echo $fecha_actual; ?>' id='fecha2' name='fecha2'>
                    </div>
                  </div>
                </div>
                <div class="row" id='buscador'>
                  <div class="col-lg-6">
                    <div class='form-group has-info'><label>Buscar Productos</label>
                      <input type="text" id="producto_buscar" name="producto_buscar" size="20" class="producto_buscar form-control" placeholder="Ingrese nombre de producto"  data-provide="typeahead">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group has-info single-line">
                        <div class='checkbox i-checks'>
                          <label id='frentex'>
                              <input type='checkbox' id='checkreser' name='checkreser' value="0"><strong> Reservado</strong>
                          </label>
                        </div>
                        <input type='hidden' id='reservado' name='reservado' value="0">
                    </div>
                  </div>
                </div>
                <div class="ibox">
                  <div class="row">
                    <div class="ibox-content">
                      <!--load datables estructure html-->
                      <header>
                        <h4 class="text-navy">Lista de Productos</h4>
                      </header>
                      <section>
                        <table class="table table-striped table-bordered table-condensed" id="">
                          <thead>
                            <tr>
                              <th class="col-lg-1">Id</th>
                              <th class="col-lg-4">Nombre</th>
                              <th class="col-lg-1">Presentación</th>
                              <th class="col-lg-1">Descripción</th>
                              <th class="col-lg-2">Prec. V</th>
                              <th class="col-lg-1">Stock</th>
                              <th class="col-lg-1">Cantidad</th>
                              <th class="col-lg-1">Subtotal</th>
                              <th class="col-lg-1">Acci&oacute;n</th>
                            </tr>
                          </thead>
                          <tbody id="pedidotable">

                          </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="7">Total<strong></strong></td>
                              <td id='totcant'>0</td>
                              <td id='total_dinero'>$0.00</td>
                              <td></td>
                            </tr>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                        <input type="hidden" name="autosave" id="autosave" value="false-0">
                      </section>
                      <input type="hidden" name="processo" id="processo" value="insert"><br>
                      <div>
                        <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
                        <input type='hidden' name='urlprocess' id='urlprocess' value="<?php echo $filename ?> ">
                        <input type="hidden" id="id_pedido" name="id_pedido" value="0" class="btn btn-primary m-t-n-xs" />

                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div><!--div class='ibox-content'-->
          </div>
        </div>
      </div>
    </div>

    <?php
    include_once ("footer.php");
    echo "<script src='js/funciones/funciones_pedido.js'></script>";
  } //permiso del script
  else {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
  }
  }

  function insertar()
  {
    $cuantos = $_POST['cuantos'];
    $datos = $_POST['datos'];
    $fecha_m = $_POST['fecha_m'];
    $fecha_e = $_POST['fecha_e'];
    $total_compras = $_POST['total'];
    $id_cliente=$_POST['id_cliente'];
    $lugar_entrega=$_POST['lugar_entrega'];
    $reservado=$_POST['reservado'];
    $hora=date("H:i:s");
    $fecha_movimiento = date("Y-m-d");
    $id_empleado=$_SESSION["id_usuario"];
    $id_sucursal = $_SESSION["id_sucursal"];
    //validando si ya existe el pedido
    $valido=_query("SELECT id_pedido FROM pedido WHERE id_cliente='$id_cliente' AND fecha='$fecha_m' AND fecha_entrega='$fecha_e' AND id_sucursal='$id_sucursal'");
    $valido_n=_num_rows($valido);
    if($valido_n==0){

    $sql_num = _query("SELECT pd FROM correlativo WHERE id_sucursal='$id_sucursal'");
    $datos_num = _fetch_array($sql_num);
    $ult = $datos_num["pd"]+1;
    $len_ult = strlen($ult);
    $cantidad_ceros = 7-$len_ult;
    $numero_doc=ceros_izquierda($cantidad_ceros,$ult).'_PD';

    _begin();
    $z=1;
    /*actualizar los correlativos de II*/
    $corr=1;
    $table="correlativo";
    $form_data = array(
      'pd' =>$ult
    );
    $where_clause_c="id_sucursal='".$id_sucursal."'";
    $up_corr=_update($table,$form_data,$where_clause_c);
    if ($up_corr) {
      # code...
    }
    else {
      $corr=0;
    }
    //Validamos si es resarvado para descontar del stock
    $reserva=false;
    if($reservado==1)
    {
      $reserva=true;
    }
    $table='pedido';
    $form_data = array(
      'id_cliente' => $id_cliente,
      'fecha' => $fecha_m,
      'numero' => $numero_doc,
      'total' => $total_compras,
      'id_empleado_proceso' => $id_empleado,
      'id_sucursal' => $id_sucursal,
      'estado' => 'PENDIENTE',
      'total' => $total_compras,
      'fecha_entrega' => $fecha_e,
      'lugar_entrega' => $lugar_entrega,
      'reservado' => $reservado,

    );
    $insert_mov =_insert($table,$form_data);
    $id_pedido=_insert_id();
    $lista=explode('#',$datos);
    $j = 1 ;
    $k = 1 ;
    $l = 1 ;
    $m = 1 ;
    $insert_detalle_p=0;
    for ($i=0;$i<$cuantos ;$i++)
    {
      list($id_producto,$precio_compra,$precio_venta,$cantidad,$sutto,$id_presentacion)=explode('|',$lista[$i]);
        $tablee='pedido_detalle';
        $form_data_detalle = array(
          'id_pedido' => $id_pedido,
          'id_producto' => $id_producto,
          'cantidad' => $cantidad,
          'id_presentacion' => $id_presentacion,
          'precio_venta' => $precio_venta,
            'subtotal'=>$sutto,
        );
        $insert_detalle_p = _insert($tablee,$form_data_detalle);
        /*if($reserva==true)
        {
            $get_stock=_query("SELECT stock FROM stock WHERE id_producto='$id_producto' AND id_sucursal='$id_sucursal'");
            $get_value=_fetch_array($get_stock);
            $value_stock=$get_value['stock'];
            $stock_opera=$value_stock-$cantidad;
            $table_stock='stock';
            $form_data_stock = array(
              'stock' => $stock_opera,
            );
            $where_clause_stock="id_producto='".$id_producto."' AND id_sucursal='".$id_sucursal."'";
            $updating_stock=_update($table_stock,$form_data_stock,$where_clause_stock);

        }*/
      }
      if(!$insert_detalle_p)
      {
        $l = 0;
      }
    if($insert_mov && $l)
    {
      _commit();
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Registro ingresado con exito!';
    }
    else
    {
      _rollback();
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Registro de no pudo ser ingresado!'._error();
    }
  }else {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Registro ya existe!'._error();
  }
    echo json_encode($xdatos);
  }
  function consultar_stock()
  {
    $id_producto = $_REQUEST['id_producto'];
    $id_sucursal=$_SESSION['id_sucursal'];

    $i=0;
    $unidadp=0;
    $preciop=0;
    $costop=0;
    $descripcionp=0;

    $sql_p=_query("SELECT presentacion.nombre, prp.descripcion,prp.id_presentacion,prp.unidad,prp.costo,prp.precio FROM presentacion_producto AS prp JOIN presentacion ON presentacion.id_presentacion=prp.presentacion WHERE prp.id_producto=$id_producto AND prp.activo=1
      AND prp.id_sucursal=$_SESSION[id_sucursal]");
    $select="<select class='sel ' >";
    while ($row=_fetch_array($sql_p))
    {
      if ($i==0)
      {
        $unidadp=$row['unidad'];
        $costop=$row['costo'];
        $preciop=$row['precio'];
        $descripcionp=$row['descripcion'];
      }
      $select.="<option value='".$row["id_presentacion"]."'>".$row["nombre"]." (".$row["unidad"].")</option>";
      $i=$i+1;
    }
    $select.="</select>";
    $xdatos['select']= $select;
    $xdatos['costop']= $costop;
    $xdatos['preciop']= $preciop;
    $xdatos['unidadp']= $unidadp;
    $xdatos['descripcionp']= $descripcionp;

    $sql_perece="SELECT * FROM producto WHERE id_producto='$id_producto'";
    $result_perece=_query($sql_perece);
    $row_perece=_fetch_array($result_perece);
    $nombrep=$row_perece['descripcion'];
    $xdatos['nombre'] = $nombrep;
    echo json_encode($xdatos);
  }
  function getpresentacion()
  {
    $id_presentacion =$_REQUEST['id_presentacion'];
    $sql=_fetch_array(_query("SELECT * FROM presentacion_producto WHERE id_presentacion=$id_presentacion"));
    $precio=$sql['precio'];
    $unidad=$sql['unidad'];
    $descripcion=$sql['descripcion'];
    $costo=$sql['costo'];
    $xdatos['precio']=$precio;
    $xdatos['costo']=$costo;
    $xdatos['unidad']=$unidad;
    $xdatos['descripcion']=$descripcion;
    echo json_encode($xdatos);
  }
  if (!isset($_REQUEST['process']))
  {
    initial();
  }
  if (isset($_REQUEST['process']))
  {
    switch ($_REQUEST['process'])
    {
      case 'insert':
      insertar();
      break;
      case 'consultar_stock':
      consultar_stock();
      break;
      case 'getpresentacion':
      getpresentacion();
      break;
    }
  }
  ?>
