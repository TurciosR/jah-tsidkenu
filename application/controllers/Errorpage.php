<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ErrorPage extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		validar_session($this);
	}
	public function index()
	{
		layout("404");
	}

}

/* End of file Controllername.php */
