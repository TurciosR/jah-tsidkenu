<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuentas_pagarModel extends CI_Model
{
	private $table = "cuentas_por_pagar";
	private $pk = "id_cuentas_por_pagar";

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
		$this->db->where('estado', 0);
    $this->db->select('pagar.*, p.nombre as proveedor');
    $this->db->join('proveedores as p', 'p.id_proveedor = pagar.id_proveedor');
		$rows = $this->db->get('cuentas_por_pagar as pagar');
		if ($rows->num_rows() > 0) {
			return $rows->result();
		} else {
			return NULL;
		}
	}

  function get_row($id){
    $this->db->where('pagar.estado', 0);
    $this->db->where('pagar.id_cuentas', $id);
    $this->db->select('pagar.*, SUM(abono.abono) as abono_total');
    $this->db->join('cuentas_por_pagar_abonos as abono', 'abono.id_cuentas_por_pagar = pagar.id_cuentas');
		$rows = $this->db->get('cuentas_por_pagar as pagar');
		if ($rows->num_rows() > 0) {
			return $rows->row();
		} else {
			return NULL;
		}
	}
  function get_row_abonos($id){
    $this->db->where('id_cuentas_por_pagar', $id);
    $this->db->select('abono.*, pagar.saldo as saldo_total, pagar.abono as abono_total');
    $this->db->join('cuentas_por_pagar as pagar', 'pagar.id_cuentas = abono.id_cuentas_por_pagar');
    //$this->db->join('ventas as v', 'v.id_venta = cobrar.id_venta');
    $rows = $this->db->get('cuentas_por_pagar_abonos as abono');
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

}

/* End of file ClientModel.php */
