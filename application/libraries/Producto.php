<?php

class Producto {

    public $id_producto;
    public $impuesto;
    public $precio_unitario;
    public $descripcion;
    public $id_dominio;

    public function __set($name, $value) {
        if ($name === 'id_impuesto' || $name === 'tasa' || $name === 'tipo' || $name === 'descripcion_impuesto') {
            if ($name === 'descripcion_impuesto') {
                $this->impuesto['descripcion'] = $value;
            } else {
                $this->impuesto[$name] = $value;
            }
        }
    }

    public function __get($name) {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }

}
