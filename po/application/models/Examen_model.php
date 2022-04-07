<?php
class Examen_model extends CI_Model {

	function get_examenes($order, $search, $valid_columns, $length, $start, $dir,$id_sucursal)
	{
		$this->db->select('MD5(examenes.id_cliente) as id_cliente,MAX(examenes.id) as id,cliente.nombre, MAX(examenes.fecha) as fecha,cliente.edad,usuario.nombre as optometrista, cliente.fecha_nacimiento');
		$this->db->join("cliente", "examenes.id_cliente=cliente.id");
		$this->db->join("usuario", "examenes.id_optometrista=usuario.id_usuario","left");
		//$this->db->where("examenes.id_sucursal", $id_sucursal);
		$this->db->group_by("examenes.id_cliente");
        $this->db->order_by("id", "desc");
        //$this->db->order_by("examenes.id", "desc");
        //$this->db->order_by("examenes.id", "desc");
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
		$coordinador = $this->db->get("examenes");
        //echo $this->db->last_query();

        if ($coordinador->num_rows() > 0) {
			return $coordinador->result();
		} else {
			return 0;
		}
	}
	function count_examenes($id_sucursal){
		$this->db->join("cliente", "examenes.id_cliente=cliente.id");
		$this->db->join("usuario", "examenes.id_optometrista=usuario.id_usuario","left");
		//$this->db->where("examenes.id_sucursal", $id_sucursal);
		$this->db->group_by("examenes.id_cliente");
		$query = $this->db->get('examenes');
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		} else {
			return 0;
		}
	}


	function traer_cliente($query,$id_sucursal)
	{
		//$this->db->where('sucursal', $id_sucursal);
		$query = urldecode($query);
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
	function get_cliente($id_cliente)
	{
		$this->db->where('md5(id)', $id_cliente);
		$query = $this->db->get("cliente");
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return "";
	}


	function existe_cliente($array){
        $query = $this->db->get_where("cliente", $array);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
				else
				{
					return 0;
				}

	}
	function save($table, $data){
		return $this->db->insert($table, $data);
	}
	function _insert_id()
	{
		$last_id = $this->db->insert_id();
		return $last_id;
	}
	function get($id){
		$cliente = $this->db->get_where("cliente", array("MD5(id)" => $id))->row();
		return $cliente;
	}
	function get_datos($id){
		$this->db->select('examenes.id_cliente,examenes.esfd,examenes.cild,examenes.ejed,examenes.adid,examenes.id,
		examenes.esfi,examenes.cili,examenes.ejei,examenes.adii,examenes.di,examenes.ad,examenes.color_lente,
		examenes.bif,examenes.aro,examenes.tamanio,examenes.color_aro,examenes.observaciones,examenes.id_aro, DATE_FORMAT(examenes.fecha, "%d-%m-%Y") as fecha,
		sucursal.nombre as nombre_sucursal,usuario.nombre as optometrista,examenes.id_sucursal as id_sur');
		$this->db->join("sucursal", "examenes.id_sucursal=sucursal.id","left");
		$this->db->join("usuario", "examenes.id_optometrista=usuario.id_usuario","left");
		$this->db->order_by("examenes.id", "desc");
		$data = $this->db->get_where("examenes", array("MD5(examenes.id_cliente)" => $id))->result();
		return $data;
	}
	public function get_datos_exa($id){
		$this->db->select('examenes.id_cliente,examenes.esfd,examenes.cild,examenes.ejed,examenes.adid,examenes.id,
		examenes.esfi,examenes.cili,examenes.ejei,examenes.adii,examenes.di,examenes.ad,examenes.color_lente,
		examenes.bif,examenes.aro,examenes.tamanio,examenes.color_aro,examenes.observaciones,
		cliente.nombre, examenes.fecha,cliente.edad,cliente.sexo,
		sucursal.nombre as nombre_sucursal,sucursal.direccion,sucursal.telefono,
		usuario.nombre as optometrista');
		$this->db->join("cliente", "examenes.id_cliente=cliente.id","left");
		$this->db->join("sucursal", "examenes.id_sucursal=sucursal.id","left");
		$this->db->join("usuario", "examenes.id_optometrista=usuario.id_usuario","left");
		$data = $this->db->get_where("examenes", array("MD5(examenes.id)" => $id))->row();
		//echo $this->db->last_query();
		return $data;
	}

}
