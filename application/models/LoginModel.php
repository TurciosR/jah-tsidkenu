<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoginModel extends CI_Model
{
	private $table = "usuario";

	function exits_user($username){
		$this->db->where('usuario', $username);
		$query = $this->db->get($this->table);
		if ($query->num_rows() > 0){
			return 1;
		}
		else return 0;
	}

	function login_user($usuario,$password){
		$this->db->where('usuario', $usuario);
		$query = $this->db->get($this->table);
		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			if($result !== null){
				return $result;
				// $passwordb = decrypt($result->password);
				// if($password == $passwordb)
				// {
				// 	return $result;
				// }
				// else {
				// 	return 0;
				// }
		}
		return 0;
	}
}

}

/* End of file .php */
