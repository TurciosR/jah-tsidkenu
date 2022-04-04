<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BancoModel extends CI_Model
{
  function get_collection($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
  {
    if ($order !=	 null) {
      $this->db->order_by($order, $dir);
    }
    if (!empty($search)) {
      $x = 0;
      foreach ($valid_columns as $sterm) {
        if ($x == 0) {
          $this->db->like($sterm, $search);
          $this->db->where("id_sucursal",$id_sucursal);
        } else {
          $this->db->or_like($sterm, $search);
          $this->db->where("id_sucursal",$id_sucursal);
        }
        $x++;
      }
    }
    $this->db->select("*");
    $this->db->limit($length, $start);
    $this->db->from('banco');
    $this->db->where("id_sucursal",$id_sucursal);
    $clients = $this->db->get();
    if ($clients->num_rows() > 0) {
      return $clients->result();
    } else {
      return 0;
    }
  }

  function total_rows($id_sucursal)
  {
    $this->db->select("*");
    $this->db->from('banco');
    $this->db->where("id_sucursal",$id_sucursal);
    $clients = $this->db->get();
    return $clients->num_rows();
  }
  function get_state($id){
      $this->db->where('deleted', 0);
      $this->db->where('id', $id);
      $rows = $this->db->get('banco');
      if ($rows->num_rows() > 0) {
          return 1;
      } else {
          return 0;
      }
  }
  function get_row_info($id){
		$this->db->where("id", $id);
		$rows = $this->db->get("banco");
		if ($rows->num_rows() > 0) {
			return $rows->row();
		} else {
			return NULL;
		}
	}
}
 ?>
