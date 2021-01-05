<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Admin extends RestController {

    function __construct()
    {
        // Construct the parent class
        
        parent::__construct();
        $this->load->model('Users');
        $this->load->model('Objek');
        $this->load->model('Cart');
        $this->load->model('Transaksi');
        $this->load->model('Hotel');
        $this->load->model('Users');
        Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
        Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
        Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
    }

    public function addHotel_post(){
        $nama = $this->post('hotel_nama');
        $alamat = $this->post('hotel_alamat');
        $data = array(
            'hotel_nama' => $nama,
            'hotel_alamat' => $alamat,
        );

        $response = $this->Hotel->addHotel($data);
        
        if(! $response){
            $this->response(
                [
                    'status' => true,
                    'result' => "Success"
                ]
            );
        }else{
            $this->response(
                [
                    'status' => true,
                    'result' => $response
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

    public function hotel_get(){
        $id = $this->input->get('hotel_id');
        // print_r($id);exit;
        $response = $this->Hotel->list($id);
        
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

    public function objekDelete_post(){
        $id = $this->post('objek_id');
        $response = $this->Objek->destroy($id);
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

    public function editObjek_post(){
        $id = $this->post('objek_id');
        $nama = $this->post('objek_nama');
        $keterangan = $this->post('objek_keterangan');
        $harga = $this->post('objek_harga');
        $path_foto=$this->post('path_foto');
        $config['upload_path']          = './assets';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100000;
        $config['max_width']            = 2048;
        $config['max_height']           = 1586;
        $filename = $this->post('file');
        
        $this->load->library('upload', $config);
        $this->upload->do_upload('file');
     
        $upload_data = $this->upload->data();
        // print_r($upload_data['file_name']);exit;
        if($upload_data['file_name']){
            $foto = base_url('assets/').$upload_data['file_name'];
        }else{
            $foto = $path_foto;
        }
        $data = array(
            'objek_id' => $id,
            'objek_nama' => $nama,
            'objek_keterangan' => $keterangan,
            'objek_harga' => $harga,
            'objek_foto' => $foto
        );
        // print_r($data);exit;

        $response = $this->Objek->editObjek($data);
        if(! $response){
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
        
        public function editHotel_post(){
        $id = $this->post('hotel_id');
        $nama = $this->post('hotel_nama');
        $alamat = $this->post('hotel_alamat');
        $data = array(
            'hotel_id' => $id,
            'hotel_nama' => $nama,
            'hotel_alamat' => $alamat,
        );

        $response = $this->Hotel->editHotel($data);
        if(! $response){
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

    public function totalUser_get(){

        $response = $this->Users->countAll();
        $this->response(
            [
                'status' => true,
                'result' => $response
            ]
            );
    }
    public function totalTransaksi_get(){

        $response = $this->Transaksi->countAll();
        $this->response(
            [
                'status' => true,
                'result' => $response
            ]
            );
    }
    public function totalHotel_get(){

        $response = $this->Hotel->countAll();
        $this->response(
            [
                'status' => true,
                'result' => $response
            ]
            );
    }
    public function totalObjek_get(){

        $response = $this->Objek->countAll();
        $this->response(
            [
                'status' => true,
                'result' => $response
            ]
            );
    }

    public function hotelDelete_post(){
        $id = $this->post('hotel_id');
        $response = $this->Hotel->destroy($id);
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

}