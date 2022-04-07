<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aros extends CI_Controller
{
    public function index()
    {
        validar_session($this);
        $data = array(
            'tipo' => 5,
            'nombre_archivo' => 'Administrar Aros',
            'icono' => 'fa fa-circle',
            'urljs' => 'funciones_aros.js',
            'url_agregar' => 'Aros/agregar_aro',
            'txt_agregar' => 'Agregar Aro',
            'tabla'=> array(
                'CODIGO' => 2,
                'MARCA' => 2,
                'CASA'=>2,
                'EXISTENCIA' => 1,
                'SUCURSAL' => 2,
                'ACCIONES'=>1,
            ),
        );
        $this->load->helper('template_helper');
        template('template/admin', $data);
    }

    public function get_data()
    {
        $this->load->model('Aro_model');

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
            0 => 'aro.codigo',
            1 => 'aro.marca',
            2 => 'aro.casa',
            3 => 'aro.existencia',
        );
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }
        $id_sucursal=$this->session->id_sucursal;
        $aros = $this->Aro_model->get_aros($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);

        if ($aros != 0) {
            $data = array();
            $num = 1;
            foreach ($aros as $rows) {
                $menudrop = "<div class='btn-group'>
                <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
                <ul class='dropdown-menu dropdown-primary'>";

                $filename = base_url() . "Aros/editar_aro";
                $menudrop .= "<li><a href='" . $filename . "/" .md5($rows->id). "' role='button'  data-refresh='true'><i class='fa fa-pencil' ></i> Editar</a></li>";
                $filename = base_url() . "Aros/ingresar_aro";
                $menudrop .= "<li><a href='" . $filename . "/" .md5($rows->id). "' role='button'  data-refresh='true'><i class='fa fa-shopping-cart' ></i> Ingreso</a></li>";
                $filename = base_url() . "Aros/detalle_aro";
                $menudrop .= "<li><a href='" . $filename . "/" .md5($rows->id). "' role='button'  data-refresh='true'><i class='fa fa-list-alt' ></i> Detalle</a></li>";
                $filename = base_url() . "Aros/borrar_aro";
                $menudrop .= "<li><a class='elim' id='".$rows->id."'><i class='fa fa-trash' ></i> Eliminar</a></li>";

                $menudrop .= "</ul></div>";

                $data[] = array(
                    $rows->codigo,
                    $rows->marca,
                    $rows->casa,
                    $rows->existencia,
                    $rows->nom_su,
                    $menudrop,
                );

                $num++;
            }
            $total = $this->Aro_model->count_aros($id_sucursal);
            $output = array(
                "draw" => $draw,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $data
            );
        } else {
            $data[] = array(
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

    public function agregar_aro()
    {
        $this->load->helper('template_helper');
        template('aro/agregar_aro');
    }
    public function guardar_aro()
    {
        $this->load->model('Aro_model');
        $this->load->model('Utils_model');
        $codigo = $this->input->post("codigo");
        $marca = $this->input->post("marca");
        $casa = $this->input->post("casa");
        $existencia = $this->input->post("existencia");
        $id = $this->input->post("id_aro");
        $id_sucursal=$this->session->id_sucursal;
        $existe = $this->Aro_model->existe_aro($codigo, $id,$id_sucursal);
       // echo $existe;

            $table = "aro";
            if ($id != 0) {
                $id = md5($id);
                $form_data = array(
                'codigo' => $codigo,
                'marca' => $marca,
                'casa' => $casa,
                'existencia' => $existencia,
              );
                $where = "md5(id)='$id'";
                $insertar = $this->Utils_model->_update($table, $form_data, $where);

            } else {
              $form_data = array(
                'codigo' => $codigo,
                'marca' => $marca,
                'casa' => $casa,
                'existencia' => $existencia,
                'sucursal' => $id_sucursal,
              );
              if (!$existe) {
						    $insertar = $this->Utils_model->_insert($table, $form_data);
            } else {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Este aro ya fue ingresado!!!';
            }
            }
            if ($insertar) {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']="Datos guardados con exito!!!";
            } else {
                if (!$existe) {
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']="Datos no pudieron ser guardados!!!";
                } else {
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='Este aro ya fue ingresado!!!';
                }

            }



        $xdatos['url'] = base_url("Aros");

        echo json_encode($xdatos);
    }

    public function editar_aro($id)
    {
        $this->load->helper('template_helper');
        $this->load->model('Aro_model');
        $aros = $this->Aro_model->get_aro($id);
        template('aro/editar_aro', $aros);
      //  print_r($aros);
    }
    public function detalle_aro($id)
    {
        $this->load->helper('template_helper');
        $this->load->model('Aro_model');
        $id_sucursal=$this->session->id_sucursal;
        $aros = $this->Aro_model->get_aro($id);
        $codigo=$aros->codigo;
        $marca=$aros->marca;
        $casa=$aros->casa;
        $id1=$aros->id;
        $data = $this->Aro_model->get_existencia_aro($codigo);
        //echo json_encode($data);
        $form_data = array(
            'data' => $data,
            'codigo' => $codigo,
            'marca' => $marca,
            'casa' => $casa,
            'id' => $id1,
            'id_sucursal' => $id_sucursal,
        );
        template('aro/solicitar_aro', $form_data);
    }
    public function ingresar_aro($id)
    {
        $this->load->helper('template_helper');
        $this->load->model('Aro_model');
        $aros = $this->Aro_model->get_aro($id);
        template('aro/ingresar_aro', $aros);
    }
    public function ingresar_aros()
    {
        $this->load->model('Utils_model');
        $this->load->helper('template_helper');
        $this->load->model('Aro_model');
				$id = $this->input->post("id_aro");
				$id = md5($id);
        $existencia= $this->input->post("existencia");
        $aros = $this->Aro_model->get_aro($id);
				$existenciadb = $aros->existencia;
        $nuevaex = $existencia + $existenciadb;
				$table = "aro";
				$form_data_ar = array(
				    'existencia' => $nuevaex,
				);
				if ($id != "") {
						$where = " md5(id)='$id'";
						$insertar = $this->Utils_model->_update($table, $form_data_ar, $where);

				} else {
						$insertar = $this->Utils_model->_insert($table, $form_data_ar);
				}
				if ($insertar) {
						$xdatos['typeinfo']='Success';
						$xdatos['msg']="Datos guardados con exito!!!";
				} else {
						$xdatos['typeinfo']='Error';
						$xdatos['msg']="Datos no pudieron ser guardados!!!";
				}
				$xdatos['url'] = base_url("Aros");

				echo json_encode($xdatos);
    }

     function  aro_salida($codigo=-1) {
        $this->load->helper('template_helper');
        $this->load->model('Aro_model');
        $data=array(
         "codigo"=>$codigo,
        );
       $this->load->view('aro/salida',$data);
        //template('aro/salida', $data);
       // template('aro/salida');
    }
    public function ingresar_solicitud()
    {
        $this->load->model('Utils_model');
        $this->load->helper('template_helper');
        $this->load->model('Aro_model');
        $fecha_registro=date("Y-m-d");
        $hora_registro=date("H:i:s");
        $sucursal_solicita = $this->input->post("id_sur");
        $codigo= $this->input->post("codigo");
        $id_sucursal=$this->session->id_sucursal;
        $id_usuario=$this->session->id_usuario;
        $table = "solicitudes";
        $form_data = array(
                'sucursal_solicita' => $sucursal_solicita,
                'codigo' => $codigo,
                'id_sucursal' => $id_sucursal,
                'id_usuario' => $id_usuario,
                'fecha' => $fecha_registro,
                'hora' => $hora_registro,
        );
        $insertar = $this->Utils_model->_insert($table, $form_data);

        if ($insertar) {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']="Solicitud realizada con exito!!!";
        } else {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']="Solicitud no pudo ser guardados!!!";
        }
        $xdatos['url'] = base_url("Aros");

        echo json_encode($xdatos);
    }
    public function ingresar_salida_aro()
    {
        $this->load->model('Utils_model');
        $this->load->helper('template_helper');
        $this->load->model('Aro_model');
        $fecha_registro=date("Y-m-d");
        $hora_registro=date("H:i:s");
        $codigo= $this->input->post("codigo");
        $cantidad= $this->input->post("cantidad");
        $motivo= $this->input->post("motivo");
        $id_sucursal=$this->session->id_sucursal;
        $id_usuario=$this->session->id_usuario;

        $existe = $this->Aro_model->get_existe_aro($codigo,$id_sucursal);
    //    echo json_encode($existe);
        $existenciadb = $existe->existencia;
        $id_aro = $existe->id;
        $nuevaex = $existenciadb-$cantidad;
        $table_up = "aro";
        $form_data_up = array(
            'existencia' => $nuevaex,
        );
        $table = "movimientos";
        $form_data = array(
                'codigo' => $codigo,
                'motivo' => $motivo,
                'cantidad' => $cantidad,
                'tipo' => "SALIDA",
                'id_sucursal' => $id_sucursal,
                'id_usuario' => $id_usuario,
                'fecha' => $fecha_registro,
                'hora' => $hora_registro,
        );
        if ($nuevaex>=0){
            $insertar = $this->Utils_model->_insert($table, $form_data);

            if ($insertar) {
                $where = "id='$id_aro'";
                $this->Utils_model->_update($table_up, $form_data_up, $where);
                $xdatos['typeinfo']='Success';
                $xdatos['msg']="Salida realizada con exito!!!";
            } else {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']="Salida no pudo ser registrada!!!";
            }
        }else{
            $xdatos['typeinfo']='Error';
            $xdatos['msg']="No hay suficientes!!!";
        }

        $xdatos['url'] = base_url("Aros");

        echo json_encode($xdatos);
    }
    public function borrar_aro()
    {
				$id = $this->input->post("id");
        $this->load->model('Utils_model');
				$table = "aro";
				$where = "id='".$id."'";
				if($this->Utils_model->_delete($table, $where))
				{
					$xdata["typeinfo"] = 'Success';
					$xdata["msg"] = 'Aro borrado con exito!!!';
				}
				else
				{
					$xdata["typeinfo"] = 'Error';
					$xdata["msg"] = 'Aro no pudo ser borrado!!!';
				}
				echo json_encode($xdata);
    }
}

/* End of file Aros.php */
