<?php

//require(APPPATH . 'libraries/REST_Controller.php');

class usuarios extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');
    }

    public function index_get() {

        $datos = $this->usuario_model->get_usuarios();
        $this->response($datos);
    }

    public function index_post() {
        $usuario = $this->post('usuario');
        $datos = $this->usuario_model->add_usuario($usuario);
        $this->response($datos);
    }

}
