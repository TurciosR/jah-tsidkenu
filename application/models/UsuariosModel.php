<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsuariosModel extends CI_Model
{
	private $table = "usuario";

	function get_collection($order, $search, $valid_columns, $length, $start, $dir)
	{
		if ($order !=	 null) {
			$this->db->order_by($order, $dir);
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
		$this->db->where("id_usuario>", 0);
		$row = $this->db->get($this->table);
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return 0;
		}
	}

	function total_rows(){
		$row = $this->db->get($this->table);
		if ($row->num_rows() > 0) {
			return $row->num_rows();
		} else {
			return 0;
		}
	}

	function exits_row($usuario){
		$this->db->where('usuario', $usuario);
		$row = $this->db->get($this->table);
		if ($row->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	function exits_row_edit($usuario,$id){
		$this->db->where('usuario', $usuario);
		$this->db->where("id_usuario!='".$id."'");
		$row = $this->db->get($this->table);
		if ($row->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	function get_row_info($id){
		$this->db->where('id_usuario', $id);
		$row = $this->db->get($this->table);
		if ($row->num_rows() > 0) {
			return $row->row();
		} else {
			return 0;
		}
	}

	function get_state($id){
		$this->db->where('activo', 1);
		$this->db->where('id_usuario', $id);
		$row = $this->db->get($this->table);
		if ($row->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	function get_controller(){
		$this->db->where('mostrarmenu', 1);
		$row = $this->db->get("modulo");
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return 0;
		}
	}
	function get_menu(){
		$this->db->where('visible', 1);
		$row = $this->db->get("menu");
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return 0;
		}
	}
	function get_permissions($id){
		$this->db->where('id_usuario', $id);
		$row = $this->db->get("permisos_usuario");
		return $row->result();
	}

}

/* End of file UsuariosModel.php */
