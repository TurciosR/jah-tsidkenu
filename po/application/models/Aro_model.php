<?php
class Aro_model extends CI_Model {

	function existe_aro($codigo, $id=0,$id_su){
		$this->db->select('codigo');
		$this->db->where('codigo', $codigo);
		$this->db->where('sucursal ', $id_su);
		//$this->db->where('id != ', $id);
		$query = $this->db->get('aro');
		if ($query->num_rows() > 0){
				return 1;
		}else{
            return 0;
        }
	}


	function get_aros($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
 	{
        $this->db->select('aro.id,aro.codigo,aro.marca,aro.existencia,aro.casa,s.nombre as nom_su');
        $this->db->join("sucursal as s", "aro.sucursal=s.id", "left");
        //$this->db->where('aro.sucursal', $id_sucursal);
        if ($order !=	 null) {
			 $this->db->order_by($order, $dir);
		 }

		 if (!empty($search)) {
			 $x = 0;
             foreach ($valid_columns as $sterm) {
                // $this->db->where('aro.sucursal', $id_sucursal);
                 if ($x == 0) {
					 $this->db->like($sterm, $search);
				 } else {
					 $this->db->or_like($sterm, $search);
				 }
				 $x++;
			 }
		 }
        $this->db->limit($length, $start);
       // $this->db->where('aro.sucursal', $id_sucursal);
        $query = $this->db->get('aro');
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return 0;
		}
	 }
 	function count_aros($id_sucursal){
		//$this->db->where('sucursal', $id_sucursal);
		$query = $this->db->get('aro');
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		} else {
			return 0;
		}
	 }

	function get_aro($id){
		$this->db->where('md5(id)', $id);
		$query = $this->db->get("aro");
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_existencia_aro($codigo){
        $this->db->select('s.nombre, a.existencia,a.sucursal');
        $this->db->join("sucursal as s", "a.sucursal=s.id");
		$this->db->where('a.codigo', $codigo);
        $this->db->order_by("a.existencia","asc");
        $query = $this->db->get("aro as a");
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return 0;
		}
	}
	function get_existe_aro($codigo,$sucursal){
        $this->db->select('existencia,id');
        $this->db->where('codigo',$codigo);
        $this->db->where('sucursal',$sucursal);
        $query = $this->db->get("aro");
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}


}
