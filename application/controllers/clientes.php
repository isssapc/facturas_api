<?php

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

    public function search_post() {
        $term = $this->post('term');
        $datos = $this->cliente_model->search_by_nombre($term);
        $this->response($datos);
    }
    
    public function update_put($id_cliente){
        $cliente=  $this->put('cliente');
        $datos=  $this->cliente_model->update_cliente($id_cliente,$cliente);
        $this->response($datos);
    }
            

}
