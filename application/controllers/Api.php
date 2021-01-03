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

    public function addObjek_post(){
        $hotel = $this->post('hotel_id');
        $nama = $this->post('objek_nama');
        $keterangan = $this->post('objek_keterangan');
        $jenis = $this->post('objek_jenis');
        $harga = $this->post('objek_harga');
        $status = "available";
        
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
        }else{  
            $upload_data = array('upload_data' => $this->upload->data());
            $data = array(
                'hotel_id' => $hotel,
                'objek_nama' => $nama,
                'objek_keterangan' => $keterangan,
                'objek_jenis' => $jenis,
                'objek_foto' => base_url('assets/').$upload_data['upload_data']['file_name'],
                'objek_harga' => $harga,
                'objek_status' => $status
            );
            $response = $this->Objek->addObjek($data);
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

    public function cart_delete(){
        $id = $this->delete('cart_id');
        print_r($id);exit;
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
        $user = $this->post('user_id');
        $no = $this->post('transaksi_no');
        $date = $this->post('transaksi_tanggal');
        $status = $this->post('transaksi_status');
        $data = array(
            'transaksi_no' => $no,
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

}