<?php

class Orden_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_ordenes() {

        $sql = "SELECT o.*, d.nombre AS dominio, v.num_serie AS vehiculo, c.nombre AS cliente 
                FROM orden_servicio o 
                JOIN dominio d ON o.id_dominio= d.id_dominio
                JOIN cliente c ON c.id_cliente=o.id_cliente
                JOIN vehiculo v ON v.id_vehiculo=o.id_vehiculo;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_orden($id_orden) {
        $sql = "SELECT o.*, d.nombre AS dominio 
                FROM orden_servicio o 
                JOIN dominio d ON d.id_dominio= o.id_dominio
                WHERE o.id_orden=$id_orden LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_orden($orden) {
        $this->db->insert('orden_servicio', $orden);
        $id_orden = $this->db->insert_id();
        $nuevo_orden = $this->get_orden($id_orden);

        return $nuevo_orden;
    }

    public function update_orden($id_orden, $orden) {

        $where = "id_orden = $id_orden";
        $sql = $this->db->update_string('orden_servicio', $orden, $where);
        $query = $this->db->query($sql);

        $updated = $this->get_orden($id_orden);

        return $updated;
    }

//    public function get_nombres($id_dominio) {
//        if (!isset($id_dominio)) {
//            $id_dominio = 1;
//        }
//
//        $sql = "SELECT o.id_dominio, o.id_orden, o.nombre 
//                FROM orden_servicio o 
//                WHERE o.id_dominio= $id_dominio;";
//
//        $query = $this->db->query($sql);
//        return $query->result_array();
//    }
//
//    public function search_by_nombre($term) {
//        $sql = "SELECT o.*, d.nombre AS dominio 
//                FROM orden_servicio o 
//                JOIN dominio d ON o.id_dominio= d.id_dominio
//                WHERE o.nombre LIKE '%" . $this->db->escape_like_str($term) . "%'";
//
//        $query = $this->db->query($sql);
//        return $query->result_array();
//    }

}
