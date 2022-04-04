<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CajaModel extends CI_Model
{
	var $table = "caja";
	var $pk = "id_caja";

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
					$this->db->where("c.id_sucursal",$id_sucursal);
				} else {
					$this->db->or_like($sterm, $search);
					$this->db->where("c.id_sucursal",$id_sucursal);
				}
				$x++;
			}
		}
		$this->db->select("id_caja, nombre, serie, desde, hasta, correlativo_dispo, resolucion, fecha, id_sucursal, activa");
		$this->db->from('caja as c');
		$this->db->where("c.id_sucursal",$id_sucursal);
		$this->db->limit($length, $start);
		$clients = $this->db->get();
		if ($clients->num_rows() > 0) {
			return $clients->result();
		} else {
			return 0;//$this->db->last_query();
		}
	}
	function total_rows(){
		$clients = $this->db->get("caja");
		if ($clients->num_rows() > 0) {
			return $clients->num_rows();
		} else {
			return 0;
		}
	}

	function insert_row($data){
        $this->db->insert('servicio', $data);
        if($this->db->affected_rows() > 0){
            return $this->db->insert_id();
        }else{
            return NULL;
        }
    }
		function get_row_info($id){
			$this->db->where('id_caja', $id);
			$clients = $this->db->get($this->table);
			if ($clients->num_rows() > 0) {
				return $clients->row();
			} else {
				return 0;
			}
		}
	function get_state($id){
		$this->db->select("activa");
		$this->db->where('id_caja', $id);
		$clients = $this->db->get($this->table);
		if ($clients->num_rows() > 0) {
			return $clients->row();
		} else {
			return 0;
		}
	}

	function inAndCon($table,$data){
		$this->db->insert($table, $data);
		if($this->db->affected_rows() > 0){
			return $this->db->insert_id();
		}else{
			return NULL;
		}
	}
	function get_aperturascaja_activa($id_sucursal,$fecha){
		$this->db->where('id_sucursal', $id_sucursal);
		$this->db->where('fecha', $fecha);
		$this->db->where('vigente', '1');
		$query = $this->db->get("apertura_caja");
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		else {
			return NULL;
		}
	}
	function get_apertura_activa($caja,$fecha){
		$this->db->where("caja",$caja);
	  $this->db->where('fecha', $fecha);
		$this->db->order_by('id_apertura',"DESC");
		$this->db->limit(1);
		$query = $this->db->get("apertura_caja");
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		else {
			return NULL;
		}
	}
	function get_cajas_disponibles($id_sucursal,$fecha){
		$this->db->distinct();
		$this->db->select("c.id_caja,c.nombre,c.activa,a.vigente");
		$this->db->from("caja AS c");
		$this->db->join("apertura_caja AS a","c.id_caja=a.caja");
		$this->db->where("c.id_sucursal",$id_sucursal);
		$this->db->where('a.fecha', $fecha);
		$this->db->where('a.vigente', '0');
		$this->db->where('c.activa', '1');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return NULL;
		}
	}

	function get_precios(){
		$porc = $this->db->get("porcentajes");
		return $porc->result();
	}
	function get_porcent_desc_cliente($id_clasifica){
		$this->db->where("deleted","0");
	  $this->db->where('id_clasifica', $id_clasifica);
		$query = $this->db->get("clasifica_cliente");
		return $porc->result();
	}
	function get_clasifica_cliente($id_cliente){
		$this->db->where("deleted","0");
	  $this->db->where('id_clasifica', $id_clasifica);
		$query = $this->db->get("clasifica_cliente");
		return $porc->result();
	}
	function get_precios_exis($id){
		$porc = $this->db->where("id_producto",$id)->get("producto_precio");
		return $porc->result();
	}


function get_caja($id_caja)
{
	$this->db->select("clientes.nombre, caja.fecha,caja.id_sucursal_despacho");
	$this->db->from("caja");
	$this->db->join("clientes","clientes.id_cliente = caja.id_cliente");
	$this->db->where('id_caja', $id_caja);
	$query = $this->db->get();
	if ($query->num_rows() > 0) {
		return $query->row();
	}
	else {
		return 0;
	}
}

function get_stock($id_producto,$id_color,$id_sucursal)
{
	$this->db->where('id_sucursal', $id_sucursal);
	$this->db->where('id_producto', $id_producto);
	$this->db->where('id_color', $id_color);
	$query = $this->db->get('stock');
	if ($query->num_rows() > 0) {
		return $query->row();
	}
	else {
		$data = array(
			'id_producto' => $id_producto,
			'id_sucursal' => $id_sucursal,
			'id_color' => $id_color,
			'cantidad' => 0,
		);
		$this->db->insert('stock', $data);
		if($this->db->affected_rows() > 0){
			$this->db->where('id_sucursal', $id_sucursal);
			$this->db->where('id_producto', $id_producto);
			$this->db->where('id_color', $id_color);
			$query = $this->db->get('stock');
			if ($query->num_rows() > 0) {
				return $query->row();
			}
		}
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

function get_detail_rows($tabla,$where){
	foreach ($where as $key => $value) {
		// code...
		$this->db->where($key, $value);
	}
	$detail = $this->db->get($tabla);
	if ($detail->num_rows() > 0) {
		return $detail->result();
	} else {
		return 0;
	}
}

function get_detail_ci($id_carga){

	$this->db->select("producto_color.color,icd.*, p.nombre,p.imei,p.n_imei,p.marca,p.modelo");
	$this->db->from('caja_detalle AS icd');
	$this->db->join('producto as p', 'p.id_producto=icd.id_producto');
	$this->db->join('producto_color', 'producto_color.id_color=icd.id_color',"left");
	$this->db->where('id_caja',$id_carga);
	$this->db->order_by('icd.id_detalle', 'ASC');
	$data = $this->db->get();
	if ($data->num_rows() > 0) {
		return $data->result();
	} else {
		return 0;
	}
}
function get_detail_serv($id_carga){

	$this->db->select("icd.*, s.nombre,s.precio_sugerido,s.precio_minimo");
	$this->db->from('caja_detalle AS icd');
	$this->db->join('servicio as s', 's.id_servicio=icd.id_producto');
	$this->db->where('icd.id_caja',$id_carga);
	$this->db->where('icd.tipo_prod',1);
	$this->db->order_by('icd.id_detalle', 'ASC');
	$data = $this->db->get();
	if ($data->num_rows() > 0) {
		return $data->result();
	} else {
		return NULL;
	}
}

function getGarantia($id_producto,$estado)
{
	$this->db->where('id_producto', $id_producto);
	$data = $this->db->get("producto");
	if ($data->num_rows() > 0) {
		$dat = $data->row();
		if ($estado=="NUEVO") {
			// code...
			return $dat->dias_garantia;
		}
		else {
			return $dat->dias_garantia_usado;
		}
	} else {
		return 0;
	}
}

	function get_porcent_client($clasifica){
		$this->db->select('porcentaje');
		$this->db->where('id_clasifica', $clasifica);
		$this->db->where('deleted',0);
		$row =$this->db->get('clasifica_cliente');
			if ($row->num_rows() > 0) {
					return $row->row();
			} else {
					return NULL;
			}
	}
	function get_tipodoc(){
		$this->db->where('cliente',1);
		$row =$this->db->get('tipodoc');
			if ($row->num_rows() > 0) {
					return $row->result();

			} else {
					return NULL;
			}
	}
}
/* End of file CajaModel.php */
