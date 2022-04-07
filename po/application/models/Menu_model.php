<?php
class Menu_model extends CI_Model {
	function get_menu($tipo)
	{
		$query = $this->db->where("visible", "1");
	  $query = $this->db->where("tipo", $tipo);
		$query = $this->db->order_by('prioridad', 'ASC');;
		$query = $this->db->get("menu");
		if ($query->num_rows() > 0){
			return $query->result();
		}
		return 0;
	}
	function get_modulo($tipo)
	{
		if($tipo > 1)
		{
		//	$query = $this->db->where("rh","1");
		}
		else
		{
		}
		//$query = $this->db->where("tipo", $tipo);
		$query = $this->db->where("mostrarmenu", "1");
		$query = $this->db->get("modulo");
		if ($query->num_rows() > 0){
			return $query->result();
		}
		return 0;
	}
}
?>
