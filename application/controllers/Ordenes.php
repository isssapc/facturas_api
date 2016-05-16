<?php

class Ordenes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('orden_model');
    }

    public function index_get() {

        $datos = $this->orden_model->get_ordenes();
        $this->response($datos);
    }

    public function orden_get($id_orden) {

        $datos = $this->orden_model->get_orden($id_orden);
        $this->response($datos);
    }

    public function index_post() {
        $orden = $this->post('orden');
        $datos = $this->orden_model->add_orden($orden);
        $this->response($datos);
    }

//    public function nombres_get() {
//        $id_dominio = $this->get('id_dominio');
//        $datos = $this->orden_model->get_nombres($id_dominio);
//        $this->response($datos);
//    }
//    public function search_post() {
//        $term = $this->post('term');
//        $datos = $this->orden_model->search_by_nombre($term);
//        $this->response($datos);
//    }

    public function update_put($id_orden) {
        $orden = $this->put('orden');
        $datos = $this->orden_model->update_orden($id_orden, $orden);
        $this->response($datos);
    }

}
