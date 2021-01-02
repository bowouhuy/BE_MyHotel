<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController {

    function __construct()
    {
        // Construct the parent class
        
        parent::__construct();
        $this->load->model('Users');
        $this->load->model('Objek');

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
    }

    public function login_post(){
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $where = array(
            'user_mail' => $email,
            'user_password' => md5($password)
            );
        $result = $this->Users->login($where)->result_array();
        if($result != null){
            
            $this->response( [
                'status' => true,
                'message' => 'found',
                'data' => $result
            ], 200 );

        }else{
            $this->response( [
                'status' => false,
                'message' => 'No users were found'
            ], 404 );
        }
    }
    
    public function register_post(){

        $nama = $this->input->post('nama');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $role = $this->input->post('role');

        $data = array(
            'user_nama' => $nama,
            'user_mail' => $email,
            'user_password' => md5($password),
            'user_role' => $role
        );

        $response = $this->Users->register($data);
        
        if($response == null){
            $this->response(
                [
                    'status' => true,
                    'result' => "Success"
                ], 200
            );
        }else{
            $this->response(
                [
                    'status' => false,
                    'result' => $response
                ], 200
            );
        }
    }

    public function objek_get(){
        $objek = $this->input->get('objek');

        if($objek === null){
            $response = $this->Objek->list();
        }else{
            $response = $this->Objek->list($objek);
        }

        if($response){
            $this->response(
                [
                    'status' => true,
                    'result' => $response
                ]
            );
        }else{
            $this->response(
                [
                    'status' => false,
                    'result' => "No Objek Found"
                ]
            );
        }
    }

}