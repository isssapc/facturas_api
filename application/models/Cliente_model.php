<?php

class Cliente_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_clientes() {

        $sql = "SELECT c.*, d.nombre AS dominio 
                FROM cliente c 
                JOIN dominio d ON c.id_dominio= d.id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_cliente($id_cliente) {
        $sql = "SELECT c.*, d.nombre AS dominio 
                FROM cliente c 
                JOIN dominio d ON d.id_dominio= c.id_dominio
                WHERE c.id_cliente=$id_cliente LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_cliente($cliente) {
        $this->db->insert('cliente', $cliente);
        $id_cliente = $this->db->insert_id();
        $nuevo_cliente = $this->get_cliente($id_cliente);

        return $nuevo_cliente;
    }

    public function update_cliente($id_cliente, $cliente) {

        $where = "id_cliente = $id_cliente";
        $sql = $this->db->update_string('cliente', $cliente, $where);
        $query = $this->db->query($sql);

        $updated = $this->get_cliente($id_cliente);

        return $updated;
    }

    public function get_nombres($id_dominio) {
        if (!isset($id_dominio)) {
            $id_dominio = 1;
        }

        $sql = "SELECT c.id_dominio, c.id_cliente, c.nombre 
                FROM cliente c 
                WHERE c.id_dominio= $id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function search_by_nombre($term) {
        $sql = "SELECT c.*, d.nombre AS dominio 
                FROM cliente c 
                JOIN dominio d ON c.id_dominio= d.id_dominio
                WHERE c.nombre LIKE '%" . $this->db->escape_like_str($term) . "%'";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
