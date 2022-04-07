<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_model extends CI_Model
{
	function get_sucursal($id_sucursal){

		$this->db->where('id', $id_sucursal);
		$this->db->select('UPPER(nombre) as nombre');
		$colaboradores = $this->db->get('sucursal ');
		if ($colaboradores->num_rows() > 0) {
			return $colaboradores->row();
		} else {
			return "";
		}
	}

	function get_corte_caja($inicio,$fin){
		$this->db->where("fecha BETWEEN '$inicio' AND '$fin'");
		$datos = $this->db->get('corte_caja ');
		if ($datos->num_rows() > 0) {
			return $datos->result();
		} else {
			return "";
		}
	}

}


/* End of file Reportes_model.php */
