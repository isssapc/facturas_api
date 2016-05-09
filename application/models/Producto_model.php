<?php

require(APPPATH . 'libraries/Producto.php');

class Producto_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_productos() {

        $sql = "SELECT p.*, d.nombre AS dominio 
                FROM producto p 
                JOIN dominio d ON p.id_dominio= d.id_dominio;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function buscar_productos($id_dominio, $search) {
        if (!isset($id_dominio)) {
            $id_dominio = 1;
        }

        $sql = "SELECT p.id_producto, 
                       p.id_dominio, 
                       p.descripcion, 
                       p.precio_unitario, 
                       p.default_id_impuesto AS id_impuesto,
                       i.descripcion AS descripcion_impuesto,
                       i.tasa AS tasa,
                       i.tipo AS tipo
                FROM producto p
                JOIN impuesto i ON i.id_impuesto=p.default_id_impuesto
                WHERE p.id_dominio= $id_dominio AND p.descripcion LIKE '%" . $this->db->escape_like_str($search) . "%';";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_producto($id_producto) {
        $sql = "SELECT p.*, d.nombre AS dominio 
                FROM producto p 
                JOIN dominio d ON p.id_dominio= d.id_dominio
                WHERE p.id_producto=$id_producto LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function nuevo_producto($producto) {
        $this->db->insert('producto', $producto);
        $id_producto = $this->db->insert_id();
        $nuevo_producto = $this->get_producto($id_producto);

        return $nuevo_producto;
    }

    public function get_nombres($id_dominio) {

        if (!isset($id_dominio)) {
            $id_dominio = 1;
        }

        $sql = "SELECT p.id_producto, 
                       p.id_dominio, 
                       p.descripcion, 
                       p.precio_unitario, 
                       p.default_id_impuesto AS id_impuesto,
                       i.descripcion AS descripcion_impuesto,
                       i.tasa AS tasa,
                       i.tipo AS tipo
                FROM producto p
                JOIN impuesto i ON i.id_impuesto=p.default_id_impuesto
                WHERE p.id_dominio= $id_dominio;";

        $query = $this->db->query($sql);
        return $query->result('Producto');
        //$productos = $query->result_array();
        //$this->fix_impuestos($productos);
        //return $productos;
    }

    function fix_impuestos(&$productos) {
        foreach ($productos as &$p) {
            $id_impuesto = $p['impuesto'];
            $p['impuesto'] = array("id_impuesto" => $id_impuesto);
        }
        //eliminamos la referencia del ultimo elemento
        unset($p);
    }

}
