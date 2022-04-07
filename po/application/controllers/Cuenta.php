    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Cuenta extends CI_Controller {

        public function index(){
            validar_session($this);
            $data = array(
                'tipo' => 5,
                'nombre_archivo' => 'Administrar Cuentas ',
                'icono' => 'fa fa-id-card',
                'urljs' => 'funciones_cuenta_cliente.js?a='.rand(0,999),
                'url_agregar' => base_url().'Cuenta/agregar_cuenta',
                'txt_agregar' => 'Nueva Cuenta',
                'tabla'=> array(
                    'ID' => 1,
                    'EMPRESA'=>3,
                    'CLIENTE'=>4,
                    'MONTO'=>2,
                    'SALDO'=>2,
                    'ABONO' => 1,
                    'FECHA' => 2,
                    'ACCIONES'=>1
                ),
            );
            $this->load->helper('template_helper');
            template('template/admin',$data);
        }

        public function get_cuentas(){
          $id = $this->uri->segment(3);
          //echo $id."###";
            validar_session($this);
            $data = array(
                'tipo' => 5,
                'nombre_archivo' => 'Administrar Cuentas ',
                'icono' => 'fa fa-id-card',
                'urljs' => 'funciones_cuenta.js?a='.rand(0,999),
                'url_agregar' => base_url().'Cuenta/agregar_cuenta',
                'txt_agregar' => 'Nueva Cuenta',
                'cent_cuenta_cob' => $id,
                'tabla'=> array(
                    'ID' => 1,
                    'EMPRESA'=>3,
                    'CLIENTE'=>4,
                    'MONTO'=>2,
                    'SALDO'=>2,
                    'ABONO' => 1,
                    'FECHA' => 2,
                    'ACCIONES'=>1
                ),
            );
            $this->load->helper('template_helper');
            template('template/admin',$data);
        }
        public function get_data()
        {
            $id = $this->uri->segment(3);
          //echo $id."###";
            $this->load->model('Cuenta_model');
            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));

            $order = $this->input->post("order");
            $search = $this->input->post("search");
            $search = $search['value'];
            $col = 0;
            $dir = "";
            if (!empty($order)) {
                foreach ($order as $o) {
                    $col = $o['column'];
                    $dir = $o['dir'];
                }
            }

            if ($dir != "asc" && $dir != "desc") {
                $dir = "desc";
            }
            $valid_columns = array(
                0 => 'id',
                1 => 'empresa',
                2 => 'nombre',
            );
            if (!isset($valid_columns[$col])) {
                $order = null;
            } else {
                $order = $valid_columns[$col];
            }
            $id_sucursal=$this->session->id_sucursal;
            $cuentas = $this->Cuenta_model->get_cuenta($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal, $id);
            if ($cuentas != 0) {
                $data = array();
                $num = 1;
                foreach ($cuentas as $rows) {

                    $menudrop = "<div class='btn-group'>
                    <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
                    <ul class='dropdown-menu dropdown-primary'>";

                    $filename = base_url() . "Cuenta/ver_detalle_cuenta";
                    $menudrop .= "<li><a href='" . $filename . "/" .($rows->id_cuenta). "' role='button' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye' > </i> Ver detalle </a></li>";
                    $filename = base_url() . "Cuenta/detalle_cuenta_abono";
                    $menudrop .= "<li><a href='" . $filename . "/" .($rows->id_cuenta). "' role='button' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-money' > </i> Abonar </a></li>";
                    $menudrop .= "</ul></div>";

                    $data[] = array(
                        $num,
                        $rows->empresa,
                        $rows->nombre,
                        $rows->monto,
                        $rows->saldo,
                        $rows->abono,
                        $rows->fecha,
                        $menudrop,
                    );

                    $num++;
                }
                $total = $this->Cuenta_model->count_cuenta($id_sucursal, $id);
                $output = array(
                    "draw" => $draw,
                    "recordsTotal" => $total,
                    "recordsFiltered" => $total,
                    "data" => $data
                );
            } else {
                $data[] = array(
                    "",
                    "",
                    "",
                    "No se encontraron registros",
                    "",
                    "",
                    "",
                    "",
                );
                $output = array(
                    "draw" => 0,
                    "recordsTotal" => 0,
                    "recordsFiltered" => 0,
                    "data" => $data
                );
            }
            echo json_encode($output);
            exit();
        }

        public function get_data_clientes()
        {
            $this->load->model('Cuenta_model');
            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));

            $order = $this->input->post("order");
            $search = $this->input->post("search");
            $search = $search['value'];
            $col = 0;
            $dir = "";
            if (!empty($order)) {
                foreach ($order as $o) {
                    $col = $o['column'];
                    $dir = $o['dir'];
                }
            }

            if ($dir != "asc" && $dir != "desc") {
                $dir = "desc";
            }
            $valid_columns = array(
                0 => 'id',
                1 => 'empresa',
                2 => 'nombre',
            );
            if (!isset($valid_columns[$col])) {
                $order = null;
            } else {
                $order = $valid_columns[$col];
            }
            $id_sucursal=$this->session->id_sucursal;
            $cuentas = $this->Cuenta_model->get_cuenta_clientes($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);
            if ($cuentas != 0) {
                $data = array();
                $num = 1;
                foreach ($cuentas as $rows) {

                    $menudrop = "<div class='btn-group'>
                    <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
                    <ul class='dropdown-menu dropdown-primary'>";

                    $filename = base_url() . "Cuenta/get_cuentas";
                    $menudrop .= "<li><a href='" . $filename . "/" .($rows->id_cliente). "'><i class='fa fa-eye' > </i> Ver cuentas </a></li>";
                    //$filename = base_url() . "Cuenta/detalle_cuenta_abono";
                    //$menudrop .= "<li><a href='" . $filename . "/" .($rows->id_cliente). "' role='button' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-money' > </i> Abonar </a></li>";
                    $menudrop .= "</ul></div>";

                    $data[] = array(
                        $num,
                        $rows->empresa,
                        $rows->nombre,
                        $rows->monto,
                        $rows->saldo,
                        $rows->abono,
                        $rows->fecha,
                        $menudrop,
                    );

                    $num++;
                }
                $total = $this->Cuenta_model->count_cuenta_clientes($id_sucursal);
                $output = array(
                    "draw" => $draw,
                    "recordsTotal" => $total,
                    "recordsFiltered" => $total,
                    "data" => $data
                );
            } else {
                $data[] = array(
                    "",
                    "",
                    "",
                    "No se encontraron registros",
                    "",
                    "",
                    "",
                    "",
                );
                $output = array(
                    "draw" => 0,
                    "recordsTotal" => 0,
                    "recordsFiltered" => 0,
                    "data" => $data
                );
            }
            echo json_encode($output);
            exit();
        }

        function  agregar_cuenta()
        {
            $this->load->model('Cuenta_model');
            $id_sucursal=$this->session->id_sucursal;
            $this->load->helper('template_helper');
            template('cuenta/cuentas_por_cobrar');
        }

        function actdate()
        {
          if($this->input->method(TRUE) == "POST"){
            $id_cuenta=$this->input->post("id_cuenta");
            $fecha=$this->input->post("fecha");
            $empresa=$this->input->post("empresa");

            $fecha = Y_m_d($fecha);
            $this->db->set('fecha', $fecha);
            $this->db->set('empresa', $empresa);
            $this->db->where('id_cuenta', $id_cuenta);
            $this->db->update('cuenta');

            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Datos ingresadas correctamente!';
            echo json_encode($xdatos);
          }
        }
        function guardar_cuenta()
        {
            $this->load->model('Cuenta_model');
            date_default_timezone_set('America/El_Salvador');
            $empresa=$this->input->post("empresa");
            $fecha_actual=$this->input->post("fecha");
            if($fecha_actual=="")
            {
              $fecha_actual=date("Y-m-d");
            }
            else
            {
              $fecha_actual = Y_m_d($fecha_actual);
            }
            //$hora_actual=date("H:i:s");
            $id_sucursal=$this->session->id_sucursal;
            $id_usuario=$this->session->id_usuario;
            $id_cliente = $this->input->post("id_cliente");//
            $total = $this->input->post("total");
            $abono = $this->input->post("abono");//
            $numero_doc = $this->input->post("numero_doc");//
            $datos = json_decode($this->input->post("datos"), true);
            $saldo=0;
            if ($abono!=''){
                $saldo=$total-$abono;
            }
                $tabla= "cuenta";
                $form_data = array(
                    'id_cliente' => $id_cliente,
                    'monto' => $total,
                    'saldo' => $saldo,
                    'abono' => $abono,
                    'id_usuario' => $id_usuario,
                    'fecha' => $fecha_actual,
                    'id_sucursal' => $id_sucursal,
                    'empresa' => $empresa,
                );
                $valor=	array(
                    'id_cliente' => $id_cliente,
                    'monto' => $total,
                    'id_usuario' => $id_usuario,
                    'fecha' => $fecha_actual,
                    'id_sucursal' => $id_sucursal,
                );
                $total = $this->Cuenta_model->existe_cuenta($valor);
                if ($total==0) {
                    $this->Cuenta_model->begin();
                    $insert = $this->Cuenta_model->save($tabla, $form_data);
                    if($insert)
                    {
                        $id_cuenta =$this->Cuenta_model-> _insert_id();
                        if ($abono!=''){
                            $table_abono = "abono";
                            $form_abono = array(
                                'id_cuenta' => $id_cuenta,
                                'abono' => $abono,
                                'numero_doc' => $numero_doc,
                                'fecha' => $fecha_actual,
                            );
                            $insert_abo = $this->Cuenta_model->save($table_abono, $form_abono);
                        }
                        $error = 0;
                        $table_fd = "cuenta_detalle";
                        $num_datos=count($datos);
                        $e=0;
                        foreach ($datos as $data)
                        {
                            $cantidad = $data["cant"];
                            $descripcion = $data["desc"];
                            $precio = $data["prec"];
                            $subtotal = $data["subt"];
                            $form_fd = array(
                                'id_cuenta' => $id_cuenta,
                                'cantidad' => $cantidad,
                                'precio' => $precio,
                                'subtotal' => $subtotal,
                                'detalle' => $descripcion,

                            );
                            $ins = $this->Cuenta_model->save($table_fd, $form_fd);
                            if($ins)
                            {
                                $e++;
                            }
                        }
                        if($num_datos==$e)
                        {
                            $this->Cuenta_model->commit();
                            $xdatos['typeinfo']='Success';
                            $xdatos['msg']='Datos ingresadas correctamente!';
                        }
                        else
                        {
                            $this->Cuenta_model->rollback();
                            $xdatos['typeinfo']='Error';
                            $xdatos['msg']='Error al guardar los detalles!';
                        }
                    }
                    else
                    {
                        $this->Cuenta_model->rollback();
                        $xdatos['typeinfo']='Error';
                        $xdatos['msg']='Error al guardar los los datos de la Cuenta! ';
                    }
                }else {
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='Esta Cuenta ya existe!';
                }

            $xdatos['url'] = base_url("Cuenta");

            echo json_encode($xdatos);
        }
        function guardar_abono()
        {
            $this->load->model('Cuenta_model');
            $this->load->model('Utils_model');
            date_default_timezone_set('America/El_Salvador');

            $fecha_actual=$this->input->post("fecha");
            if($fecha_actual=="")
            {
              $fecha_actual=date("Y-m-d");
            }
            else
            {
              $fecha_actual = Y_m_d($fecha_actual);
            }

            $id_cuenta = $this->input->post("id_cuenta");//
            $abono = $this->input->post("abono");//
            $abonado_ante = $this->input->post("abonado");//
            $numero_doc = $this->input->post("numero_doc");//
            $saldo_ante=$this->input->post("saldo");
            $saldo=$saldo_ante-$abono;
            $abonado=$abonado_ante+$abono;
            $this->Cuenta_model->begin();
            $table_abono = "abono";
            $form_abono = array(
                'id_cuenta' => $id_cuenta,
                'abono' => $abono,
                'numero_doc' => $numero_doc,
                'fecha' => $fecha_actual,
            );
            $insert_abo = $this->Cuenta_model->save($table_abono, $form_abono);

            if ($insert_abo) {
                $tabla= "cuenta";
                if ($saldo==0){
                    $form_data = array(
                        'saldo' => $saldo,
                        'abono' => $abonado,
                        'estado' => 1,
                    );
                }else{
                    $form_data = array(
                        'saldo' => $saldo,
                        'abono' => $abonado,
                    );
                }
                $where =" id_cuenta='$id_cuenta'";
                $update=$this->Utils_model->_update($tabla,$form_data,$where);
                if ($update) {
                    $this->Cuenta_model->commit();
                    $xdatos['typeinfo'] = 'Success';
                    $xdatos['msg'] = 'Abono realizado con exito!';
                    $xdatos['fecha'] = d_m_Y($fecha_actual);
                    $xdatos['saldo'] = $saldo;
                    $xdatos['abono'] = $abono;
                    $xdatos['abonado'] = $abonado;

                } else {
                $this->Cuenta_model->rollback();
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Esta Cuenta ya existe!';
            }
            }else{
                $this->Cuenta_model->rollback();
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Error al guardar el abono!';
            }

            $xdatos['url'] = base_url("Cuenta");

            echo json_encode($xdatos);
        }
        function fetch($query)
        {
            $id_sucursal=$this->session->id_sucursal;
            $this->load->model('Cuenta_model');
            echo $this->Cuenta_model->traer_cliente($query,$id_sucursal);
        }

        function  ver_detalle_cuenta($id_cuenta) {
            $this->load->helper('template_helper');
            $this->load->model('Cuenta_model');
            $cuenta = $this->Cuenta_model->get_cuenta_modal($id_cuenta);
            $id_cuenta1=$cuenta->id_cuenta;
            $nombre=$cuenta->nombre;
            $fecha=$cuenta->fecha;
            $saldo=$cuenta->saldo;
            $abono=$cuenta->abono;
            $monto=$cuenta->monto;
            $empresa=$cuenta->empresa;
            $cuenta_detalle = $this->Cuenta_model->get_cuenta_detalle_modal($id_cuenta);

            $cuenta_detalle2 = $this->Cuenta_model->get_cuenta_detalle_abono($id_cuenta);

            $data = array(
                'nombre' => $nombre,
                'fecha' => $fecha,
                'saldo' => $saldo,
                'abono' => $abono,
                'monto' => $monto,
                'empresa' => $empresa,
                'cuenta_detalle' => $cuenta_detalle,
                'cuenta_detalle2' => $cuenta_detalle2,
                'id_cuenta' => $id_cuenta1,
            );
            $this->load->view('cuenta/detalle_cuenta',$data);
        }

        function  detalle_cuenta_abono($id_cuenta) {
            $this->load->helper('template_helper');
            $this->load->model('Cuenta_model');
            $cuenta = $this->Cuenta_model->get_cuenta_modal($id_cuenta);
            $id_cuenta1=$cuenta->id_cuenta;
            $nombre=$cuenta->nombre;
            $fecha=$cuenta->fecha;
            $saldo=$cuenta->saldo;
            $abono=$cuenta->abono;
            $monto=$cuenta->monto;
            $estado=$cuenta->estado;
            $cuenta_detalle = $this->Cuenta_model->get_cuenta_detalle_abono($id_cuenta);
            $data = array(
                'nombre' => $nombre,
                'fecha' => $fecha,
                'saldo' => $saldo,
                'abono' => $abono,
                'monto' => $monto,
                'estado' => $estado,
                'cuenta_detalle' => $cuenta_detalle,
                'id_cuenta' => $id_cuenta1,
            );
            $this->load->view('cuenta/abono',$data);
        }

        function imprimir_fact() {
            $this->load->helper('facturacion_imprimir_helper');
            $this->load->library('user_agent');
            $this->load->model('Factura_model');
            $id_sucursal=$this->session->id_sucursal;

            $tipo_impresion = $this->input->post("tipo_impresion");
            $id_factura = $this->input->post("id_factura");
            if ($tipo_impresion=='COF') {
                $tipo_entrada_salida="FACTURA CONSUMIDOR";
            }
            if ($tipo_impresion=='ABONO') {
                $tipo_entrada_salida="ABONO";
            }
            if ($tipo_impresion=='CCF') {
                $tipo_entrada_salida="CREDITO FISCAL";
            }
            //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
            $info = $this->agent->platform();
            if (strpos($info, 'Windows') == true) {
                $so_cliente='win';
            } else {
                $so_cliente='lin';
            }


            $headers="";
            $footers="";
        if ($tipo_impresion=='COF') {
            $info_facturas=print_fact($id_factura, $tipo_impresion);
        }

        if ($tipo_impresion=='CCF') {
            $info_facturas=print_ccf($id_factura, $tipo_impresion);
        }
        if ($tipo_impresion=='ABONO') {
            $info_facturas=print_abono($id_factura, $tipo_impresion);
        }
        //directorio de script impresion cliente


        $rows1 = $this->Factura_model->get_config_dir($id_sucursal);
        $dir_print=$rows1->dir_print_script;
        $shared_printer_win=$rows1->shared_printer_matrix;
        $shared_printer_pos = $rows1->shared_printer_pos;
        $nreg_encode['shared_printer_win'] =$shared_printer_win;
        $nreg_encode['shared_printer_pos'] =$shared_printer_pos;
        $nreg_encode['dir_print'] =$dir_print;
        $nreg_encode['facturar'] =$info_facturas;
        $nreg_encode['sist_ope'] =$so_cliente;
        $nreg_encode['headers'] =$headers;
        $nreg_encode['footers'] =$footers;
        $nreg_encode['tipo_impresion'] =$tipo_impresion;


        echo json_encode($nreg_encode);
        }

    }


    /* End of file Usuarios.php */
