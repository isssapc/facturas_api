<?php

//require(APPPATH . 'libraries/REST_Controller.php');

class facturadores extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('facturador_model');
    }

    public function index_get() {

        $datos = $this->facturador_model->get_facturadores();
        $this->response($datos);
    }

    public function index_post() {
        $facturador = $this->post('facturador');
        $datos = $this->facturador_model->add_facturador($facturador);
        $this->response($datos);
    }

    public function nombres_get() {
        $id_dominio=$this->get('id_dominio');
        $datos = $this->facturador_model->get_nombres($id_dominio);
        $this->response($datos);
    }

}
