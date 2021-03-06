<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProveedoresModel extends CI_Model
{
	var $table = "proveedores";
	var $pk = "id_proveedor";

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

	function exits_row($name,$address,$cellphone){
		$this->db->where('name', $name);
		$this->db->where('address', $address);
		$this->db->where('cellphone', $cellphone);
		$row = $this->db->get("clients");
		if ($row->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	function get_row_info($id){
		$this->db->where($this->pk, $id);
		$row = $this->db->get($this->table);
		if ($row->num_rows() > 0) {
			return $row->row();
		} else {
			return 0;
		}
	}

	function get_state($id){
		$this->db->where('activo', 1);
		$this->db->where($this->pk, $id);
		$row = $this->db->get($this->table);
		if ($row->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	function get_empresas(){
		$this->db->where('estado', 1);
		$row = $this->db->get("empresa");
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return 0;
		}
	}

	function get_departamentos(){
		$row = $this->db->get("departamento");
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return 0;
		}
	}
	function get_giro(){
		$row = $this->db->get("giro");
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return 0;
		}
	}
	function get_categoria_proveedor(){
		$row = $this->db->get("categoria_proveedor");
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return 0;
		}
	}
	function get_tipo_proveedor(){
		$row = $this->db->get("tipo_proveedor");
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return 0;
		}
	}
    function get_municipio($id_departamento){
        $this->db->select('id_municipio,nombre');
        if($id_departamento>0){
            $this->db->where('id_departamento', $id_departamento);
        }
        $cars = $this->db->get("municipio");
        if ($cars->num_rows() > 0) {
            return $cars->result();
        } else {
            return 0;
        }
    }
	/*function get_proveedores($id){
        $this->db->select('proveedores.nombre, producto_proveedor.*');
	    $this->db->where('id_producto', $id);
        $this->db->join('proveedores', 'proveedores.id_proveedor = producto_proveedor.id_proveedor');
		$row = $this->db->get("producto_proveedor");
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return NULL;
		}
	}
	function get_proveedor_autocomplete($query){
        $this->db->select('id_proveedor, nombre');
        $this->db->like('nombre', $query);
        $this->db->where('activo', 1);
        $query = $this->db->get('proveedores');
        if ($query->num_rows() > 0) return $query->result();
        else return NULL;
    }

	function insertar_producto($data){
        $this->db->insert('producto', $data);
        if($this->db->affected_rows() > 0){
            return $this->db->insert_id();
        }else{
            return NULL;
        }
    }
    function insertar_imagen($data){
        $this->db->insert('producto_imagen', $data);
        if($this->db->affected_rows() > 0){
            return $this->db->insert_id();
        }else{
            return NULL;
        }
    }*/
    function eliminar_imagen($data,$id_producto){
	    $this->db->where('id_producto', $id_producto);
	    for ($i=0;$i<count($data);$i++){
            $this->db->where("id_imagen != '".$data[$i]["id_imagen"]."'");
        }
        $this->db->delete('producto_imagen');
    }
    function get_images($id){
        $this->db->where('id_producto', $id);
        $row = $this->db->get("producto_imagen");
        if ($row->num_rows() > 0) {
            return $row->result();
        } else {
            return NULL;
        }
    }
}

/* End of file ClientModel.php */
