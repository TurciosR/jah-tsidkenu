<?php
class Solicitud_model extends CI_Model {

	function existe_aro($codigo,$id_su){
		$this->db->select('codigo');
		$this->db->where('TRIM(codigo)', $codigo);
		$this->db->where('sucursal ', $id_su);
		//$this->db->where('id != ', $id);
		$query = $this->db->get('aro');
		if ($query->num_rows() > 0){
				return 1;
		}else{
            return 0;
        }
	}
	function get_existe_aro($codigo,$sucursal){
        $this->db->select('existencia,id,marca,casa,codigo');
        $this->db->where('TRIM(codigo)',$codigo);
        $this->db->where('sucursal',$sucursal);
        $query = $this->db->get("aro");
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_one_solicitud($id_solicitud){
        $this->db->select('id_solicitud,sucursal_solicita, id_usuario,fecha,hora,estado,codigo,id_sucursal,fecha_re');
        $this->db->where('md5(id_solicitud)',$id_solicitud);
        $query = $this->db->get("solicitudes");
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_solisitud($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
	{
		$this->db->select('MD5(s.id_solicitud) as id_solicitud, id_solicitud as id,u.nombre, DATE_FORMAT(s.fecha, "%d-%m-%Y") as fecha,
		sur.nombre as sucur, s.codigo,s.estado,DATE_FORMAT(s.fecha_re, "%d-%m-%Y") as fecha_re');
		$this->db->join("usuario as u", "s.id_usuario=u.id_usuario");
		$this->db->join("sucursal as sur", "s.sucursal_solicita=sur.id");
		$this->db->where("s.id_sucursal !=", $id_sucursal);
		$this->db->order_by("s.fecha", "DESC");

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
		$coordinador = $this->db->get("solicitudes as s");
		if ($coordinador->num_rows() > 0) {
			return $coordinador->result();
		} else {
			return 0;
		}
	}

	function count_solicitud($id_sucursal){
		$this->db->where("id_sucursal !=", $id_sucursal);
		$query = $this->db->get('solicitudes');
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		} else {
			return 0;
		}
	}
	function get_solisitud_env($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
	{
		$this->db->select('MD5(s.id_solicitud) as id_solicitud,u.nombre, DATE_FORMAT(s.fecha, "%d-%m-%Y") as fecha,
		sur.nombre as sucur, s.codigo,s.estado,DATE_FORMAT(s.fecha_re, "%d-%m-%Y") as fecha_re');
		$this->db->join("usuario as u", "s.id_usuario=u.id_usuario");
		$this->db->join("sucursal as sur", "s.sucursal_solicita=sur.id");
		$this->db->where("s.id_sucursal ", $id_sucursal);
		$this->db->order_by("s.fecha", "DESC");
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
		$coordinador = $this->db->get("solicitudes as s");
		if ($coordinador->num_rows() > 0) {
			return $coordinador->result();
		} else {
			return 0;
		}
	}

	function count_solicitud_env($id_sucursal){
		$this->db->where("id_sucursal ", $id_sucursal);
		$query = $this->db->get('solicitudes');
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	function get_sucursal($id){
		$cliente = $this->db->get_where("sucursal", array("id" => $id))->row();
		return $cliente;
	}
    function get_cuenta_modal($id){
		$this->db->select('c.id_cuenta,cli.nombre, DATE_FORMAT(c.fecha, "%d-%m-%Y") as fecha,c.estado,
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
