<?php

class Usuario_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_usuarios() {

        $sql = "SELECT u.*, d.nombre AS dominio 
                FROM usuario u 
                JOIN dominio d ON u.id_dominio= d.id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_usuario($id_usuario) {
        $sql = "SELECT u.*, d.nombre AS dominio 
                FROM usuario u 
                JOIN dominio d ON d.id_dominio= u.id_dominio
                WHERE u.id_usuario=$id_usuario LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_usuario($usuario) {
        $this->db->insert('usuario', $usuario);
        $id_usuario = $this->db->insert_id();
        $nuevo_usuario = $this->get_usuario($id_usuario);

        return $nuevo_usuario;
    }
    
    public function get_usuario_where($key, $value){
        $sql="SELECT u.* FROM usuario u WHERE $key='$value';";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

}
