<?php
class Usuario_model extends CI_Model {

	function existe_usuario($usuario, $id=0){
		$this->db->select('nombre');
		$this->db->where('usuario', $usuario);
		$this->db->where('id_usuario != ', $id);
		$query = $this->db->get('usuario');
		if ($query->num_rows() > 0){
				return 1;
		}
		return 0;
	}

	function usuario_login($correo,$clave){
		$this->db->where('usuario', $correo);
		$this->db->where('password', md5($clave));
		$query = $this->db->get('usuario');
		if ($query->num_rows() > 0){
				return $query->row();
		}
		return 0;
	}

	function get_usuarios($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
 	{
		 if ($order !=	 null) {
			 $this->db->order_by("id_usuario", "desc");
		 }

		 if (!empty($search)) {
			 $x = 0;
			 foreach ($valid_columns as $sterm) {
				 if ($x == 0) {
					 $this->db->like($sterm, $search);
				 } else {
					 $this->db->or_like($sterm, $search);
				 }
				 $x++;
			 }
		 }
		$this->db->limit($length, $start);
		$this->db->where('id_sucursal', $id_sucursal);
		$query = $this->db->get('usuario');
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return 0;
		}
	 }
 	function count_usuarios($id_sucursal){
		$this->db->where('id_sucursal', $id_sucursal);
		$query = $this->db->get('usuario');
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		} else {
			return 0;
		}
	 }

	function get_usuario($id_usuario){
		$this->db->where('md5(id_usuario)', $id_usuario);
		$query = $this->db->get("usuario");
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}
}
