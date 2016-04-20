<?php

class Pago_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_pagos() {

        $sql = "SELECT p.*, d.nombre AS dominio
                FROM pago p 
                JOIN dominio d ON d.id_dominio= p.id_dominio ;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
