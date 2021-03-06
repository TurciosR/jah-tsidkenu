<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
	function get_saldo($id_colaborador){
		$this->db->where('id_colaborador', $id_colaborador);
		$query = $this->db->get("vacaciones_colaboradores");
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function grafica_permiso($actual,$anterior){
		$this->db->select('COUNT("id_permiso") as total');
		$this->db->where('estado','APROBADA');
		$this->db->where("fecha_solicitud BETWEEN '$actual' AND '$anterior'");
		$query=$this->db->get('permisos');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}
	function grafica_vacacion($actual,$anterior){
		$this->db->select('COUNT("id_vacacion") as total');
		$this->db->where('estado','APROBADA');
		$this->db->where("fecha_solicitud BETWEEN '$actual' AND '$anterior'");
		$query=$this->db->get('vacaciones');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_permisos_admin(){
		$this->db->select("DATE_FORMAT(p.fecha_solicitud,'%d-%m-%Y') as fecha");
		$this->db->select("TIME_FORMAT(p.hora_solicitud,'%H:%i %p') as hora");
		$this->db->select("CONCAT(c.nombre,' ',c.apellido) as colaborador");
		$this->db->select('u.nombre as unidad');
		$this->db->order_by('p.id_permiso', 'desc');
		$this->db->join('colaboradores as c', 'c.id_colaborador = p.id_colaborador');
		$this->db->join('unidades as u', 'u.id_unidad = c.id_unidad');
		$this->db->limit(5);
		$permisos=$this->db->get('permisos as p');
		if($permisos->num_rows()>0){
			return $permisos->result();
		}else{
			return 0;
		}
	}

	function get_permisos($id_colaborador){
		$this->db->select("DATE_FORMAT(p.fecha_solicitud,'%d-%m-%Y') as fecha");
		$this->db->select("TIME_FORMAT(p.hora_solicitud,'%H:%i %p') as hora");
		$this->db->select("CONCAT(c.nombre,' ',c.apellido) as colaborador");
		$this->db->select('u.nombre as unidad');
		$this->db->order_by('p.id_permiso', 'desc');
		$this->db->where('c.jefe_inmediato', $id_colaborador);
		$this->db->or_where('c.jefe_superior', $id_colaborador);
		$this->db->join('colaboradores as c', 'c.id_colaborador = p.id_colaborador');
		$this->db->join('unidades as u', 'u.id_unidad = c.id_unidad');
		$this->db->limit(5);
		$permisos=$this->db->get('permisos as p');
		if($permisos->num_rows()>0){
			return $permisos->result();
		}else{
			return 0;
		}
	}

	function get_vacaciones_admin(){
		$this->db->select("DATE_FORMAT(v.fecha_solicitud,'%d-%m-%Y') as fecha");
		$this->db->select("TIME_FORMAT(v.hora_solicitud,'%H:%i %p') as hora");
		$this->db->select("CONCAT(c.nombre,' ',c.apellido) as colaborador");
		$this->db->select('u.nombre as unidad');
		$this->db->order_by('v.id_vacacion', 'desc');
		$this->db->join('colaboradores as c', 'c.id_colaborador = v.id_colaborador');
		$this->db->join('unidades as u', 'u.id_unidad = c.id_unidad');
		$this->db->limit(5);
		$query=$this->db->get('vacaciones as v');
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return 0;
		}
	}

	function get_vacaciones($id_colaborador){
		$this->db->select("DATE_FORMAT(v.fecha_solicitud,'%d-%m-%Y') as fecha");
		$this->db->select("TIME_FORMAT(v.hora_solicitud,'%H:%i %p') as hora");
		$this->db->select("CONCAT(c.nombre,' ',c.apellido) as colaborador");
		$this->db->select('u.nombre as unidad');
		$this->db->order_by('v.id_vacacion', 'desc');
		$this->db->join('colaboradores as c', 'c.id_colaborador = v.id_colaborador');
		$this->db->join('unidades as u', 'u.id_unidad = c.id_unidad');
		$this->db->limit(5);
		$query=$this->db->get('vacaciones as v');
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return 0;
		}
	}

	function get_saldo_dias($id_colaborador){
		$this->db->select('SUM(saldo) as num');
		$this->db->where('id_colaborador', $id_colaborador);
		//$this->db->where('year', date("Y"));
		$query=$this->db->get('vacaciones_colaboradores');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_solicitudes_permisos($id_colaborador){
		$this->db->select('COUNT(p.id_permiso) as num');
		$this->db->where('p.estado=', 'ESPERANDO REVISION');
		$this->db->where('c.jefe_inmediato', $id_colaborador);
		$this->db->or_where('c.jefe_superior', $id_colaborador);
		$this->db->join('colaboradores as c', 'c.id_colaborador = p.id_colaborador');
		$query=$this->db->get('permisos as p');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_solicitudes_vacaciones($id_colaborador){
		$this->db->select('COUNT(p.id_vacacion) as num');
		$this->db->where('p.estado=', 'ESPERANDO APROBACION');
		$this->db->where('c.jefe_inmediato', $id_colaborador);
		$this->db->or_where('c.jefe_superior', $id_colaborador);
		$this->db->join('colaboradores as c', 'c.id_colaborador = p.id_colaborador');
		$query=$this->db->get('vacaciones as p');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_solicitudes_permisos_admin(){
		$this->db->select('COUNT(p.id_permiso) as num');
		$this->db->where('p.estado=', 'ESPERANDO REVISION');
		$query=$this->db->get('permisos as p');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_solicitudes_vacaciones_admin(){
		$this->db->select('COUNT(v.id_vacacion) as num');
		$this->db->where('v.estado=', 'ESPERANDO APROBACI??N');
		$query=$this->db->get('vacaciones as v');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_historial_permisos($id_colaborador){
		$this->db->select('COUNT(p.id_permiso) as num');
		$this->db->where('p.id_colaborador', $id_colaborador);
		$this->db->where('p.estado=', 'APROBADA');
		$this->db->where('YEAR(p.fecha_solicitud)', date("Y"));
		$query=$this->db->get('permisos as p');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

	function get_historial_vacaciones($id_colaborador){
		$this->db->select('COUNT(v.id_vacacion) as num');
		$this->db->where('v.id_colaborador', $id_colaborador);
		$this->db->where('v.estado=', 'APROBADA');
		$this->db->where('YEAR(v.fecha_solicitud)', date("Y"));
		$query=$this->db->get('vacaciones as v');
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return 0;
		}
	}

}

/* End of file Dashboard_model.php */
