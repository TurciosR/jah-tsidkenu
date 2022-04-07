<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{
    public function index()
    {
        validar_session($this);
        $data = array(
            'tipo' => 5,
            'nombre_archivo' => 'Administrar Usuarios',
            'icono' => 'fa fa-user-circle',
            'urljs' => 'funciones_usuarios.js',
            'url_agregar' => 'Usuarios/agregar_usuario',
            'txt_agregar' => 'Agregar Usuario',
            'tabla'=> array(
                '#' => 1,
                'NOMBRE' => 4,
                'USUARIO' => 4,
                'TIPO'=>2,
                'ACCIONES'=>1,
            ),
        );
        $this->load->helper('template_helper');
        template('template/admin', $data);
    }

    public function get_data()
    {
        $this->load->model('Usuario_model');

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
            0 => 'nombre',
            1 => 'usuario',
        );
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }
        $id_sucursal=$this->session->id_sucursal;
        $usuarios = $this->Usuario_model->get_usuarios($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal);

        if ($usuarios != 0) {
            $data = array();
            $num = 1;
            foreach ($usuarios as $rows) {
                $menudrop = "<div class='btn-group'>
                <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
                <ul class='dropdown-menu dropdown-primary'>";

                $filename = base_url() . "Usuarios/editar_usuario";
                $menudrop .= "<li><a href='" . $filename . "/" .md5($rows->id_usuario). "' role='button'  data-refresh='true'><i class='fa fa-pencil' ></i> Editar</a></li>";
                $filename = base_url() . "Usuarios/borrar_usuario";
                $menudrop .= "<li><a id='".$rows->id_usuario. "' class='elim'><i class='fa fa-trash' ></i> Eliminar</a></li>";
                $filename = base_url() . "Usuarios/permiso_usuario";
                //$menudrop .= "<li><a href='" . $filename . "/" .md5($rows->id_usuario). "' role='button'  data-refresh='true'><i class='fa fa-lock' ></i> Permisos</a></li>";

                $menudrop .= "</ul></div>";

                $data[] = array(
                    $num,
                    $rows->nombre,
                    $rows->usuario,
                    $rows->admin == 1 ? "Administrador" : "Usuario",
                    $menudrop,
                );

                $num++;
            }
            $total = $this->Usuario_model->count_usuarios($id_sucursal);
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

    public function agregar_usuario()
    {
        $this->load->helper('template_helper');
        template('usuario/agregar_usuario');
    }
    public function guardar_usuario()
    {
        $this->load->model('Usuario_model');
        $this->load->model('Utils_model');
        $nombre = $this->input->post("nombre");
        $usuario = $this->input->post("usuario");
        $clave = $this->input->post("clave");
        $tipo2 = $this->input->post("tipo2");
        $id_usuario = $this->input->post("id_usuario");
        $id_sucursal=$this->session->id_sucursal;
        $existe = $this->Usuario_model->existe_usuario($usuario, $id_usuario);
        if (!$existe) {
						$id_usuario = md5($id_usuario);
            $table = "usuario";
            if ($id_usuario != 0) {
                $datos_usuarios = $this->Usuario_model->get_usuario($id_usuario);
                $passwd = $datos_usuarios->password;
                if ($passwd != $clave) {
                    $clave = md5($clave);
                }
                $form_data = array(
                    'nombre' => $nombre,
                    'usuario' => $usuario,
                    'password' => $clave,
                    'tipo' => $tipo2,
                );
                $where = "md5(id_usuario)='$id_usuario'";
                $insertar = $this->Utils_model->_update($table, $form_data, $where);
            } else {
								$clave = md5($clave);
                $form_data = array(
                    'nombre' => $nombre,
                    'usuario' => $usuario,
                    'password' => $clave,
                    'tipo' => $tipo2,
                    'id_sucursal' => $id_sucursal,
                );
                $insertar = $this->Utils_model->_insert($table, $form_data);
            }
            if ($insertar) {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']="Datos guardados con exito!!!";
            } else {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']="Datos no pudieron ser guardados!!!";
            }
        } else {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Este usuario ya fue ingresado!!!';
        }


        $xdatos['url'] = base_url("Usuarios");

        echo json_encode($xdatos);
    }

    public function editar_usuario($id)
    {
        $this->load->helper('template_helper');
        $this->load->model('Usuario_model');
        $usuarios = $this->Usuario_model->get_usuario($id);
        template('usuario/editar_usuario', $usuarios);
    }
    public function borrar_usuario()
    {
				$id = $this->input->post("id");
        $this->load->model('Utils_model');
				$table = "usuario";
				$where = "id_usuario='".$id."'";
				if($this->Utils_model->_delete($table, $where))
				{
					$xdata["typeinfo"] = 'Success';
					$xdata["msg"] = 'Usuario borrado con exito!!!';
				}
				else
				{
					$xdata["typeinfo"] = 'Error';
					$xdata["msg"] = 'Usuario no pudo ser borrado!!!';
				}
				echo json_encode($xdata);
    }
}

/* End of file Usuarios.php */
