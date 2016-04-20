<?php

class Preferencia_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_preferencias() {

        $sql = "SELECT p.*, d.nombre AS dominio 
                FROM preferencia p 
                JOIN dominio d ON d.id_dominio= p.id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_preferencia($id_preferencia) {
        $sql = "SELECT p.*, d.nombre AS dominio 
                FROM preferencia p 
                JOIN dominio d ON d.id_dominio= p.id_dominio
                WHERE p.id_preferencia=$id_preferencia LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_preferencia($preferencia) {
        $this->db->insert('preferencia', $preferencia);
        $id_preferencia = $this->db->insert_id();
        $nueva_preferencia = $this->get_preferencia($id_preferencia);

        return $nueva_preferencia;
    }

    public function get_nombres($id_dominio) {
        if (!isset($id_dominio)) {
            $id_dominio = 1;
        }

        $sql = "SELECT p.id_dominio, p.id_preferencia, p.descripcion 
                FROM preferencia p 
                WHERE p.id_dominio= $id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
