<?php

class Factura_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_facturas() {

        $sql = "SELECT f.*, 
                       d.nombre AS dominio, 
                       b.nombre AS facturador, 
                       c.nombre AS cliente,
                       t.descripcion AS factura_tipo
                FROM factura f 
                JOIN dominio d ON f.id_dominio= d.id_dominio 
                JOIN facturador b ON f.id_facturador=b.id_facturador 
                JOIN cliente c ON c.id_cliente= f.id_cliente 
                JOIN factura_tipo t ON t.id_factura_tipo=f.id_factura_tipo;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_factura($id_factura) {
        $sql = "SELECT f.*, 
                       d.nombre AS dominio, 
                       b.nombre AS facturador, 
                       c.nombre AS cliente,
                       t.descripcion AS factura_tipo
                FROM factura f 
                JOIN dominio d ON f.id_dominio= d.id_dominio 
                JOIN facturador b ON f.id_facturador=b.id_facturador 
                JOIN cliente c ON c.id_cliente= f.id_cliente 
                JOIN factura_tipo t ON t.id_factura_tipo=f.id_factura_tipo
                WHERE f.id_factura=$id_factura LIMIT 1;";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function add_factura($factura, $productos) {

        $this->db->insert('factura', $factura);
        $id_factura = $this->db->insert_id();
        $nueva_factura = $this->get_factura($id_factura);

        $this->add_factura_productos($id_factura, $productos);

        return $nueva_factura;
    }

//    public function add_factura_productos_impuestos($id_factura_producto, $impuesto) {
//
//        $sql = "INSERT INTO factura_producto_impuesto (id_factura_producto, id_impuesto) 
//              VALUES (?,?);";
//
//        $count = 0;
//        foreach ($impuestos as $i) {
//
//            $this->db->query($sql, array($id_factura_producto, $i['id_impuesto']));
//            $count+= $this->db->affected_rows();
//        }
//
//        return json_encode(array("count" => $count));
//    }

    public function add_factura_productos($id_factura, $productos) {

        $sql_productos = "INSERT INTO factura_producto (id_factura, cantidad, id_producto, precio_unitario, descripcion) 
              VALUES (?,?,?,?,?);";
        $sql_impuestos = "INSERT INTO factura_producto_impuesto (id_factura_producto, id_impuesto,tipo_impuesto, tasa_impuesto) 
              VALUES (?,?,?,?);";

        $count = 0;
        foreach ($productos as $p) {

            $this->db->query($sql_productos, array($id_factura, $p['cantidad'], $p['id_producto'], $p['precio_unitario'], $p['descripcion']));
            $id_factura_producto = $this->db->insert_id();
            $this->db->query($sql_impuestos, array($id_factura_producto, $p['impuesto']['id_impuesto'],$p['impuesto']['tipo'],$p['impuesto']['tasa']));
            $count+= $this->db->affected_rows();
        }

        return $count;
    }

}
