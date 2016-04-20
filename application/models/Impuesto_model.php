<?php

class Impuesto_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_impuestos() {

        $sql = "SELECT i.*, d.nombre AS dominio 
                FROM impuesto i 
                JOIN dominio d ON d.id_dominio= i.id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_impuesto($id_impuesto) {
        $sql = "SELECT i.*, d.nombre AS dominio 
                FROM impuesto i 
                JOIN dominio d ON d.id_dominio= i.id_dominio
                WHERE i.id_impuesto=$id_impuesto LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_impuesto($impuesto) {
        $this->db->insert('impuesto', $impuesto);
        $id_impuesto = $this->db->insert_id();
        $nuevo_impuesto = $this->get_impuesto($id_impuesto);

        return $nuevo_impuesto;
    }

}
