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

<<<<<<< HEAD
    public function getbyUserId($user_id){
        return $this->db->get_where('transaksi', array( 'user_id' => $user_id));
    }

=======
    public function getLastTransaksiId(){
        $result = $this->db->query(
                    "SELECT transaksi_id FROM transaksi 
                     ORDER BY transaksi_id DESC
                     LIMIT 1
                    ");
        return $result;
    }


>>>>>>> decafec43c8760498e2b741ad32ee5d48b45839b
}