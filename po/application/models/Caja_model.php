<?php

class Caja_model extends CI_Model
{
  function get_caja($order = 'nombre', $search, $valid_columns, $length, $start, $dir = 'desc',$id_sucursal)
  {
    $this->db->select('MD5(mc.id_movimiento) as id_movimiento,mc.fecha,mc.total, mc.concepto, mc.responsable,IF(entrada=1,"ENTRADA","SALIDA") as tipo');
    $this->db->where('mc.id_sucursal', $id_sucursal);
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
    $coordinador = $this->db->get("mov_caja as mc");
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
    $query = $this->db->get("mov_caja");
    $result = $query->row();
    if (isset($result)) return $result->num;
    return 0;
  }


  function existe_mov_caja($array){
    $num = $this->db->get_where("mov_caja", $array)->num_rows();
    return $num;
  }
  function save($table, $data){
    return $this->db->insert($table, $data);
  }

  function get($id){
    $caja = $this->db->get_where("mov_caja", array("MD5(id_movimiento)" => $id))->row();
    return $caja;
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
