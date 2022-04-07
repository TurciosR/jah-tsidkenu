<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Libreta_model extends CI_Model
{

	function get_movs($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal,$ini,$fin)
	{
		$this->db->select('id,descripcion, DATE_FORMAT(fecha, "%d-%m-%Y") as fecha,venta, ingreso');
		$this->db->where("id_sucursal", $id_sucursal);
		$this->db->where("fecha>=", $ini);
		$this->db->where("fecha<=", $fin);
		$this->db->where("borrado", 0);
		$this->db->order_by($order, $dir);

		if (!empty($search)) {
			$x = 0;
			foreach ($valid_columns as $sterm) {
				if ($x == 0) {
					$this->db->where("id_sucursal", $id_sucursal);
					$this->db->where("borrado", 0);
					$this->db->where("fecha>=", $ini);
					$this->db->where("fecha<=", $fin);
					$this->db->like($sterm, $search);
				} else {
					$this->db->where("id_sucursal", $id_sucursal);
					$this->db->where("borrado", 0);
					$this->db->where("fecha>=", $ini);
					$this->db->where("fecha<=", $fin);
					$this->db->or_like($sterm, $search);
				}
				$x++;
			}
		}
		$this->db->limit($length, $start);
		$coordinador = $this->db->get("libreta");
		if ($coordinador->num_rows() > 0) {
			return $coordinador->result();
		} else {
			return 0;
		}
	}

	function total_rows(){
		$clients = $this->db->get("libreta");
		if ($clients->num_rows() > 0) {
			return $clients->num_rows();
		} else {
			return 0;
		}
	}

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
	
	function get_one_row($tabla,$where){
		foreach ($where as $key => $value) {
			// code...
			$this->db->where($key, $value);
		}
		$data = $this->db->get($tabla);
		if ($data->num_rows() > 0) {
			return $data->row();
		} else {
			return 0;
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
