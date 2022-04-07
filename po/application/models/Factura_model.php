<?php
class Factura_model extends CI_Model {

	function get_factura($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
	{
		$this->db->select('MD5(f.id_factura) as id_factura,f.num_doc,IF(f.tipo="CCF",CONCAT("CREDITO "," FISCAL"), "FACTURA") as tipo, DATE_FORMAT(f.fecha, "%d-%m-%Y") as fecha,c.nombre,CONCAT("$",(f.total+f.iva-f.retencion)) as total, f.estado ');
		$this->db->join("cliente as c", "f.id_cliente=c.id");
		$this->db->where("f.id_sucursal", $id_sucursal);
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
		$coordinador = $this->db->get("factura as f");
		if ($coordinador->num_rows() > 0) {
			return $coordinador->result();
		} else {
			return 0;
		}
	}

	function count_factura($id_sucursal){
		$this->db->where("id_sucursal", $id_sucursal);
		$query = $this->db->get('factura');

		if ($query->num_rows() > 0) {
			return $query->num_rows();
		} else {
			return 0;
		}
	}


	function traer_cliente($query,$id_sucursal)
	{
		//$this->db->where("sucursal", $id_sucursal);
		$query=urldecode($query);
		$this->db->like('nombre', $query);
		//  $this->db->limit(100);
		$query = $this->db->get('cliente');
		if($query->num_rows() > 0)
		{
			$fechaHoy = new DateTime(date("Y-m-d"));
			$output = array();
			foreach($query->result_array() as $row)
			{
				$fechaNac = new DateTime($row['fecha_nacimiento']);
				$diff = $fechaHoy->diff($fechaNac);
				$edadN = $diff->format("%y");
				$output[] = array('producto' => $row["id"]."| ".$row["nombre"]."|".$row["edad"]."|".$row["sexo"]."|".$row["nit"]."|".$row["nrc"]);
			}
			echo json_encode($output);
		}
	}
	function get_sucursal($id){
		$cliente = $this->db->get_where("sucursal", array("id" => $id))->row();
		return $cliente;
	}

	function get_factura_modal($id){
		$this->db->select('f.id_factura,f.tipo,c.nombre, DATE_FORMAT(f.fecha, "%d-%m-%Y") as fecha,IF(f.tipo="CCF",CONCAT("CREDITO "," FISCAL"), "FACTURA") as tipo_desc,f.num_doc,f.total,f.iva,f.retencion,f.retencion_bol');
		$this->db->join("cliente as c", "f.id_cliente=c.id");
		$this->db->where("MD5(id_factura)", $id);
		$cliente = $this->db->get("factura as f");
		return $cliente->row();;
	}
	function get_factura_detalle_modal($id){
		$this->db->where("MD5(id_factura)", $id);
		$fd = $this->db->get("factura_detalle as f");
		return $fd->result();;
	}
	function get_config_dir($id){
		$cliente = $this->db->get_where("config_dir", array("id_sucursal" => $id))->row();
		return $cliente;
	}

	function save($table, $data){
		return $this->db->insert($table, $data);
	}
	function existe_factura($array){
		$num = $this->db->get_where("factura", $array)->num_rows();
		return $num;
	}
	function get_cuenta($id_cliente){
		$this->db->select('c.id_cuenta,cl.nombre,DATE_FORMAT(c.fecha, "%d-%m-%Y") as fecha,FORMAT(c.monto,2) as monto ,FORMAT(c.saldo,2) as saldo,FORMAT(c.abono,2) as abono');
		$this->db->where("c.id_cliente", $id_cliente);
		$this->db->where("c.estado", 0);
		$this->db->join("cliente as cl", "c.id_cliente=cl.id");
		$query = $this->db->get('cuenta as c');
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return 0;
		}
	}

	function get_facturac($id){
		$this->db->where("MD5(id_factura)", $id);
		$cliente = $this->db->get("factura");
		return $cliente->row();;
	}
	function get_factura_detallec($id){
		$this->db->where("MD5(id_factura)", $id);
		$fd = $this->db->get("factura_detalle");
		return $fd->result();;
	}
	function actualizar($table, $data,$where){
		return $this->db->update($table, $data,$where);
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
