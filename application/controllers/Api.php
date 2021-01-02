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
        Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
        Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
        Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
    }

    public function login_post(){
        // print_r($this->input->raw_input_stream);exit;
        $email = $this->post('user_mail');
        $password = $this->post('user_password');
        $where = array(
            'user_mail' => $email,
            'user_password' => md5($password)
            );
        // print_r($where);exit;
        $result = $this->Users->login($where)->row_array();
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

        $nama = $this->post('user_nama');
        $email = $this->post('user_mail');
        $password = $this->post('user_password');
        $role = 2;

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
        $objek_nama = $this->input->get('objek_nama');
        $objek_jenis = $this->input->get('objek_jenis');

        $nama = empty($objek_nama) ? '%' : $objek_nama;
        $jenis = empty($objek_jenis) ? '%' : $objek_jenis;
        $where= array(
            'objek_nama' => $nama,
            'objek_jenis' => $jenis
        );
        // print_r($where['objek_nama']);exit;
        $response = $this->Objek->list($where);
        

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

    public function add_post(){
        $config['upload_path']          = './assets';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100000;
        $config['max_width']            = 2048;
        $config['max_height']           = 1586;
        $filename = $this->post('file');
        
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file'))
            {
                    $error = array('error' => $this->upload->display_errors());
                    $this->response(
                        [
                            'status' => false,
                            'result' => $error
                        ]
                    );
            }
            else
            {
                    $data = array('upload_data' => $this->upload->data());
                    $this->response(
                        [
                            'status' => true,
                            'result' => base_url('assets/').$data['upload_data']['file_name']
                        ]
                    );
            }
    }

}