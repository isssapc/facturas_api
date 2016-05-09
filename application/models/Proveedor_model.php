<?php

class Proveedor_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_proveedores() {

        $sql = "SELECT p.*, d.nombre AS dominio 
                FROM proveedor p 
                JOIN dominio d ON p.id_dominio= d.id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_proveedor($id_proveedor) {
        $sql = "SELECT p.*, d.nombre AS dominio 
                FROM proveedor p 
                JOIN dominio d ON d.id_dominio= p.id_dominio
                WHERE p.id_proveedor=$id_proveedor LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_proveedor($proveedor) {
        $this->db->insert('proveedor', $proveedor);
        $id_proveedor = $this->db->insert_id();
        $nuevo_proveedor = $this->get_proveedor($id_proveedor);

        return $nuevo_proveedor;
    }

    public function get_nombres($id_dominio) {
        if (!isset($id_dominio)) {
            $id_dominio = 1;
        }

        $sql = "SELECT p.id_dominio, p.id_proveedor, p.nombre 
                FROM proveedor p 
                WHERE p.id_dominio= $id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
