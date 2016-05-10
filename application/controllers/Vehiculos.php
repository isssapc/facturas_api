<?php

class vehiculos extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('vehiculo_model');
    }

    public function index_get() {

        $datos = $this->vehiculo_model->get_vehiculos();
        $this->response($datos);
    }

    public function vehiculo_get($id_vehiculo) {

        $datos = $this->vehiculo_model->get_vehiculo($id_vehiculo);
        $this->response($datos);
    }

    public function update_put($id_vehiculo) {
        $vehiculo = $this->put('vehiculo');
        $datos = $this->vehiculo_model->update_vehiculo($id_vehiculo, $vehiculo);
        $this->response($datos);
    }

    public function index_post() {
        $vehiculo = $this->post('vehiculo');
        $datos = $this->vehiculo_model->add_vehiculo($vehiculo);
        $this->response($datos);
    }



}
