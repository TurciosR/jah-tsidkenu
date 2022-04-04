<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RolesModel extends CI_Model
{
    var $table = "roles";
    var $pk = "id_rol";

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
        $this->db->where('deleted', 0);
        $rows = $this->db->get($this->table);
        if ($rows->num_rows() > 0) {
            return $rows->result();
        } else {
            return NULL;
        }
    }
    function total_rows(){
        $rows = $this->db->get($this->table);
        if ($rows->num_rows() > 0) {
            return $rows->num_rows();
        } else {
            return NULL;
        }
    }

    /*function exits_row($camp1,$camp2){
        $this->db->where('nombre_cat', $camp1);
        $this->db->where('descripcion', $camp2);
        $rows = $this->db->get($this->table);
        if ($rows->num_rows() > 0) {
            return 1;
        } else {
            return NULL;
        }
    }*/

    function get_row_info($id){
        $this->db->where($this->pk, $id);
        $rows = $this->db->get($this->table);
        if ($rows->num_rows() > 0) {
            return $rows->row();
        } else {
            return NULL;
        }
    }
    function get_state($id){
        $this->db->where('activo', 1);
        $this->db->where($this->pk, $id);
        $rows = $this->db->get($this->table);
        if ($rows->num_rows() > 0) {
            return 1;
        } else {
            return NULL;
        }
    }
    function get_roles($id){
        $this->db->where("id_rol", $id);
        $rows = $this->db->get("roles_detalle");
        if ($rows->num_rows() > 0) {
            return $rows->result();
        } else {
            return NULL;
        }
    }
    function insert_rol($data){
        $this->db->insert('roles', $data);
        if($this->db->affected_rows() > 0){
            return $this->db->insert_id();
        }else{
            return NULL;
        }
    }
}
