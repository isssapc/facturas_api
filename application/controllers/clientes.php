<?php

//require(APPPATH . 'libraries/REST_Controller.php');

class clientes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cliente_model');
    }

    public function index_get() {

        $datos = $this->cliente_model->get_clientes();
        $this->response($datos);
    }

    public function index_post() {
        $cliente = $this->post('cliente');
        $datos = $this->cliente_model->add_cliente($cliente);
        $this->response($datos);
    }

    public function nombres_get() {
        $id_dominio = $this->get('id_dominio');
        $datos = $this->cliente_model->get_nombres($id_dominio);
        $this->response($datos);
    }

}
