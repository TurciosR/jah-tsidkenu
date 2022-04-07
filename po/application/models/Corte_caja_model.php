<?php

class Corte_caja_model extends CI_Model
{
  function get_caja($order = 'nombre', $search, $valid_columns, $length, $start, $dir = 'desc',$id_sucursal)
  {
    $this->db->select('MD5(id_corte) as id_corte,DATE_FORMAT(fecha, "%d-%m-%Y") as fecha,total_efectivo,efectivo_caja,observaciones');
    $this->db->where('id_sucursal', $id_sucursal);
    $this->db->order_by($order, $dir);
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
    $coordinador = $this->db->get("corte_caja");
    //echo $this->db->last_query();
    if ($coordinador->num_rows() > 0) {
      return $coordinador->result();
    } else {
      return 0;
    }
  }

  function total_caja($id_sucursal)
  {
    $query = $this->db->select("COUNT(*) as num");
    $this->db->where('id_sucursal', $id_sucursal);
    $query = $this->db->get("corte_caja");
    $result = $query->row();
    if (isset($result)) return $result->num;
    return 0;
  }

  function get_total_factura($fecha){

		$this->db->where('fecha', $fecha);
		$this->db->where('estado', 1);
		$this->db->select('SUM(total+iva-retencion) as total');
		$datos = $this->db->get('factura ');
		if ($datos->num_rows() > 0) {
			return $datos->row();
		} else {
			return "";
		}
	}
	function get_total_abono($fecha){
		$this->db->where('fecha', $fecha);
		$this->db->select('SUM(FORMAT(abono,2)) as total');
		$datos = $this->db->get('abono');
		if ($datos->num_rows() > 0) {
			return $datos->row();
		} else {
			return "";
		}
	}
	function get_total_otros_ingresos($fecha){

		$this->db->where('fecha', $fecha);
		$this->db->where('entrada', 1);
		$this->db->select('SUM(total) as total');
		$datos = $this->db->get('mov_caja ');
		if ($datos->num_rows() > 0) {
			return $datos->row();
		} else {
			return "";
		}
	}
	function get_total_egresos($fecha){
		$this->db->where('fecha', $fecha);
		$this->db->where('salida', 1);
		$this->db->select('SUM(total) as total');
		$datos = $this->db->get('mov_caja ');
		if ($datos->num_rows() > 0) {
			return $datos->row();
		} else {
			return "";
		}
	}
	function get_corte_caja($id){
		$this->db->where('MD5(id_corte)', $id);
		$datos = $this->db->get('corte_caja ');
		if ($datos->num_rows() > 0) {
			return $datos->row();
		} else {
			return "";
		}
	}


  function save($table, $data){
    return $this->db->insert($table, $data);
  }

  function update($id, $data){
    return $this->db->update("mov_caja", $data, "id_movimiento=".$id);
  }

  function delete($tabla,$id){
    return $this->db->delete($tabla, array("MD5(id_movimiento)" => $id));
  }

  function begin()
  {
    $this->db->trans_begin();
  }

  function rollback()
  {
    $this->db->trans_rollback();
  }

  function commit()
  {
    $this->db->trans_commit();
  }
  function _insert_id()
  {
    $last_id = $this->db->insert_id();
    return $last_id;
  }

}

?>
