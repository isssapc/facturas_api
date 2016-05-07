<?php

//require(APPPATH . 'libraries/REST_Controller.php');

class productos extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('producto_model');
    }

    public function index_get() {

        $datos = $this->producto_model->get_productos();
        $this->response($datos);
    }

    public function index_post() {
        $producto = $this->post('producto');
        $datos = $this->producto_model->nuevo_producto($producto);
        $this->response($datos);
    }

    public function nombres_get() {
        $id_dominio = $this->get('id_dominio');
        $datos = $this->producto_model->get_nombres($id_dominio);
        $this->response($datos);
    }

}
