<?php
require(APPPATH . 'libraries/REST_Controller.php');

class controldeobras extends REST_Controller {
    
     public function __construct() {
        parent::__construct();
        $this->load->model('lote_model');       
    }
    
      public function avances_lote_get() {             
       
       $datos = $this->lote_model->get_avances($this->get('id'));
       $this->response($datos);    
       
    }
    
}

