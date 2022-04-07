<?php

if (!function_exists('template')) {
	function template($view, $view_data = array()) {
        $ci =& get_instance();
        $ci->load->model('Menu_model');
        $ci->load->helper("security");
        $tipo = $ci->session->tipo;
		$nombre_session = $ci->session->usuario;
        $menus = $ci->Menu_model->get_menu($tipo);
		$modulos_base = $ci->Menu_model->get_modulo($tipo);
		$modulos = array();
		foreach ($menus as $menu)
		{
			$id_menu = $menu->id_menu;
			$modulos[$id_menu] = array_filter($modulos_base, function($modulo) use ($id_menu)
			{
				return $modulo->id_menu == $id_menu;
			});
		}
		$menu_data = array(
			'menus'=>$menus,
			'modulos' => $modulos,
			'nombre_session' => $nombre_session,
		);

		$hash = array(
			"value"=>$ci->security->get_csrf_hash(),
			"name"=>$ci->security->get_csrf_token_name()
		);

		$ci->load->view('template/header');
		$ci->load->view('template/menu',$menu_data);
		$ci->load->view($view, $view_data);
		$ci->load->view('template/footer',$hash);
        return true;
	}
}

?>
