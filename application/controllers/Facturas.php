<?php

//require(APPPATH . 'libraries/REST_Controller.php');
//require(APPPATH . 'libraries/jose/JOSE_JWT.php');
//require(APPPATH . 'libraries/jose/JOSE_JWS.php');
//require(APPPATH . 'libraries/jose/JOSE_URLSafeBase64.php');
//require(APPPATH . 'libraries/jose/Exception.php');
//require(APPPATH . 'libraries/jose/Exception/VerificationFailed.php');
//require(APPPATH . 'libraries/jose/Exception/InvalidFormat.php');
//require(APPPATH . 'libraries/jose/Exception/UnexpectedAlgorithm.php');
//require(APPPATH . 'libraries/jose/Exception/DecryptionFailed.php');

class facturas extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('factura_model');
    }

    public function test() {
        return ["ramiro", "jimenez", "arechar"];
    }

    protected function middleware() {
        //return array('admin_auth', 'someother|except:index,list', 'yet_another_one|only:index');
        //return ['auth'];
        return [];
    }

//    public function index_get() {
//        $datos = $this->factura_model->get_facturas();
//        $this->response(array("facturas" => $datos, "jwt_claims"=> $this->middlewares['auth']->claims));
//    }

    public function index_get() {
        $datos = $this->factura_model->get_facturas();
        $this->response(array("facturas" => $datos));
    }

    public function detalle_get() {
        $id_factura = $this->get('id_factura');
        $datos = $this->factura_model->get_factura($id_factura);
        $this->response($datos);
    }

    public function index_post() {
        $factura = $this->post('factura');
        $productos = $this->post('productos');
        $datos = $this->factura_model->add_factura($factura, $productos);
        $this->response(array("factura" => $datos));
    }

}
