<?php
//require(APPPATH . 'libraries/REST_Controller.php');

class pagos extends MY_Controller {
    
     public function __construct() {
        parent::__construct();
        $this->load->model('pago_model');       
    }
    
      public function index_get() {             
       
       $datos = $this->pago_model->get_pagos();
       $this->response($datos);    
       
    }
    
}
