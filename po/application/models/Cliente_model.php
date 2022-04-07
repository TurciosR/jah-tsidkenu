<?php

class Cliente_model extends CI_Model
{
  function get_cliente($order = 'nombre', $search, $valid_columns, $length, $start, $dir = 'desc',$id_sucursal)
  {
    $this->db->select('MD5(c.id) as id,c.nombre,c.edad, c.dui,  DATE_FORMAT(c.fecha_registro, "%d-%m-%Y") as fecha_registro, fecha_nacimiento');
    //$this->db->join("examenes as e", "c.id=e.id_cliente","LEFT");
    //$this->db->where('e.id_sucursal', $id_sucursal);
    //$this->db->group_by("e.id_cliente");
    //$this->db->order_by("id_examen", "desc");
    $this->db->order_by("c.id", "desc");
    //MAX(e.id) as id_examen
    //$this->db->order_by($order, $dir);

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
    $coordinador = $this->db->get("cliente as c");
    //echo $this->db->last_query();
    if ($coordinador->num_rows() > 0) {
      return $coordinador->result();
    } else {
      return 0;
    }
  }

  function total_cliente($id_sucursal)
  {
    $query = $this->db->select("COUNT(*) as num");
    //$this->db->where('sucursal', $id_sucursal);
    $query = $this->db->get("cliente");
    $result = $query->row();
    if (isset($result)) return $result->num;
    return 0;
  }
  function get_departamento()
  {
    $query = $this->db->get("departamento");
    if ($query->num_rows() > 0) {
      return $query->result();
    }
    return 0;
  }
  function get_municipio($id_departamento)
  {
    $this->db->where('id_departamento_municipio', $id_departamento);
    $query = $this->db->get("municipio");
    if ($query->num_rows() > 0) {
      return $query->result();
    }
    return "";
  }function get_aros($id_sur)
  {
    $this->db->where("sucursal",$id_sur);
    $query = $this->db->get("aro");
    if ($query->num_rows() > 0) {
      return $query->result();
    }
    return "";
  }
  function existe_cliente($array){
    $num = $this->db->get_where("cliente", $array)->num_rows();
    return $num;
  }
  function save($table, $data){
    return $this->db->insert($table, $data);
  }

  function get($id){
    $cliente = $this->db->get_where("cliente", array("MD5(id)" => $id))->row();
    return $cliente;
  }

  function update($id, $data){
    return $this->db->update("cliente", $data, "id=".$id);
  }

  function delete($tabla,$id){
    return $this->db->delete($tabla, array("id_coordinacion" => $id));
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

  function get_datos($id){
    $this->db->select('examenes.id_cliente,examenes.esfd,examenes.cild,examenes.ejed,examenes.adid,examenes.id,examenes.id_sucursal as sucursal1,
		examenes.esfi,examenes.cili,examenes.ejei,examenes.adii,examenes.di,examenes.ad,examenes.color_lente,
		examenes.bif,examenes.aro,examenes.tamanio,examenes.color_aro,examenes.id_aro,examenes.observaciones, DATE_FORMAT(examenes.fecha, "%d-%m-%Y") as fecha,
		sucursal.nombre as nombre_sucursal,usuario.nombre as optometrista');
    $this->db->join("sucursal", "examenes.id_sucursal=sucursal.id","left");
    $this->db->join("usuario", "examenes.id_optometrista=usuario.id_usuario","left");
    $this->db->order_by("examenes.id", "desc");
    $data = $this->db->get_where("examenes", array("MD5(examenes.id_cliente)" => $id))->result();
    return $data;
  }
  function get_cliente_dat($id_cliente)
  {
    $this->db->where('md5(id)', $id_cliente);
    $query = $this->db->get("cliente");
    if ($query->num_rows() > 0) {
      return $query->row();
    }
    return "";
  }
  function get_existe_aro($id_aro){
    $this->db->select('existencia,codigo');
    $this->db->where('id',$id_aro);
    $query = $this->db->get("aro");
    if($query->num_rows()>0){
      return $query->row();
    }else{
      return 0;
    }
  }

}

?>
