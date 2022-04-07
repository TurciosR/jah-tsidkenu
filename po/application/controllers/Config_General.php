<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config_General extends CI_Controller
{

	public function index()
	{
		validar_session($this);
		$this->load->model('Config_model');
		$id_sucursal=$this->session->id_sucursal;
		$rows = $this->Config_model->get_data($id_sucursal);
		$nombre_empresa = $rows->nombre;
		$direccion_empresa = $rows->direccion;
		$telefono_empresa = $rows->telefono;
		$iva = $rows->iva;
		$retencion = $rows->retencion;
		$logo_empresa = $rows->logo;
		$data = array(
			'titulo' => "Configuración General",
			'urljs' => 'funciones_general.js',
			'nombre_empresa' => $nombre_empresa,
			'direccion_empresa' => $direccion_empresa,
			'telefono_empresa' => $telefono_empresa,
			'iva' => $iva,
			'retencion' => $retencion,
			'logo_empresa' => base_url()."assets/".$logo_empresa,
		);
		$this->load->helper('template_helper');
		template('config',$data);
	}

	public function cambios(){
		$this->load->model('Utils_model');
		$this->load->helper('Utilities_helper');
		$nombre = $_POST["nombre"];
		$direccion = $_POST["direccion"];
		$telefono = $_POST["telefono"];
		$iva = $_POST["iva"]/100;
		$retencion = $_POST["retencion"]/100;

		$table = "sucursal";
		$form_data = array(
			"nombre"=>$nombre,
			"direccion"=>$direccion,
			"telefono"=>$telefono,
			"iva"=>$iva,
			"retencion"=>$retencion,
		);
		$where = "id=1";
		$insertar = $this->Utils_model->_update($table,$form_data,$where);

		if($insertar){
			$xdatos["type"]="success";
			$xdatos["msg"]="Datos Actualizados";
		}else
		{
			$xdatos["type"]="error";
			$xdatos["msg"]="Error al actualizar los datos";
		}
		/*if ($_FILES["fileinput"]["name"] != "") {

			$_FILES['file']['name'] = $_FILES['fileinput']['name'];
			$_FILES['file']['type'] = $_FILES['fileinput']['type'];
			$_FILES['file']['tmp_name'] = $_FILES['fileinput']['tmp_name'];
			$_FILES['file']['error'] = $_FILES['fileinput']['error'];
			$_FILES['file']['size'] = $_FILES['fileinput']['size'];

			$config['upload_path'] = "./assets/img/";
			$config['allowed_types'] = 'jpg|jpeg|png|bmp';

			$info = new SplFileInfo( $_FILES['fileinput']['name']);
			$name = uniqid(date("dmYHi")).".".$info->getExtension();
			$config['file_name'] = $name;
			$this->upload->initialize($config);


			$this->load->library('upload', $config);

			if ($this->upload->do_upload('file')){
				$url = 'img/'.$name;
				$table = "configuracion";
				$form_data = array(
					"nombre_empresa"=>$nombre,
					"direccion_empresa"=>$direccion,
					"telefono_empresa"=>$telefono,
					"correo_empresa"=>$correo,
					"web_empresa"=>$web,
					"logo_empresa"=>$url,
				);
				$where = "id_configuracion=1";
				$insertar = $this->Utils_model->_update($table,$form_data,$where);
				$uploadData = $this->upload->data();
				$filename = $uploadData['file_name'];
				if($insertar){
					$xdatos["type"]="success";
					$xdatos["msg"]="Datos Actualizados";
				}else
				{
					$xdatos["type"]="error";
					$xdatos["msg"]="Error al actualizar los datos";
				}
			}else{
				$xdatos["type"]="error";
				$xdatos["msg"]="Error al actualizar los datos";
			}
		}else{

		}*/
		$xdatos["url"]=base_url();
		echo json_encode($xdatos);
	}

}
