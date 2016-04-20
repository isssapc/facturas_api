<?php

//require_once (APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'libraries/jose/JOSE_JWT.php');
require(APPPATH . 'libraries/jose/JOSE_JWS.php');
require(APPPATH . 'libraries/jose/JOSE_URLSafeBase64.php');
require(APPPATH . 'libraries/jose/Exception.php');
require(APPPATH . 'libraries/jose/Exception/VerificationFailed.php');
require(APPPATH . 'libraries/jose/Exception/InvalidFormat.php');
require(APPPATH . 'libraries/jose/Exception/UnexpectedAlgorithm.php');
require(APPPATH . 'libraries/jose/Exception/DecryptionFailed.php');

class AuthMiddleware {

    protected $controller;
    protected $ci;
    public $claims;

    public function __construct($controller, $ci) {
        $this->controller = $controller;
        $this->ci = $ci;
    }

    public function run() {
        //$this->roles = array('somehting', 'view', 'edit'); 

        $headers = $this->controller->input->request_headers();

        if (!array_key_exists('Authorization', $headers)) {
            $this->controller->response(["error" => ["message" => "Inicie sesión para acceder a los recursos del sistema"]], REST_Controller::HTTP_UNAUTHORIZED);
        }

        $authorization = $headers['Authorization'];
        $token = explode(' ', $authorization)[1];
        $jwt = JOSE_JWT::decode($token);
        $jws = new JOSE_JWS($jwt);
        //throw Exception  Signature Verification Failed en caso de fallo
//        $this->controller->config->item('token_secret')
        $jws->verify($this->controller->config->item('token_secret'));

        if ($jws->claims['exp'] < time()) {
            $this->controller->response(["error" => ["message" => "Su sesión ha caducado"]], REST_Controller::HTTP_UNAUTHORIZED);
        }
        $this->claims = $jws->claims;
    }

}
