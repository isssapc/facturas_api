<?php

class Proveedores extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('proveedor_model');
    }

    public function index_get() {

        $datos = $this->proveedor_model->get_proveedores();
        $this->response($datos);
    }

    public function index_post() {
        $proveedor = $this->post('proveedor');
        $datos = $this->proveedor_model->add_proveedor($proveedor);
        $this->response($datos);
    }

    public function nombres_get() {
        $id_dominio = $this->get('id_dominio');
        $datos = $this->proveedor_model->get_nombres($id_dominio);
        $this->response($datos);
    }

    public function proveedor_get($id_proveedor) {
        $datos = $this->proveedor_model->get_proveedor($id_proveedor);
        $this->response($datos);
    }

    public function update_put($id_proveedor) {
        $proveedor = $this->put('proveedor');
        $datos = $this->proveedor_model->update_proveedor($id_proveedor, $proveedor);
        $this->response($datos);
    }

}
