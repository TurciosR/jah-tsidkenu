<?php

class Imprimir_model extends CI_Model
{
  function get_factura($id_factura)
  {
    $factura = $this->db->get_where("factura", array("id_factura" => $id_factura))->row();
    return $factura;
  }

  function get_cliente($id_cliente)
  {
    $cliente = $this->db->get_where("cliente", array("id" => $id_cliente))->row();
    return $cliente;
  }

  function get_factura_detalle($id_factura)
  {
    $factura_detalle = $this->db->get_where("factura_detalle", array("id_factura" => $id_factura))->result();
    return $factura_detalle;
  }

}

?>
