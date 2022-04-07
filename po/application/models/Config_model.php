<?php
class Config_model extends CI_Model {

 function get_data($id_sucursal){
    $this->db->select('id,nombre,direccion,telefono,logo,FORMAT((iva*100),0) as iva,FORMAT((retencion*100),0) as retencion');
    $query = $this->db->where('id',$id_sucursal);
    $query = $this->db->get("sucursal");
    if ($query->num_rows() > 0) {
        return $query->row();
    }
    return 0;
 }
}
?>
