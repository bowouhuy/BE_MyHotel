<?php

Class Transaksi extends CI_Model {

    public function add($data){
        if(! $this->db->insert('transaksi', $data)){
            
            return $this->db->error();
        }
    }

    public function getAll(){
        $this->db->get('transaksi');
    }

    public function getTransaksibyNo($no){
        return $this->db->get_where('transaksi', array( 'transaksi_no' => $no));
    
    }

    public function getbyUserId($user_id){
        return $this->db->get_where('transaksi', array( 'user_id' => $user_id));
    }

}