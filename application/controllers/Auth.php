<?php

//require(APPPATH . 'libraries/REST_Controller.php');
require(APPPATH . 'libraries/jose/JOSE_JWT.php');
require(APPPATH . 'libraries/jose/JOSE_JWS.php');
require(APPPATH . 'libraries/jose/JOSE_URLSafeBase64.php');

class Auth extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');
        //$this->load->library('jose/JOSE_JWT');
        // $this->load->library('jose/JOSE_JWS');
        // $this->load->library('jose/JOSE_URLSafeBase64');
    }

    public function _create_token($user) {

        /*         * *
         * sub => Subject
         * iat => IssuedAt
         * exp => Expiration
         * iss => Issuer
         * aud => Audience
         * 
         */

        $payload = [
            "sub" => $user['id_usuario'],
            "rol" => $user['id_rol'],
            "iat" => time(),
            "exp" => time() + (60 * 60) //1 hora
        ];
        $jwt = new JOSE_JWT($payload);
        $jws = $jwt->sign($this->config->item('token_secret'));

        return $jws->toString();
    }

    public function login_post() {
        $email = $this->post('email');
        $password = $this->post('password');

        $usuario = $this->usuario_model->get_usuario_where('email', $email);

        if (!isset($usuario)) {
            $this->response(["error" => ["message" => "El email y/o password son incorrectos"]], REST_Controller::HTTP_UNAUTHORIZED);
        }

        if ($usuario["password"] === $password) {
            unset($usuario['password']);
            $this->response(["token" => $this->_create_token($usuario), "usuario" => $usuario]);
        } else {
            $this->response(["error" => ["message" => "El email y/o password son incorrectos"]], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function signup_post() {
        //$cliente = $this->post('cliente');
        //$datos = $this->login_model->add_cliente($cliente);
        $ramiro = [];
        $ramiro['nombre'] = "ramiro";
        $ramiro['ap_paterno'] = "jimenez";
        $ramiro['ap_materno'] = "arechar";
        $this->response($ramiro);
    }

    public function unlink_post() {
        //$cliente = $this->post('cliente');
        //$datos = $this->login_model->add_cliente($cliente);
        $ramiro = [];
        $ramiro['nombre'] = "ramiro";
        $ramiro['ap_paterno'] = "jimenez";
        $ramiro['ap_materno'] = "arechar";
        $this->response($ramiro);
    }

}
