<?php
/**
 * This file is part of the OpenPyme2.
 *
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

defined('BASEPATH') or exit('No direct script access allowed');

class TallerModel extends CI_Model {

	var $table = "producto";
	var $pk = "id_producto";
	/**
	 * Function for create query for each Model, to show values on datatables
	 */

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
					$this->db->where("v.id_sucursal",$id_sucursal);
				} else {
					$this->db->or_like($sterm, $search);
					$this->db->where("v.id_sucursal",$id_sucursal);
				}
				$x++;
			}
		}
		$this->db->select("v.requiere_imei,v.imei_ingresado,v.id_trabajo_taller,v.id_estado,
		DATE_FORMAT(v.fecha,'%d-%m-%Y') as fecha, FORMAT(v.total,2) as total, v.guia,
		 c.nombre, e.descripcion, t.nombredoc,v.tipo_doc, tp.alias_tipopago"); // cambios 7-7-2022
		$this->db->from('trabajos_taller as v');
		$this->db->join('clientes as c', 'v.id_cliente = c.id_cliente', 'left');
		$this->db->join('estado as e', 'v.id_estado = e.id_estado', 'left');
		$this->db->join('tipodoc as t', 'v.tipo_doc = t.idtipodoc', 'left');
		// cambios 7-7-2022
		$this->db->join('tipo_pago as tp', 'v.tipo_pago = tp.id_tipopago', 'left');
		$this->db->where("v.id_sucursal",$id_sucursal);
		$this->db->limit($length, $start);
		$clients = $this->db->get();
		if ($clients->num_rows() > 0) {
			return $clients->result();
		} else {
			return 0;//$this->db->last_query();
		}
	}

	function total_rows(){
		$clients = $this->db->get("trabajos_taller");
		if ($clients->num_rows() > 0) {
			return $clients->num_rows();
		} else {
			return 0;
		}
	}

	function create_dt_query(){
		//table and sql query string
		$table = 'trabajos_taller as v';
		$query  = "v.id_trabajo_taller,v.id_estado,";
		$query .= "DATE_FORMAT(v.fecha,'%d-%m-%Y') as fecha,";
		$query .= "FORMAT(v.total,2) as total, v.guia,c.nombre, e.descripcion,";
		$query .= "t.nombredoc,v.tipo_doc,tp.descripcion as tipopago";
		$sql_array =array('table'=>$table,'query'=>$query);
		//create join array
		/*
		First parameter: table name
		Second parameter: keys for join
		Third parameter: join types based on sql, optional
		join Options are: left, right, outer, inner, left outer, and right outer.
		*/
		$j1=array('clientes as c', 'v.id_cliente = c.id_cliente');
		$j2=array('estado as e', 'v.id_estado = e.id_estado', 'left');
		$j3=array('tipodoc as t', 'v.tipo_doc = t.idtipodoc', 'left');
		$j4=array('tipo_pago as tp', 'v.tipo_pago = tp.id_tipopago', 'left');

		$join = array($j1,$j2,$j3,$j4);
		//add join parameters if exist join
		if(isset($join) && !empty($join)){
				$sql_array['join']=$join;
		}
		return $sql_array;
	}

	function get_state($id)
	{
		$this->db->where('activo', 1);
		$this->db->where('id_producto', $id);
		$clients = $this->db->get($this->table);
		if ($clients->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}


	function get_precios()
	{
		$porc = $this->db->get("porcentajes");
		return $porc->result();
	}
	function get_porcent_desc_cliente($id_clasifica)
	{
		$this->db->where("deleted", "0");
		$this->db->where('id_clasifica', $id_clasifica);
		$query = $this->db->get("clasifica_cliente");
		return $porc->result();
	}
	function get_clasifica_cliente($id_cliente)
	{
		$this->db->where("deleted", "0");
		$this->db->where('id_clasifica', $id_clasifica);
		$query = $this->db->get("clasifica_cliente");
		return $porc->result();
	}
	function get_precios_exis($id)
	{
		$porc = $this->db->where("id_producto", $id)->get("producto_precio");
		return $porc->result();
	}

	function get_productos($query, $id_sucursal)
	{
		$arr_values = array(
			'producto.codigo_barra' => $query,
			'producto.nombre' => $query,
			'producto_color.color' => $query,
		);
		$this->db->select('stock.id_stock,stock.id_color,producto.id_producto, producto.nombre,stock.cantidad,producto_color.color');
		$this->db->from("stock");
		$this->db->join("producto", "producto.id_producto=stock.id_producto");
		$this->db->join("sucursales", "sucursales.id_sucursal=stock.id_sucursal");
		$this->db->join("producto_color", "stock.id_color=producto_color.id_color");
		//$this->db->or_like($arr_values);
		$this->db->where("( producto.nombre LIKE '%" . $query . "%'  OR producto.codigo_barra LIKE '%" . $query . "%' OR producto_color.color LIKE '%" . $query . "%')", NULL, FALSE);
		$this->db->where("stock.cantidad >", "0");
		$this->db->where('producto.activo', 1);
		$this->db->where("stock.id_sucursal", $id_sucursal);
		$this->db->where('producto.deleted', '0');

		$queryx = $this->db->get();
		if ($queryx->num_rows() > 0) {
			return $queryx->result();
		} else {
			return NULL;
		}
	}
	function query_prod($query, $id_sucursal, $field_query)
	{
		$this->db->select('stock.id_stock,stock.id_color,producto.id_producto, producto.nombre, marca, modelo,stock.cantidad,producto_color.color');
		$this->db->from("stock");
		$this->db->join("producto", "producto.id_producto=stock.id_producto");
		$this->db->join("producto_color", "stock.id_color=producto_color.id_color", "left");
		$this->db->like($field_query, $query);
		$this->db->where('activo', 1);
		$this->db->where("stock.id_sucursal", $id_sucursal);
		$this->db->where('cantidad>0');
		$this->db->where('deleted', '0');
		$queryx = $this->db->get();
		if ($queryx->num_rows() > 0) {
			return $queryx->result();
		} else {
			return NULL;
		}
	}
	function get_servicios($query, $id_sucursal)
	{
		$this->db->select('id_servicio, nombre, activo');
		$this->db->from("servicio");
		$this->db->like('nombre', $query);
		$this->db->where('activo', 1);
		$this->db->where('deleted', '0');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	function get_row_servicios($id)
	{
		$this->db->where('id_servicio', $id);
		$data = $this->db->get("servicio");
		if ($data->num_rows() > 0) {
			return $data->row();
		} else {
			return 0;
		}
	}
	function get_clientes($query)
	{
		$this->db->select('id_cliente, nombre,clasifica');
		$this->db->like('nombre', $query);
		$this->db->where('deleted', '0');
		$this->db->where('activo', '1');
		$query = $this->db->get('clientes');
		if ($query->num_rows() > 0) return $query->result();
		else return NULL;
	}
	function get_producto($id)
	{
		$this->db->select('id_producto, nombre,  precio_sugerido');
		$this->db->where('id_producto', $id);
		$query = $this->db->get('producto');
		if ($query->num_rows() > 0) return $query->row();
		else return NULL;
	}

	/*trabajo_taller de productos*/
	/*insertar a tabla con confirmacion*/
	function inAndCon($table, $data)
	{
		$this->db->insert($table, $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return NULL;
		}
	}
	/*obtener el valor del correlativo y actualizar la tabla*/
	function get_max_correlative($corr, $id_sucursal)
	{
		$this->db->select($corr);
		$this->db->where('id_sucursal', $id_sucursal);
		$query = $this->db->get('correlativo');
		if ($query->num_rows() > 0) {
			$correlativo = $query->row();
			$this->db->set($corr, "$corr+1", FALSE);
			$this->db->where('id_sucursal', $id_sucursal);
			$this->db->update('correlativo'); // gives UPDATE mytable SET field = field+1 WHERE id = 2

			$num = $correlativo->$corr;
			$num = $num + 1;
			return $num;
		} else {
			return NULL;
		}
	}

	/**
	 * get the correlative and increment by one
	 *
	 * @param string $corr indicates the correlative that we want to obtain
	 * @param int $id_sucursal
	 *
	 * @return int
	 */
	function get_correlative($corr, $id_sucursal)
	{
		$this->db->select($corr);
		$this->db->where('id_sucursal', $id_sucursal);
		$query = $this->db->get('correlativo');
		if ($query->num_rows() > 0) {
			$correlativo = $query->row();
			$num = $correlativo->$corr + 1;
			//	$num = $num+1;
			return $num;
		} else {
			return NULL;
		}
	}
	function get_date_correlative($id_sucursal)
	{
		$this->db->select('fecha');
		$this->db->where('id_sucursal', $id_sucursal);
		$query = $this->db->get('correlativo');
		if ($query->num_rows() > 0) {
			$correlativo = $query->row();
			return $correlativo->fecha;
		} else {
			return NULL;
		}
	}
	function update_correlative($corr, $val, $id_sucursal)
	{
		$this->db->select($corr);
		$this->db->where('id_sucursal', $id_sucursal);
		$query = $this->db->get('correlativo');
		if ($query->num_rows() > 0) {
			$correlativo = $query->row();
			$this->db->set($corr, $val, FALSE);
			$this->db->where('id_sucursal', $id_sucursal);
			$this->db->update('correlativo'); // gives UPDATE mytable SET field = field+1 WHERE id = 2

			return $correlativo->$corr;
		} else {
			return NULL;
		}
	}
	function get_trabajo_taller($id_trabajo_taller)
	{
		// code...
		$this->db->select("clientes.nombre, trabajos_taller.fecha,trabajos_taller.id_sucursal_despacho");
		$this->db->from("trabajos_taller");
		$this->db->join("clientes", "clientes.id_cliente = trabajos_taller.id_cliente");
		$this->db->where('id_trabajo_taller', $id_trabajo_taller);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return 0;
		}
	}

	function get_stock($id_producto, $id_color, $id_sucursal)
	{
		$this->db->where('id_sucursal', $id_sucursal);
		$this->db->where('id_producto', $id_producto);
		$this->db->where('id_color', $id_color);
		$query = $this->db->get('stock');
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			$data = array(
				'id_producto' => $id_producto,
				'id_sucursal' => $id_sucursal,
				'id_color' => $id_color,
				'cantidad' => 0,
			);
			$this->db->insert('stock', $data);
			if ($this->db->affected_rows() > 0) {
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

	function update_cost($costo, $id_producto, $precios)
	{
		$costo_iva = round($costo * 1.13, 2);

		foreach ($precios as $row_por) {
			$id_precio = $row_por->id_precio;
			$porcentaje = $row_por->porcentaje;

			$resultado = round($costo * ($porcentaje / 100), 2);
			$resultado1 = $costo + $resultado;
			$resultado2 = round($resultado1 * 1.13, 2);

			$this->db->set('costo', "$costo");
			$this->db->set('costo_iva', "$costo_iva");
			$this->db->set('ganancia', "$resultado");
			$this->db->set('total', "$resultado1");
			$this->db->set('total_iva', "$resultado2");
			$this->db->where('id_producto', $id_producto);
			$this->db->where('id_precio', $id_precio);
			$this->db->update('producto_precio');
		}
	}

	function get_one_row($tabla, $where)
	{
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

	function get_detail_rows($tabla, $where)
	{

		foreach ($where as $key => $value) {
			if ($key != 'null') {
				$this->db->where($key, $value);
			}
		}
		$detail = $this->db->get($tabla);
		if ($detail->num_rows() > 0) {
			return $detail->result();
		} else {
			return 0;
		}
	}

	function get_detail_ci($id_carga)
	{
		$this->db->select("producto_color.color,icd.*, p.nombre");
		$this->db->from('trabajos_taller_detalle AS icd');
		$this->db->join('producto as p', 'p.id_producto=icd.id_producto');
		$this->db->join('producto_color', 'producto_color.id_color=icd.id_color', "left");
		$this->db->where('id_trabajo_taller', $id_carga);
		$this->db->where('icd.tipo_prod', 0);
		$this->db->order_by('icd.id_detalle', 'ASC');
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			return $data->result();
		} else {
			return 0;
		}
	}
	function get_detail_serv($id_carga)
	{

		$this->db->select("icd.*, s.nombre,s.precio_sugerido,s.precio_minimo");
		$this->db->from('trabajos_taller_detalle AS icd');
		$this->db->join('servicio as s', 's.id_servicio=icd.id_producto');
		$this->db->where('icd.id_trabajo_taller', $id_carga);
		$this->db->where('icd.tipo_prod', 1);
		$this->db->order_by('icd.id_detalle', 'ASC');
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			return $data->result();
		} else {
			return NULL;
		}
	}


	function get_imei_ci($id_trabajo_taller)
	{

		$this->db->select("ici.id_detalle,ici.id_trabajo_taller,ici.id_producto,p.nombre,ici.chain");
		$this->db->from('trabajos_taller_imei as ici ');
		$this->db->join(' producto as p ', 'ici.id_producto=p.id_producto');
		$this->db->group_by("chain");
		$this->db->order_by('p.n_imei', 'ASC');
		$this->db->where('id_trabajo_taller', $id_trabajo_taller);
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			return $data->result();
		} else {
			return 0;
		}
	}

	function get_imei_ci_det($chain)
	{

		$this->db->select("ici.id_imei,ici.imei");
		$this->db->from('trabajos_taller_imei as ici ');
		$this->db->where('chain', $chain);
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			return $data->result();
		} else {
			return 0;
		}
	}

	function get_imei_productos($id_detalle)
	{

		$this->db->select("ici.id_imei,ici.imei");
		$this->db->from('trabajos_taller_imei as ici ');
		$this->db->where('id_detalle', $id_detalle);
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			return $data->result();
		} else {
			return 0;
		}
	}


/*
	function has_imei_required($id_producto)
	{
		$this->db->where('id_producto', $id_producto);
		$this->db->where('imei', '1');
		$data = $this->db->get("producto");
		if ($data->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	*/
	function getGarantia($id_producto, $estado)
	{
		$this->db->where('id_producto', $id_producto);
		$data = $this->db->get("producto");
		if ($data->num_rows() > 0) {
			$dat = $data->row();
			if ($estado == "NUEVO") {
				// code...
				return 0;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	function get_reservado($id_producto, $id_trabajo_taller, $id_color)
	{
		$this->db->select("sum(trabajos_taller_detalle.cantidad) as reservado");
		$this->db->from("trabajos_taller_detalle");
		$this->db->where("id_producto", $id_producto);
		$this->db->where("id_color", $id_color);
		$this->db->where("id_trabajo_taller", $id_trabajo_taller);
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			return $data->row();
		} else {
			return 0;
		}
	}

	/**
	 * get the stock reserved for pre-sales
	 *
	 * Check the presales, with the current date and obtain the reserved
	 * stock of your products except for the presale indicated by sending
	 * your id
	 *
	 * @param int $id_sucursal
	 * @param int $id_producto
	 * @param int $id_color
	 * @param int $id_trabajo_taller
	 *
	 * @return int reserved stock
	 *
	 */
	function get_reserved_stock($id_sucursal, $id_producto, $id_color, $id_trabajo_taller = 0){

		// get reserved stock in sales
		$trabajos_taller = $this->db->select("COALESCE(sum(vd.cantidad), 0) AS reservado")
		->join("trabajos_taller AS v", "v.id_trabajo_taller = vd.id_trabajo_taller")
		->where([
			"v.id_sucursal_despacho" => $id_sucursal,
			"vd.id_producto"         => $id_producto,
			"vd.id_color"            => $id_color,
			"vd.id_trabajo_taller !="         => $id_trabajo_taller,
			"v.id_estado"            => 1,
			"v.fecha" 	             => date("Y-m-d")])
		->get("trabajos_taller_detalle AS vd")->row();

		// get stock reserved for transfers
		$traslados = $this->db->select("COALESCE(sum(td.cantidad), 0) AS reservado")
		->join("traslado AS t", "t.id_traslado = td.id_traslado")
		->where([
			"t.id_sucursal_despacho" => $id_sucursal,
			"t.estado" => 0,
			"td.id_producto" => $id_producto,
			"td.id_color" => $id_color
		])
		->get("traslado_detalle AS td")->row();


		return $trabajos_taller->reservado + $traslados->reservado;
	}

	function get_dev_ante($id_producto, $id_trabajo_taller)
	{
		$this->db->select("sum(cant) as dev_ante");
		$this->db->from("devoluciones_det");
		$this->db->where("id_producto", $id_producto);
		$this->db->where("id_trabajo_taller", $id_trabajo_taller);
		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			return $data->row();
		} else {
			return NULL;
		}
	}
	function get_tipodoc_alias($alias)
	{
		$this->db->select("idtipodoc");
		$this->db->from("tipodoc");
		$this->db->where("alias", $alias);

		$data = $this->db->get();
		if ($data->num_rows() > 0) {
			return $data->row();
		} else {
			return NULL;
		}
	}
	function get_porcent_client($clasifica)
	{
		$this->db->select('porcentaje');
		$this->db->where('id_clasifica', $clasifica);
		$this->db->where('deleted', 0);
		$row = $this->db->get('clasifica_cliente');
		if ($row->num_rows() > 0) {
			return $row->row();
		} else {
			return NULL;
		}
	}
	function get_tipodoc()
	{
		$this->db->where('cliente', 1);
		$row = $this->db->get('tipodoc');
		if ($row->num_rows() > 0) {
			return $row->result();
		} else {
			return NULL;
		}
	}


	/**
	 * Search the database if it finds a cash opening
	 *
	 * @param int $id_sucursal
	 * @param string $fecha
	 *
	 * @return object
	 */
	function get_caja_activa($id_sucursal, $fecha)
	{
		$query = $this->db->where([
			"id_sucursal" => $id_sucursal,
			"fecha" => $fecha,
			"vigente" => "1"])->get("apertura_caja");

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return NULL;
		}
	}

	function get_detalle_trabajo_taller($id_trabajo_taller)
	{
		$sql = $this->db->query("SELECT GROUP_CONCAT(CONCAT_WS(' ' ,'-', p.nombre , c.color)
		SEPARATOR '<br>') as detalle_v
		FROM trabajos_taller_detalle as vd INNER JOIN producto as p
		ON p.id_producto = vd.id_producto
		INNER JOIN producto_color as c ON c.id_color = vd.id_color
		WHERE vd.id_trabajo_taller = $id_trabajo_taller
		AND vd.tipo_prod=0");
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return 0;
		}
	}
	function get_detalle_serv($id_trabajo_taller)
	{
		 $q="SELECT GROUP_CONCAT(CONCAT_WS(' ' ,'-', s.nombre )
		 SEPARATOR '<br>') AS detalle_v
		 FROM trabajos_taller_detalle AS vd INNER JOIN servicio AS s
		 ON s.id_servicio = vd.id_producto
 	 		WHERE vd.id_trabajo_taller = $id_trabajo_taller
			AND vd.tipo_prod=1";
		$sql = $this->db->query($q);
		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return 0;
		}
	}
}

/* End of file tallerModel.php */
