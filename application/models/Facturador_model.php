<?php

class Facturador_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_facturadores() {

        $sql = "SELECT f.*, d.nombre AS dominio 
                FROM facturador f 
                JOIN dominio d ON f.id_dominio= d.id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_facturador($id_facturador) {
        $sql = "SELECT f.*, d.nombre AS dominio 
                FROM facturador f 
                JOIN dominio d ON d.id_dominio= f.id_dominio
                WHERE f.id_facturador=$id_facturador LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_facturador($facturador) {
        $this->db->insert('facturador', $facturador);
        $id_facturador = $this->db->insert_id();
        $nuevo_facturador = $this->get_facturador($id_facturador);

        return $nuevo_facturador;
    }

    public function get_nombres($id_dominio) {
        if (!isset($id_dominio)) {
            $id_dominio = 1;
        }

        $sql = "SELECT f.id_facturador, f.nombre
                FROM facturador f 
                WHERE f.id_dominio= $id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
