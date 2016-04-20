<?php

//require(APPPATH . 'libraries/REST_Controller.php');

class preferencias extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('preferencia_model');
    }

    public function index_get() {

        $datos = $this->preferencia_model->get_preferencias();
        $this->response($datos);
    }

    public function index_post() {
        $preferencia = $this->post('preferencia');
        $datos = $this->preferencia_model->add_preferencia($preferencia);
        $this->response($datos);
    }

    public function nombres_get() {
        $id_dominio = $this->get('id_dominio');
        $datos = $this->preferencia_model->get_nombres($id_dominio);
        $this->response($datos);
    }

}
