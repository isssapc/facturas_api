<?php

//require(APPPATH . 'libraries/REST_Controller.php');

class impuestos extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('impuesto_model');
    }

    public function index_get() {

        $datos = $this->impuesto_model->get_impuestos();
        $this->response($datos);
    }

    public function nombres_get() {

        $datos = $this->impuesto_model->get_nombres();
        $this->response($datos);
    }

    public function index_post() {
        $impuesto = $this->post('impuesto');
        $datos = $this->impuesto_model->add_impuesto($impuesto);
        $this->response($datos);
    }

}
