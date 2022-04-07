<?php
class Cuenta_model extends CI_Model {

	function get_cuenta($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal, $id)
	{
		$this->db->select('MD5(c.id_cuenta) as id_cuenta,empresa,
		 format(c.saldo,2) as saldo,format(c.abono,2) as abono, format(c.monto,2) as monto,	DATE_FORMAT(c.fecha, "%d-%m-%Y") as fecha,
		cli.nombre');
		$this->db->join("cliente as cli", "c.id_cliente=cli.id");
		$this->db->where("c.id_sucursal", $id_sucursal);
		$this->db->where("c.id_cliente", $id);
		$this->db->order_by($order, $dir);
		//echo $this->db->last_query();

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
		$coordinador = $this->db->get("cuenta as c");
		if ($coordinador->num_rows() > 0) {
			return $coordinador->result();
		} else {
			return 0;
		}
	}
	function get_cuenta_clientes($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
	{
		$this->db->select('c.id_cliente,empresa,
		 format(SUM(c.saldo),2) as saldo,format(SUM(c.abono),2) as abono, format(SUM(c.monto),2) as monto,	DATE_FORMAT(c.fecha, "%d-%m-%Y") as fecha,
		cli.nombre');
		$this->db->join("cliente as cli", "c.id_cliente=cli.id");
		$this->db->where("c.id_sucursal", $id_sucursal);
		$this->db->order_by($order, $dir);
		$this->db->group_by("cli.id");
		//echo $this->db->last_query();

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
		$coordinador = $this->db->get("cuenta as c");
		if ($coordinador->num_rows() > 0) {
			return $coordinador->result();
		} else {
			return 0;
		}
	}

	function count_cuenta($id_sucursal, $id){
		$this->db->where("id_sucursal", $id_sucursal);
		$this->db->where("id_cliente", $id);
		$query = $this->db->get('cuenta');

		if ($query->num_rows() > 0) {
			return $query->num_rows();
		} else {
			return 0;
		}
	}
	function count_cuenta_clientes($id_sucursal){
		$this->db->where("id_sucursal", $id_sucursal);
		$query = $this->db->get('cuenta');
		$this->db->group_by("id_cliente");

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
				$output[] = array('producto' => $row["id"]."| ".$row["nombre"]."|".$row["edad"]."|".$row["sexo"]);
			}
			echo json_encode($output);
		}
	}
	function get_sucursal($id){
		$cliente = $this->db->get_where("sucursal", array("id" => $id))->row();
		return $cliente;
	}
    function get_cuenta_modal($id){
		$this->db->select('c.id_cuenta,cli.nombre, DATE_FORMAT(c.fecha, "%d-%m-%Y") as fecha,c.estado,c.empresa,
		 format(c.saldo,2) as saldo,format(c.abono,2) as abono, format(c.monto,2) as monto');
		$this->db->join("cliente as cli", "c.id_cliente=cli.id");
		$this->db->where("MD5(id_cuenta)", $id);
		$cliente = $this->db->get("cuenta as c");
		return $cliente->row();
	}

    function get_cuenta_detalle_modal($id){
        $this->db->select('cd.cantidad,cd.detalle, format(cd.precio,2) as precio,format(cd.subtotal,2) as subtotal');
		$this->db->where("MD5(cd.id_cuenta)", $id);
		$fd = $this->db->get("cuenta_detalle as cd");
		return $fd->result();
	}
    function get_cuenta_detalle_abono($id){
        $this->db->select('format(abono,2) as abono, DATE_FORMAT(fecha, "%d-%m-%Y") as fecha');
        $this->db->where("MD5(id_cuenta)", $id);
		$fd = $this->db->get("abono");
		return $fd->result();
	}
	function get_config_dir($id){
		$cliente = $this->db->get_where("config_dir", array("id_sucursal" => $id))->row();
		return $cliente;
	}

	function save($table, $data){
		return $this->db->insert($table, $data);
	}
	function actualizar($table, $data,$where){
		return $this->db->update($table, $data,$where);
	}
	function existe_cuenta($array){
		$num = $this->db->get_where("cuenta", $array)->num_rows();
		return $num;
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
