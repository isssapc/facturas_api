<?php

class Vehiculo_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_vehiculos() {

        $sql = "SELECT v.*, d.nombre AS dominio 
                FROM vehiculo v 
                JOIN dominio d ON v.id_dominio= d.id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_vehiculo($id_vehiculo) {
        $sql = "SELECT v.*, d.nombre AS dominio 
                FROM vehiculo v 
                JOIN dominio d ON d.id_dominio= v.id_dominio
                WHERE v.id_vehiculo=$id_vehiculo LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_vehiculo($vehiculo) {
        $this->db->insert('vehiculo', $vehiculo);
        $id_vehiculo = $this->db->insert_id();
        $nuevo_vehiculo = $this->get_vehiculo($id_vehiculo);

        return $nuevo_vehiculo;
    }

    public function update_vehiculo($id_vehiculo, $vehiculo) {

        $where = "id_vehiculo = $id_vehiculo";
        $sql = $this->db->update_string('vehiculo', $vehiculo, $where);
        $query = $this->db->query($sql);

        $vehiculo = $this->get_vehiculo($id_vehiculo);

        return $vehiculo;
    }

    public function search_by_serie($term) {

        $sql = "SELECT v.*, d.nombre AS dominio 
                FROM vehiculo v 
                JOIN dominio d ON v.id_dominio= d.id_dominio
                WHERE v.num_serie LIKE '%" . $this->db->escape_like_str($term) . "%'";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
