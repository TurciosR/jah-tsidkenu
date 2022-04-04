<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductosModel extends CI_Model
{
	var $table = "producto";
	var $pk = "id_producto";

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
		$this->db->select("producto.*, categoria.nombre as categoria");
		$this->db->where("producto.deleted",'0');
		$this->db->limit($length, $start);
		$this->db->join('categoria', 'categoria.id_categoria = producto.id_categoria', 'left');
		$clients = $this->db->get($this->table);
		if ($clients->num_rows() > 0) {
			return $clients->result();
		} else {
			return 0;
		}
	}

	function get_collection_stock($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
	{
		if ($order !=	 null) {
			$this->db->order_by($order, $dir);
		}
		if (!empty($search)) {
			$x = 0;
			foreach ($valid_columns as $sterm) {
				if ($x == 0) {
					$this->db->like($sterm, $search);
					$this->db->where("stock.id_sucursal",$id_sucursal);
				} else {
					$this->db->or_like($sterm, $search);
					$this->db->where("stock.id_sucursal",$id_sucursal);
				}
				$x++;
			}
		}
		$this->db->select("stock.id_stock,producto.*, categoria.nombre as categoria, if(stock.cantidad is NULL,0,stock.cantidad) as stock");
		$this->db->where("producto.deleted",'0');
		$this->db->where("stock.id_sucursal",$id_sucursal);
		$this->db->limit($length, $start);
		$this->db->join('categoria', 'categoria.id_categoria = producto.id_categoria', 'left');
		$this->db->join('stock', 'stock.id_producto = producto.id_producto', 'left');
		$clients = $this->db->get($this->table);
		if ($clients->num_rows() > 0) {
			return $clients->result();
		} else {
			return 0;
		}
	}

	function total_rows(){
		$clients = $this->db->get($this->table);
		if ($clients->num_rows() > 0) {
			return $clients->num_rows();
		} else {
			return 0;
		}
	}

	function exits_row($name,$address,$cellphone){
		$this->db->where('name', $name);
		$this->db->where('address', $address);
		$this->db->where('cellphone', $cellphone);
		$clients = $this->db->get("clients");
		if ($clients->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	function get_row_info($id){
		$this->db->where('id_producto', $id);
		$clients = $this->db->get($this->table);
		if ($clients->num_rows() > 0) {
			return $clients->row();
		} else {
			return 0;
		}
	}

	function get_state($id){
		$this->db->where('activo', 1);
		$this->db->where('id_producto', $id);
		$clients = $this->db->get($this->table);
		if ($clients->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	function get_empresas(){
		$this->db->where('estado', 1);
		$clients = $this->db->get("empresa");
		if ($clients->num_rows() > 0) {
			return $clients->result();
		} else {
			return 0;
		}
	}
	function get_categorias(){
		$this->db->where('activo', 1);
		$clients = $this->db->get("categoria");
		if ($clients->num_rows() > 0) {
			return $clients->result();
		} else {
			return 0;
		}
	}
	function get_proveedores($id){
        $this->db->select('proveedores.nombre, producto_proveedor.*');
	    $this->db->where('id_producto', $id);
        $this->db->join('proveedores', 'proveedores.id_proveedor = producto_proveedor.id_proveedor');
		$clients = $this->db->get("producto_proveedor");
		if ($clients->num_rows() > 0) {
			return $clients->result();
		} else {
			return NULL;
		}
	}
	function get_precios(){
	  $porc = $this->db->where("deleted","0")->get("porcentajes");
		return $porc->result();
	}
	function get_precios_exis($id){
	  $porc = $this->db->where("id_producto",$id)->get("producto_precio");
		return $porc->result();
	}
	function get_colores_exis($id){
	  $porc = $this->db->where("id_producto",$id)->get("producto_color");
		return $porc->result();
	}
	/*function get_precios($id){
	    $this->db->where('id_producto', $id);
        $this->db->join('proveedores', 'proveedores.id_proveedor = producto_proveedor.id_proveedor');
		$clients = $this->db->get("producto_proveedor");
		if ($clients->num_rows() > 0) {
			return $clients->result();
		} else {
			return NULL;
		}
	}*/
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
    }
    function eliminar_imagen($data,$id_producto){
	    $this->db->where('id_producto', $id_producto);
	    for ($i=0;$i<count($data);$i++){
            $this->db->where("id_imagen != '".$data[$i]["id_imagen"]."'");
        }
        $this->db->delete('producto_imagen');
    }
    function get_images($id){
        $this->db->where('id_producto', $id);
        $clients = $this->db->get("producto_imagen");
        if ($clients->num_rows() > 0) {
            return $clients->result();
        } else {
            return NULL;
        }
    }
   
    function get_idColor($id_producto,$color){
		
		$this->db->select('id_color');
        $this->db->where('id_producto', $id_producto);
		$this->db->where('color', $color);
		$row =$this->db->get('producto_color');
        //$row = $this->db->row();
        if ($row->num_rows() > 0) {
            return $row->row();
        } else {
            return NULL;
        }
    }
    function get_color_stock($id_producto,$id_color){		
		$this->db->select('id_stock,cantidad');
        $this->db->where('id_producto', $id_producto);
		$this->db->where('id_color', $id_color);
        $row =$this->db->get('stock');
        if ($row->num_rows() > 0) {
            return $row->row();
        } else {
            return NULL;
        }
    }
}

/* End of file ClientModel.php */
