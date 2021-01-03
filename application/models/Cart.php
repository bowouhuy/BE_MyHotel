<?php

Class Cart extends CI_Model {

    public function add($data){
        if(! $this->db->insert('cart', $data)){
            
            return $this->db->error();
        }
    }

    public function getCartbyId($id){
        return $this->db->get_where('cart', array('user_id' => $id, 'transaksi_id' => null));
        
    }

    public function addTransaksiId($id, $user){
        $this->db->set('transaksi_id', $id);
        $this->db->where('user_id', $user);
        if(! $this->db->update('cart'))  {
            return $this->db->error();
        }
    }

    public function destroy($id){
        if(! $this->db->delete('cart', array('cart_id' => $id))) {
            return $this->db->error();
        }
            return $this->db->affected_rows();
    }
}