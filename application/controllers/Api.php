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
        $this->load->model('Cart');
        $this->load->model('Transaksi');
        Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
        Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
        Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
    }

    public function login_post(){

        $email = $this->post('user_mail');
        $password = $this->post('user_password');
        $where = array(
            'user_mail' => $email,
            'user_password' => md5($password)
            );

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

    public function user_get(){
        $user_id = $this->input->get('user_id');
        $response = $this->Users->get_user($user_id)->row_array();
        
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

    public function objek_get(){
        $objek_nama = $this->input->get('objek_nama');
        $objek_jenis = $this->input->get('objek_jenis');
        $objek_id = $this->input->get('objek_id');
        
        $nama = empty($objek_nama) ? '%' : $objek_nama;
        $jenis = empty($objek_jenis) ? '%' : $objek_jenis;
        $where= array(
            'objek_nama' => $nama,
            'objek_jenis' => $jenis
        );

        $response = $this->Objek->list($where, $objek_id);
        

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

    public function cart_post(){
        $user = $this->post('user_id');
        $objek = $this->post('objek_id');
        $harga = $this->post('objek_harga');
        $s = $this->post('tanggal_mulai');
        $e =$this->post('tanggal_selesai');
        $start = new DateTime($s);
        $end = new DateTime($e);
        $cart_harga = ($end->diff($start)->format("%a")) * $harga;
        $data = array(
            'user_id' => $user,
            'objek_id' => $objek,
            'cart_harga' => $cart_harga,
            'tanggal_mulai' => $s,
            'tanggal_selesai' => $e
        );
        $response = $this->Cart->add($data);
        
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

    public function cart_get(){
        $id = $this->input->get('user_id');
        $response = $this->Cart->getCartbyId($id)->result_array();
        
        $this->response(
            [
                'status' => true,
                'result' => $response
            ]
        );
    }

    public function cartDelete_post(){
        $id = $this->post('cart_id');
        $response = $this->Cart->destroy($id);
        if($response > 0){
            $this->response(
                [
                    'status' => true,
                    'result' => "Success Delete"
                ]
            );
        }else{
            $this->response(
                [
                    'status' => false,
                    'result' => "Id Not Found"
                ]
            );
        }
    }

    public function transaksi_post(){

        $last_transaksi_id = $this->Transaksi->getLastTransaksiId()->row_array();
        $transaksi_id = $last_transaksi_id['transaksi_id'];
        
        if (empty($last_transaksi_id)){
            $transaksi_id = 1;
        }
        $no = date('dmY').$transaksi_id;
        $user = $this->post('user_id');
        $transaksi_harga = $this->post('transaksi_harga');
        $date = date('Y-m-d');
        $status = 'waiting';
    
        $data = array(
            'transaksi_no' => $no,
            'user_id' => $user,
            'transaksi_harga' => $transaksi_harga,
            'transaksi_tanggal' => $date,
            'transaksi_status' => $status
        );
        $response = $this->Transaksi->add($data);
        $query = $this->Transaksi->getTransaksibyNo($no)->row_array();
        $addTransaksiId = $this->Cart->addTransaksiId($query['transaksi_id'], $user);

        if($response == null && $addTransaksiId == null){
            $this->response(
                [
                    'status' => true,
                    'result' => "Success"
                ]
            );
        }else{
            $this->response(
                [
                    'status' => false,
                    'result' => $response
                ]
            );
        }
    }

    public function transaksi_get(){
        $user = $this->get('user_id');
        $id = $this->get('transaksi_id');

        if($user){
            $response = $this->Transaksi->getbyUserId($user)->result_array();
        }else{
            $response = $this->Transaksi->getAll($id);
        }

        if($response){
            $this->response(
                [
                    'status' =>true,
                    'result' => $response
                ]
            );
        }else{
            $this->response(
                [
                    'status' =>false,
                    'result' => "User id Not Found"
                ]
            );
        }
    }

    public function transaksiUpdateSt_post(){
        $id = $this->post('transaksi_id');
        $status = $this->post('transaksi_status');
        $where = array(
            'transaksi_id' => $id,
            'transaksi_status' =>$status
        );

        $response = $this->Transaksi->updateSt($where);
        
        if($response > 0){
            $this->response(
                [
                    'status' => true,
                    'result' => "Success Update"
                ]
            );
        }else{
            $this->response(
                [
                    'status' => false,
                    'result' => "Id Not Found / No one Change"
                ]
            );
        }
    }
        
}