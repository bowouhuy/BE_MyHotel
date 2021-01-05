<?php

Class Transaksi extends CI_Model {

    public function add($data){
        if(! $this->db->insert('transaksi', $data)){
            
            return $this->db->error();
        }
    }

    public function getAll($id){
        if($id != null) {
            $result = $this->db->get_where('transaksi', array( 'transaksi_id' => $id))->row_array();
        }else{

            $result = $this->db->get('transaksi')->result_array();
        }

        return $result;
    }

    public function getTransaksibyNo($no){
        return $this->db->get_where('transaksi', array( 'transaksi_no' => $no));
    
    }

    public function getbyUserId($user_id){
        return $this->db->get_where('transaksi', array( 'user_id' => $user_id));
    }

    public function getLastTransaksiId(){
        $result = $this->db->query(
                    "SELECT transaksi_id FROM transaksi 
                     ORDER BY transaksi_id DESC
                     LIMIT 1
                    ");
        return $result;
    }

    public function updateSt($where){
        $this->db->set('transaksi_status', $where['transaksi_status']);
        $this->db->where('transaksi_id', $where['transaksi_id']);

        if(! $this->db->update('transaksi')){
            return "Success";
        }
        return $this->db->affected_rows();
    }

    public function countAll(){
        return $this->db->count_all_results('transaksi');
    }


}