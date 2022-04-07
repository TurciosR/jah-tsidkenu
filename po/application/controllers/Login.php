<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function index()
    {
        $this->load->view('login');
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('Login', 'refresh');
    }
    public function iniciar_sesion()
    {
        $this->load->model('Usuario_model');
        $this->load->model('Utils_model');
        $correo = $this->input->post("correo");
        $clave = $this->input->post("clave");

        if ($this->Usuario_model->existe_usuario($correo)) {
            if ($this->Usuario_model->usuario_login($correo, $clave)) {
                $datos_usuario = $this->Usuario_model->usuario_login($correo, $clave);
                $nombre = $datos_usuario->nombre;
                $id_usuario = $datos_usuario->id_usuario;
                $id_sucursal = $datos_usuario->id_sucursal;
                $admin = $datos_usuario->admin;
                $tipo = $datos_usuario->nivel;

                    $xdatos["title"] = "Información";
                    $xdatos["typeinfo"] = "success";
                    $xdatos["msg"] = "Bienvenido ".$nombre;

                    $user_session = array(
                            'usuario'  => $nombre,
                            'admin'  => $admin,
                            'id_usuario'  => $id_usuario,
                            'tipo'  => $tipo,
                            'id_sucursal'  => $id_sucursal,
                            'logged_in' => true
                        );
                $this->session->sess_expiration = 43200;
                $this->session->set_userdata($user_session);
            } else {
                $xdatos["typeinfo"] = "error";
                $xdatos["title"] = "Error";
                $xdatos["msg"] = "La clave proporcionada es incorrecta";
            }
        } else {
            $xdatos["typeinfo"] = "error";
            $xdatos["title"] = "Error";
            $xdatos["msg"] = "El usuario ingresado no existe";
        }
        echo json_encode($xdatos);
    }
}
